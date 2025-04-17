import { useQuery } from 'react-query'
import { useModal } from '@/providers/ModalProvider'
import { TicketsTable } from '@/components/Console/TicketsTable'
import { CreateRecordBtn } from '@/components/Buttons/CreateRecordBtn'
import { getTickets } from '@/services/ticketService'
import style from './console.module.scss'

export const TicketsPage: React.FC = () => {
    const { showModal } = useModal()
    const query = useQuery(['tickets'], getTickets)

    return (
        <div className={style.tablePage}>
            <div className={style.tableBtns}>
                <CreateRecordBtn onClick={() => showModal('TicketFormModal')} />
            </div>
            <TicketsTable query={query} />
        </div>
    )
}
