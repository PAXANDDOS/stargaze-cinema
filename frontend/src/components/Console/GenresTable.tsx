import { TablePlaceholder } from '@/components/Console/TablePlaceholder'
import { GenresTableRow } from './GenresTableRow'
import type { UseQueryResult } from 'react-query'
import { Genre } from '@/types/Genre'
import style from './minimalTable.module.scss'

type Props = {
    query: UseQueryResult<Genre[], unknown>
}

export const GenresTable: React.FC<Props> = ({ query }) => (
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
                query.data?.map(genre => <GenresTableRow key={genre.id} genre={genre} />)
            )}
        </div>
    </div>
)
