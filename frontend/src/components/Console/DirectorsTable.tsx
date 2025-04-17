import { TablePlaceholder } from '@/components/Console/TablePlaceholder'
import { DirectorsTableRow } from './DirectorsTableRow'
import type { UseQueryResult } from 'react-query'
import { Genre } from '@/types/Genre'
import style from './minimalTable.module.scss'

type Props = {
    query: UseQueryResult<Genre[], unknown>
}

export const DirectorsTable: React.FC<Props> = ({ query }) => (
    <div className={style.table}>
        <div className={style.head}>
            <span className={style.headcell}>ID</span>
            <span className={style.headcell}>Name</span>
            <span className={style.headcell}>Created</span>
            <span className={style.headcell}>Updated</span>
            <span className={style.headcell}>Actions</span>
        </div>
        <div className={style.body}>
            {query.status === 'loading' ? (
                <TablePlaceholder cols={5} rows={6} />
            ) : (
                query.data?.map(director => (
                    <DirectorsTableRow key={director.id} director={director} />
                ))
            )}
        </div>
    </div>
)
