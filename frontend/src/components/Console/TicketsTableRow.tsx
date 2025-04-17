import { useQueryClient, useMutation } from 'react-query'
import { useModal } from '@/providers/ModalProvider'
import { useToast } from '@/providers/ToastProvider'
import { deleteTicket } from '@/services/ticketService'
import { Edit, Cross } from '@/assets/icons/Misc'
import type { Ticket } from '@/types/Ticket'
import style from './hallsTable.module.scss'

export const TicketsTableRow: React.FC<{ ticket: Ticket }> = ({ ticket }) => {
    const toast = useToast()
    const { showModal } = useModal()
    const queryCache = useQueryClient()
    const { mutate } = useMutation(deleteTicket, {
        onMutate: () => toast.loading('Deleting...'),
        onSuccess: () => toast.success('Ticket deleted'),
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['tickets']),
    })

    const remove = () => mutate(ticket.id)

    return (
        <>
            <div className={style.bodycell}>
                <span>{ticket.id}</span>
            </div>
            <div className={style.bodycell}>
                <span>{ticket.user.name}</span>
            </div>
            <div className={style.bodycell}>
                <span>{ticket.session.id}</span>
            </div>
            <div className={style.bodycell}>
                <span>{ticket.place}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{ticket.created_at.toLocaleString()}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{ticket.updated_at.toLocaleString()}</span>
            </div>
            <div className={style.bodycell}>
                <button onClick={() => showModal('TicketFormModal', { ticket })}>
                    <Edit height={14} />
                </button>
                <button onClick={remove}>
                    <Cross height={14} />
                </button>
            </div>
        </>
    )
}
