import { TablePlaceholder } from '@/components/Console/TablePlaceholder'
import { TicketsTableRow } from './TicketsTableRow'
import type { UseQueryResult } from 'react-query'
import { Ticket } from '@/types/Ticket'
import style from './hallsTable.module.scss'

type Props = {
    query: UseQueryResult<Ticket[], unknown>
}

export const TicketsTable: React.FC<Props> = ({ query }) => (
    <div className={style.table}>
        <div className={style.head}>
            <span className={style.headcell}>ID</span>
            <span className={style.headcell}>User</span>
            <span className={style.headcell}>Session ID</span>
            <span className={style.headcell}>Place</span>
            <span className={style.headcell}>Created</span>
            <span className={style.headcell}>Updated</span>
            <span className={style.headcell}>Actions</span>
        </div>
        <div className={style.body}>
            {query.status === 'loading' ? (
                <TablePlaceholder cols={7} rows={6} />
            ) : (
                query.data?.map(ticket => <TicketsTableRow key={ticket.id} ticket={ticket} />)
            )}
        </div>
    </div>
)
