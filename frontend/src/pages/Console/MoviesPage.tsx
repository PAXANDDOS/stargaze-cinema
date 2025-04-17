import { useState } from 'react'
import { useQuery } from 'react-query'
import { useModal } from '@/providers/ModalProvider'
import { MoviesTable } from '@/components/Console/MoviesTable'
import { CreateRecordBtn } from '@/components/Buttons/CreateRecordBtn'
import { getMovies } from '@/services/movieService'
import { Paginator } from '@/components/Paginator/Paginator'
import { useDebounce } from '@/hooks/useDebouce'
import style from './console.module.scss'

export const MoviesPage: React.FC = () => {
    const { showModal } = useModal()
    const [search, setSearch] = useState('')
    const debouncedSearch = useDebounce(search, 800)
    const [page, setPage] = useState(1)
    const query = useQuery(['movies', page, debouncedSearch], () => {
        const params = new URLSearchParams()
        params.append('title', debouncedSearch)
        params.append('order', 'id')
        params.append('orderMethod', 'ASC')
        params.append('page', page.toString())

        return getMovies(params)
    })

    return (
        <div className={style.tablePage}>
            <div className={style.tableBtns}>
                <div className={style.filterSearch}>
                    <input
                        type="text"
                        value={search}
                        placeholder="Search for a title..."
                        onChange={({ target }) => setSearch(target.value)}
                    />
                </div>
                <CreateRecordBtn onClick={() => showModal('MovieFormModal')} />
            </div>
            <MoviesTable query={query} />
            {query.status === 'success' && (
                <Paginator paginator={query.data?.paginator} setPage={setPage} />
            )}
        </div>
    )
}
