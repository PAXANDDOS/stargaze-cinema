import { useQuery } from 'react-query'
import { useModal } from '@/providers/ModalProvider'
import { SessionsTable } from '@/components/Console/SessionsTable'
import { CreateRecordBtn } from '@/components/Buttons/CreateRecordBtn'
import { getSessions } from '@/services/sessionService'
import style from './console.module.scss'

export const SessionsPage: React.FC = () => {
    const { showModal } = useModal()
    const query = useQuery(['sessions'], getSessions)

    return (
        <div className={style.tablePage}>
            <div className={style.tableBtns}>
                <CreateRecordBtn onClick={() => showModal('SessionFormModal')} />
            </div>
            <SessionsTable query={query} />
        </div>
    )
}
