import { useQueryClient, useMutation } from 'react-query'
import { useModal } from '@/providers/ModalProvider'
import { useToast } from '@/providers/ToastProvider'
import { deleteDirector } from '@/services/directorService'
import { Edit, Cross } from '@/assets/icons/Misc'
import type { Director } from '@/types/Director'
import style from './minimalTable.module.scss'

export const DirectorsTableRow: React.FC<{ director: Director }> = ({ director }) => {
    const toast = useToast()
    const { showModal } = useModal()
    const queryCache = useQueryClient()
    const { mutate } = useMutation(deleteDirector, {
        onMutate: () => toast.loading('Deleting...'),
        onSuccess: () => toast.success('Director deleted'),
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['directors']),
    })

    const remove = () => mutate(director.id)

    return (
        <>
            <div className={style.bodycell}>
                <span>{director.id}</span>
            </div>
            <div className={style.bodycell}>
                <span>{director.name}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{director.created_at.toLocaleString()}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{director.updated_at.toLocaleString()}</span>
            </div>
            <div className={style.bodycell}>
                <button onClick={() => showModal('DirectorFormModal', { director })}>
                    <Edit height={14} />
                </button>
                <button onClick={remove}>
                    <Cross height={14} />
                </button>
            </div>
        </>
    )
}
