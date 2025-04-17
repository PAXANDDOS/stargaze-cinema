import style from './inputSubmit.module.scss'

interface Props {
    label?: string
}

export const InputSubmit: React.FC<Props> = ({ label }) => (
    <input className={style.inputSumbit} type="submit" value={label} />
)

InputSubmit.defaultProps = {
    label: 'Submit',
}
