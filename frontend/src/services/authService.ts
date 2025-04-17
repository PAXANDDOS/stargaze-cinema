import Cookies from 'js-cookie'
import type { User } from '@/types/User'

export const getUserCookie = (): User | null => {
    const cookie = Cookies.get('user')
    if (!cookie) return null
    return JSON.parse(cookie) as User
}

export const hasAdminRole = (user: User | null): boolean => {
    if (!user) return false
    return user.roles.includes('Administrator')
}
