import { TablePlaceholder } from '@/components/Console/TablePlaceholder'
import { HallsTableRow } from './HallsTableRow'
import type { UseQueryResult } from 'react-query'
import { Hall } from '@/types/Hall'
import style from './hallsTable.module.scss'

type Props = {
    query: UseQueryResult<Hall[], unknown>
}

export const HallsTable: React.FC<Props> = ({ query }) => (
    <div className={style.table}>
        <div className={style.head}>
            <span className={style.headcell}>ID</span>
            <span className={style.headcell}>Name</span>
            <span className={style.headcell}>Capacity</span>
            <span className={style.headcell}>Type</span>
            <span className={style.headcell}>Created</span>
            <span className={style.headcell}>Updated</span>
            <span className={style.headcell}>Actions</span>
        </div>
        <div className={style.body}>
            {query.status === 'loading' ? (
                <TablePlaceholder cols={7} rows={6} />
            ) : (
                query.data?.map(hall => <HallsTableRow key={hall.id} hall={hall} />)
            )}
        </div>
    </div>
)
