import axios from 'axios'
import { getUserCookie } from '@/services/authService'

export const axiosClient = axios.create({
    baseURL: import.meta.env.APP_API_URL,
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        Authorization: getUserCookie()?.token as string,
    },
})
