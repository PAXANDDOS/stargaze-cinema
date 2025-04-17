import { useQueryClient, useMutation } from 'react-query'
import { useModal } from '@/providers/ModalProvider'
import { useToast } from '@/providers/ToastProvider'
import { deleteGenre } from '@/services/genreService'
import { Edit, Cross } from '@/assets/icons/Misc'
import type { Genre } from '@/types/Genre'
import style from './minimalTable.module.scss'

export const GenresTableRow: React.FC<{ genre: Genre }> = ({ genre }) => {
    const toast = useToast()
    const { showModal } = useModal()
    const queryCache = useQueryClient()
    const { mutate } = useMutation(deleteGenre, {
        onMutate: () => toast.loading('Deleting...'),
        onSuccess: () => toast.success('Genre deleted'),
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['genres']),
    })

    const remove = () => mutate(genre.id)

    return (
        <>
            <div className={style.bodycell}>
                <span>{genre.id}</span>
            </div>
            <div className={style.bodycell}>
                <span>{genre.name}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{genre.created_at.toLocaleString()}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{genre.updated_at.toLocaleString()}</span>
            </div>
            <div className={style.bodycell}>
                <button onClick={() => showModal('GenreFormModal', { genre })}>
                    <Edit height={14} />
                </button>
                <button onClick={remove}>
                    <Cross height={14} />
                </button>
            </div>
        </>
    )
}
