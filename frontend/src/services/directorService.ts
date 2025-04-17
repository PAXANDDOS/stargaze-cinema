import { axiosClient } from '@/utils/axiosClient'
import { parseDates } from '@/utils/parseDates'
import type { Director, UpdateDirector } from '@/types/Director'

export const getDirectors = async () => {
    const res = await axiosClient.get('/directors')

    return parseDates(res.data) as Director[]
}

export const getDirector = async (id: number) => {
    const res = await axiosClient.get('/directors/' + id)

    return parseDates(res.data) as Director
}

export const createDirector = async (data: object) => {
    const res = await axiosClient.post('/directors', data)

    return parseDates(res.data) as Director
}

export const updateDirector = async ({ id, data }: { id: number; data: UpdateDirector }) => {
    const res = await axiosClient.patch('/directors/' + id, data)

    return parseDates(res.data) as Director
}

export const deleteDirector = async (id: number) => {
    const res = await axiosClient.delete('/directors/' + id)

    return res.data
}
