import axios from 'axios'
import { useState } from 'react'
import { Link, useNavigate } from '@tanstack/react-location'
import { useToast } from '@/providers/ToastProvider'
import { LabeledInput } from '@/components/Inputs/LabeledInput'
import { InputSubmit } from '@/components/Inputs/InputSubmit'
import style from './auth.module.scss'

export const SignUpPage: React.FC = () => {
    const navigate = useNavigate()
    const { promise } = useToast()
    const [state, setState] = useState({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    })

    const handleChange = ({ target }: React.ChangeEvent<HTMLInputElement>) =>
        setState({
            ...state,
            [target.name]: target.value,
        })

    const handleSumbit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()
        promise({
            promise: axios.post(`${import.meta.env.APP_API_URL}/auth/signup`, state),
            title: 'Signing up...',
            onSuccess: () => {
                navigate({ to: '/signin' })
            },
        })
    }

    return (
        <div className={style.authPage}>
            <form onSubmit={handleSumbit}>
                <LabeledInput
                    label="Your name"
                    name="name"
                    value={state.name}
                    onChange={handleChange}
                    required
                />
                <LabeledInput
                    label="Your email"
                    type="email"
                    name="email"
                    value={state.email}
                    onChange={handleChange}
                    required
                />
                <LabeledInput
                    label="Your password"
                    type="password"
                    name="password"
                    value={state.password}
                    onChange={handleChange}
                    required
                />
                <LabeledInput
                    label="Confirm your password"
                    type="password"
                    name="password_confirmation"
                    value={state.password_confirmation}
                    onChange={handleChange}
                    required
                />
                <InputSubmit label="Sign up" />
                <span>
                    Already a member? <Link to="/signin">Sign in!</Link>
                </span>
            </form>
        </div>
    )
}
