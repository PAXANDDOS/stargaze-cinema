import { axiosClient } from '@/utils/axiosClient'
import { parseDates } from '@/utils/parseDates'
import type { Genre, UpdateGenre } from '@/types/Genre'

export const getGenres = async () => {
    const res = await axiosClient.get('/genres')

    return parseDates(res.data) as Genre[]
}

export const getGenre = async (id: number) => {
    const res = await axiosClient.get('/genres/' + id)

    return parseDates(res.data) as Genre
}

export const createGenre = async (data: object) => {
    const res = await axiosClient.post('/genres', data)

    return parseDates(res.data) as Genre
}

export const updateGenre = async ({ id, data }: { id: number; data: UpdateGenre }) => {
    const res = await axiosClient.patch('/genres/' + id, data)

    return parseDates(res.data) as Genre
}

export const deleteGenre = async (id: number) => {
    const res = await axiosClient.delete('/genres/' + id)

    return res.data
}
