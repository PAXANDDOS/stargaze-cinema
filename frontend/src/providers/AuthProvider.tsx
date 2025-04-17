import { useState, createContext, useContext } from 'react'
import { Navigate } from '@tanstack/react-location'
import Cookies from 'js-cookie'
import { hasAdminRole } from '@/services/authService'
import type { User } from '@/types/User'

const AuthContext = createContext<AuthContextType>(null!)
export const useAuth = () => useContext(AuthContext)

interface AuthContextType {
    user: User | null
    signIn: SignIn
    signOut: SignOut
}

interface SignIn {
    (user: User | User, token: { value: string; ttl: number }, ttl?: number): void
}

interface SignOut {
    (): void
}

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const [user, setUser] = useState<User | null>(
        Cookies.get('user') && JSON.parse(Cookies.get('user')!)
    )

    const signIn: SignIn = (user, token) => {
        const userCombined = {
            ...user,
            token: `Bearer ${token.value}`,
        }
        Cookies.set('user', JSON.stringify(userCombined), {
            expires: new Date(token.ttl * 1000),
            sameSite: 'strict',
        })
        setUser(userCombined)
    }

    const signOut: SignOut = () => {
        Cookies.remove('user')
        setUser(null)
    }

    return (
        <>
            <AuthContext.Provider
                value={{
                    user,
                    signIn,
                    signOut,
                }}
            >
                {children}
            </AuthContext.Provider>
        </>
    )
}

export const RequireAuth: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const cookie = Cookies.get('user')

    if (!cookie) {
        return <Navigate to="/signin" replace />
    }

    return <>{children}</>
}

export const RequireAdmin: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const cookie = Cookies.get('user')

    if (!cookie) {
        return <Navigate to="/signin" replace />
    }

    if (!hasAdminRole(JSON.parse(cookie) as User)) {
        return <Navigate to="/" replace />
    }

    return <>{children}</>
}

export const RequireAnon: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const cookie = Cookies.get('user')

    if (cookie) {
        return <Navigate to="/account" replace />
    }

    return <>{children}</>
}
