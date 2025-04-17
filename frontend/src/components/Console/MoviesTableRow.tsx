import { useQueryClient, useMutation } from 'react-query'
import { useModal } from '@/providers/ModalProvider'
import { useToast } from '@/providers/ToastProvider'
import { deleteMovie } from '@/services/movieService'
import { Edit, Cross } from '@/assets/icons/Misc'
import type { Movie } from '@/types/Movie'
import style from './moviesTable.module.scss'

export const MoviesTableRow: React.FC<{ movie: Movie }> = ({ movie }) => {
    const toast = useToast()
    const { showModal } = useModal()
    const queryCache = useQueryClient()
    const { mutate } = useMutation(deleteMovie, {
        onMutate: () => toast.loading('Deleting...'),
        onSuccess: () => toast.success('Movie deleted'),
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['movies']),
    })

    const remove = () => mutate(movie.id)

    return (
        <>
            <div className={style.bodycell}>
                <span>{movie.id}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{movie.title}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{movie.synopsis}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{movie.poster}</span>
            </div>
            <div className={style.bodycell}>
                <span>{movie.price}</span>
            </div>
            <div className={style.bodycell}>
                <span>{movie.year}</span>
            </div>
            <div className={style.bodycell}>
                <span>{movie.runtime}</span>
            </div>
            <div className={style.bodycell}>
                <span>{movie.rating}</span>
            </div>
            <div className={style.bodycell}>
                <span>{movie.language.name}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{movie.countries.map(o => o.name).join()}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{movie.genres.map(o => o.name).join()}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{movie.directors.map(o => o.name).join()}</span>
            </div>
            <div className={style.bodycell}>
                <button onClick={() => showModal('MovieFormModal', { movie })}>
                    <Edit height={14} />
                </button>
                <button onClick={remove}>
                    <Cross height={14} />
                </button>
            </div>
        </>
    )
}
