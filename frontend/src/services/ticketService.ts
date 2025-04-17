import { axiosClient } from '@/utils/axiosClient'
import { parseDates } from '@/utils/parseDates'
import type { Ticket, UpdateTicket } from '@/types/Ticket'

export const getTickets = async () => {
    const res = await axiosClient.get('/tickets')

    return parseDates(res.data) as Ticket[]
}

export const getTicket = async (id: number) => {
    const res = await axiosClient.get('/tickets/' + id)

    return parseDates(res.data) as Ticket
}

export const createTicket = async (data: object) => {
    const res = await axiosClient.post('/tickets', data)

    return parseDates(res.data) as Ticket
}

export const updateTicket = async ({ id, data }: { id: number; data: UpdateTicket }) => {
    const res = await axiosClient.patch('/tickets/' + id, data)

    return parseDates(res.data) as Ticket
}

export const deleteTicket = async (id: number) => {
    const res = await axiosClient.delete('/tickets/' + id)

    return res.data
}
