import { TablePlaceholder } from '@/components/Console/TablePlaceholder'
import { SessionsTableRow } from './SessionsTableRow'
import type { UseQueryResult } from 'react-query'
import { Session } from '@/types/Session'
import style from './sessionsTable.module.scss'

type Props = {
    query: UseQueryResult<Session[], unknown>
}

export const SessionsTable: React.FC<Props> = ({ query }) => (
    <div className={style.table}>
        <div className={style.head}>
            <span className={style.headcell}>ID</span>
            <span className={style.headcell}>Movie</span>
            <span className={style.headcell}>Hall</span>
            <span className={style.headcell}>Begins</span>
            <span className={style.headcell}>Ends</span>
            <span className={style.headcell}>Actions</span>
        </div>
        <div className={style.body}>
            {query.status === 'loading' ? (
                <TablePlaceholder cols={6} rows={6} />
            ) : (
                query.data?.map(session => <SessionsTableRow key={session.id} session={session} />)
            )}
        </div>
    </div>
)
