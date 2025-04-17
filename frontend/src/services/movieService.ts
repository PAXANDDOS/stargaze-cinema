import { axiosClient } from '@/utils/axiosClient'
import { parseDates } from '@/utils/parseDates'
import type { Movie, UpdateMovie, PaginatedMovies } from '@/types/Movie'

export const getMovies = async (params?: URLSearchParams) => {
    const res = await axiosClient.get('/movies', { params })

    return {
        paginator: res.data.paginator,
        data: parseDates(res.data.data),
    } as PaginatedMovies
}

export const getMovie = async (id: number | string) => {
    const res = await axiosClient.get('/movies/' + id)

    return parseDates(res.data) as Movie
}

export const createMovie = async (data: FormData) => {
    const res = await axiosClient.post('/movies', data)

    return parseDates(res.data) as Movie
}

export const updateMovie = async ({ id, data }: { id: number; data: UpdateMovie }) => {
    const res = await axiosClient.patch('/movies/' + id, data)

    return parseDates(res.data) as Movie
}

export const deleteMovie = async (id: number) => {
    const res = await axiosClient.delete('/movies/' + id)

    return res.data
}
