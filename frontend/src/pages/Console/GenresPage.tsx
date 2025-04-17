import { useQuery } from 'react-query'
import { useModal } from '@/providers/ModalProvider'
import { GenresTable } from '@/components/Console/GenresTable'
import { CreateRecordBtn } from '@/components/Buttons/CreateRecordBtn'
import { getGenres } from '@/services/genreService'
import style from './console.module.scss'

export const GenresPage: React.FC = () => {
    const { showModal } = useModal()
    const query = useQuery(['genres'], getGenres)

    return (
        <div className={style.tablePage}>
            <div className={style.tableBtns}>
                <CreateRecordBtn onClick={() => showModal('GenreFormModal')} />
            </div>
            <GenresTable query={query} />
        </div>
    )
}
