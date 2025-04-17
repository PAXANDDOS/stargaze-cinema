import { axiosClient } from '@/utils/axiosClient'
import { parseDates } from '@/utils/parseDates'
import type { Session, UpdateSession } from '@/types/Session'

export const getSessions = async () => {
    const res = await axiosClient.get('/sessions')

    return parseDates(res.data) as Session[]
}

export const getSession = async (id: number) => {
    const res = await axiosClient.get('/sessions/' + id)

    return parseDates(res.data) as Session
}

export const createSession = async (data: object) => {
    const res = await axiosClient.post('/sessions', data)

    return parseDates(res.data) as Session
}

export const updateSession = async ({ id, data }: { id: number; data: UpdateSession }) => {
    const res = await axiosClient.patch('/sessions/' + id, data)

    return parseDates(res.data) as Session
}

export const deleteSession = async (id: number) => {
    const res = await axiosClient.delete('/sessions/' + id)

    return res.data
}
