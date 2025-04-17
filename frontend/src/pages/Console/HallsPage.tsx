import { useQuery } from 'react-query'
import { useModal } from '@/providers/ModalProvider'
import { HallsTable } from '@/components/Console/HallsTable'
import { CreateRecordBtn } from '@/components/Buttons/CreateRecordBtn'
import { getHalls } from '@/services/hallService'
import style from './console.module.scss'

export const HallsPage: React.FC = () => {
    const { showModal } = useModal()
    const query = useQuery(['halls'], getHalls)

    return (
        <div className={style.tablePage}>
            <div className={style.tableBtns}>
                <CreateRecordBtn onClick={() => showModal('HallFormModal')} />
            </div>
            <HallsTable query={query} />
        </div>
    )
}
