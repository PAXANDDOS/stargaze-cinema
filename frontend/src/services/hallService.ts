import { axiosClient } from '@/utils/axiosClient'
import { parseDates } from '@/utils/parseDates'
import type { Hall, UpdateHall } from '@/types/Hall'

export const getHalls = async () => {
    const res = await axiosClient.get('/halls')

    return parseDates(res.data) as Hall[]
}

export const getHall = async (id: number) => {
    const res = await axiosClient.get('/halls/' + id)

    return parseDates(res.data) as Hall
}

export const createHall = async (data: object) => {
    const res = await axiosClient.post('/halls', data)

    return parseDates(res.data) as Hall
}

export const updateHall = async ({ id, data }: { id: number; data: UpdateHall }) => {
    const res = await axiosClient.patch('/halls/' + id, data)

    return parseDates(res.data) as Hall
}

export const deleteHall = async (id: number) => {
    const res = await axiosClient.delete('/halls/' + id)

    return res.data
}
