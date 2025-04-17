import { useState } from 'react'
import { useQuery } from 'react-query'
import { MovieBanner } from '@/components/Movie/MovieBanner'
import { useDebounce } from '@/hooks/useDebouce'
import { MovieBannerPlaceholder } from '@/components/Movie/MovieBannerPlaceholder'
import { Loading } from '@/assets/icons/Misc'
import { Paginator } from '@/components/Paginator/Paginator'
import { getMovies } from '@/services/movieService'
import { getGenres } from '@/services/genreService'
import style from './movies.module.scss'

type Method = 'ASC' | 'DESC'
type Order = 'title' | 'year' | 'price' | 'duration' | 'created_at'

export const MoviesPage: React.FC = () => {
    const [search, setSearch] = useState('')
    const [method, setMethod] = useState<Method>('DESC')
    const [order, setOrder] = useState<Order>('created_at')
    const [page, setPage] = useState(1)
    const debouncedSearch = useDebounce(search, 800)
    const moviesQuery = useQuery(['movies', debouncedSearch, order, method, page], () => {
        const params = new URLSearchParams()
        params.append('title', debouncedSearch)
        params.append('order', order)
        params.append('orderMethod', method)
        params.append('page', page.toString())

        return getMovies(params)
    })
    const genresQuery = useQuery(['genres'], getGenres)

    const changeOrder = ({ target }: React.ChangeEvent<HTMLSelectElement>) =>
        setOrder(target.value as Order)
    const changeOrderMethod = () => setMethod(method === 'DESC' ? 'ASC' : 'DESC')

    return (
        <div className={style.moviesPage}>
            <h1>Now in Stargaze Cinema</h1>
            <div className={style.filterBlock}>
                <div className={style.filterSearch}>
                    <input
                        type="text"
                        value={search}
                        placeholder="Search for a title..."
                        onChange={({ target }) => setSearch(target.value)}
                    />
                </div>
                <div className={style.filterFilters}>
                    {moviesQuery.status === 'success' && (
                        <Paginator paginator={moviesQuery.data?.paginator} setPage={setPage} />
                    )}
                    {moviesQuery.isLoading && (
                        <button className={style.filterBtn}>
                            <Loading />
                        </button>
                    )}
                    <button type="button" className={style.filterBtn} onClick={changeOrderMethod}>
                        &#8645;
                    </button>
                    <select
                        title="order"
                        name="order"
                        className={style.filterSelect}
                        value={order}
                        onChange={changeOrder}
                    >
                        <option disabled>Order by</option>
                        <option value="created_at">Date</option>
                        <option value="price">Price</option>
                        <option value="year">Year</option>
                        <option value="runtime">Runtime</option>
                        <option value="title">Alphabetical</option>
                    </select>
                    <select
                        title="genre"
                        name="genre"
                        className={style.filterSelect}
                        defaultValue="Genre"
                    >
                        <option disabled>Genre</option>
                        {genresQuery.data?.map(genre => {
                            return (
                                <option key={genre.id} value={genre.id}>
                                    {genre.name}
                                </option>
                            )
                        })}
                    </select>
                </div>
            </div>
            <div className={style.moviesList}>
                {moviesQuery.status === 'loading' ? (
                    <MovieBannerPlaceholder items={6} />
                ) : (
                    moviesQuery.data?.data.map(movie => (
                        <MovieBanner key={movie.id} movie={movie} />
                    ))
                )}
            </div>
            {moviesQuery.status === 'success' && (
                <Paginator paginator={moviesQuery.data?.paginator} setPage={setPage} />
            )}
        </div>
    )
}
