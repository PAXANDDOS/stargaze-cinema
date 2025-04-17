import { TablePlaceholder } from '@/components/Console/TablePlaceholder'
import { MoviesTableRow } from './MoviesTableRow'
import type { UseQueryResult } from 'react-query'
import style from './moviesTable.module.scss'
import { PaginatedMovies } from '@/types/Movie'

type Props = {
    query: UseQueryResult<PaginatedMovies, unknown>
}

export const MoviesTable: React.FC<Props> = ({ query }) => (
    <div className={style.table}>
        <div className={style.head}>
            <span className={style.headcell}>ID</span>
            <span className={style.headcell}>Title</span>
            <span className={style.headcell}>Synopsis</span>
            <span className={style.headcell}>Poster</span>
            <span className={style.headcell}>Price</span>
            <span className={style.headcell}>Year</span>
            <span className={style.headcell}>Runtime</span>
            <span className={style.headcell}>Rating</span>
            <span className={style.headcell}>Language</span>
            <span className={style.headcell}>Countries</span>
            <span className={style.headcell}>Genres</span>
            <span className={style.headcell}>Directors</span>
            <span className={style.headcell}>Actions</span>
        </div>
        <div className={style.body}>
            {query.status === 'loading' ? (
                <TablePlaceholder cols={13} rows={10} />
            ) : (
                query.data?.data.map(movie => <MoviesTableRow key={movie.id} movie={movie} />)
            )}
        </div>
    </div>
)
