import { useQueryClient, useMutation } from 'react-query'
import { useModal } from '@/providers/ModalProvider'
import { useToast } from '@/providers/ToastProvider'
import { deleteHall } from '@/services/hallService'
import { Edit, Cross } from '@/assets/icons/Misc'
import type { Hall } from '@/types/Hall'
import style from './hallsTable.module.scss'

export const HallsTableRow: React.FC<{ hall: Hall }> = ({ hall }) => {
    const toast = useToast()
    const { showModal } = useModal()
    const queryCache = useQueryClient()
    const { mutate } = useMutation(deleteHall, {
        onMutate: () => toast.loading('Deleting...'),
        onSuccess: () => toast.success('Hall deleted'),
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['halls']),
    })

    const remove = () => mutate(hall.id)

    return (
        <>
            <div className={style.bodycell}>
                <span>{hall.id}</span>
            </div>
            <div className={style.bodycell}>
                <span>{hall.name}</span>
            </div>
            <div className={style.bodycell}>
                <span>{hall.capacity}</span>
            </div>
            <div className={style.bodycell}>
                <span>{hall.type}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{hall.created_at.toLocaleString()}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{hall.updated_at.toLocaleString()}</span>
            </div>
            <div className={style.bodycell}>
                <button onClick={() => showModal('HallFormModal', { hall })}>
                    <Edit height={14} />
                </button>
                <button onClick={remove}>
                    <Cross height={14} />
                </button>
            </div>
        </>
    )
}
