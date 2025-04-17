import { PlusCircle } from '@/assets/icons/Misc'
import style from './createRecordBtn.module.scss'

export const CreateRecordBtn: React.FC<{ onClick: () => void }> = ({ onClick }) => (
    <button className={style.createRecord} type="button" onClick={onClick}>
        <PlusCircle width={22} fill="white" />
        Create record
    </button>
)
