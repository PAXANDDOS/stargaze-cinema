import { axiosClient } from '@/utils/axiosClient'
import { parseDates } from '@/utils/parseDates'
import type { Country } from '@/types/Country'

export const getCountries = async () => {
    const res = await axiosClient.get('/countries')

    return parseDates(res.data) as Country[]
}

export const getCountry = async (id: number) => {
    const res = await axiosClient.get('/countries/' + id)

    return parseDates(res.data) as Country
}
