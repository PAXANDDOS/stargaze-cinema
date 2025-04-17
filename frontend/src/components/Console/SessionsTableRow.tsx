import { useQueryClient, useMutation } from 'react-query'
import { useModal } from '@/providers/ModalProvider'
import { useToast } from '@/providers/ToastProvider'
import { deleteSession } from '@/services/sessionService'
import { Edit, Cross } from '@/assets/icons/Misc'
import type { Session } from '@/types/Session'
import style from './sessionsTable.module.scss'

export const SessionsTableRow: React.FC<{ session: Session }> = ({ session }) => {
    const toast = useToast()
    const { showModal } = useModal()
    const queryCache = useQueryClient()
    const { mutate } = useMutation(deleteSession, {
        onMutate: () => toast.loading('Deleting...'),
        onSuccess: () => toast.success('Session deleted'),
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['sessions']),
    })

    const remove = () => mutate(session.id)

    return (
        <>
            <div className={style.bodycell}>
                <span>{session.id}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{session.movie.title}</span>
            </div>
            <div className={style.bodycell}>
                <span>{session.hall.name}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{session.begin_at.toLocaleString()}</span>
            </div>
            <div className={style.bodycell} x-name="long">
                <span>{session.end_at.toLocaleString()}</span>
            </div>
            <div className={style.bodycell}>
                <button onClick={() => showModal('SessionFormModal', { session })}>
                    <Edit height={14} />
                </button>
                <button onClick={remove}>
                    <Cross height={14} />
                </button>
            </div>
        </>
    )
}
