import axios from 'axios'
import type { User } from '@/types/User'
import { useState } from 'react'
import { Link, useNavigate } from '@tanstack/react-location'
import { useToast } from '@/providers/ToastProvider'
import { useAuth } from '@/providers/AuthProvider'
import { LabeledInput } from '@/components/Inputs/LabeledInput'
import { InputSubmit } from '@/components/Inputs/InputSubmit'
import style from './auth.module.scss'

export const SignInPage: React.FC = () => {
    const { signIn } = useAuth()
    const navigate = useNavigate()
    const { promise } = useToast()
    const [email, setEmail] = useState('')
    const [password, setPassword] = useState('')

    const handleChange = ({ target }: React.ChangeEvent<HTMLInputElement>) => {
        switch (target.name) {
            case 'email':
                setEmail(target.value)
                break
            case 'password':
                setPassword(target.value)
                break
        }
    }

    const handleSumbit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()
        promise({
            promise: axios.post(`${import.meta.env.APP_API_URL}/auth/signin`, { email, password }),
            title: 'Signing in...',
            onSuccess: ({
                data,
            }: {
                data: { token: { value: string; ttl: number }; user: User }
            }) => {
                signIn(data.user, data.token)
                navigate({ to: '/' })
                location.reload()
            },
        })
    }

    return (
        <>
            <div className={style.authPage}>
                <form onSubmit={handleSumbit}>
                    <LabeledInput
                        label="Your email"
                        type="email"
                        name="email"
                        value={email}
                        onChange={handleChange}
                    />
                    <LabeledInput
                        label="Your password"
                        type="password"
                        name="password"
                        value={password}
                        onChange={handleChange}
                    />
                    <InputSubmit label="Sign in" />
                    <span>
                        Not a member yet? <Link to="/signup">Sign up!</Link>
                    </span>
                </form>
            </div>
        </>
    )
}
