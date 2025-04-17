import { axiosClient } from '@/utils/axiosClient'
import { parseDates } from '@/utils/parseDates'
import type { Language } from '@/types/Language'

export const getLanguages = async () => {
    const res = await axiosClient.get('/languages')

    return parseDates(res.data) as Language[]
}

export const getLanguage = async (id: number) => {
    const res = await axiosClient.get('/languages/' + id)

    return parseDates(res.data) as Language
}
