import { capitalizeFirstLetter } from '@/utils/capitalizeFirstChar'
import style from './labeledInput.module.scss'

interface Props {
    label?: string
    name: string
    value: string
    onChange: React.ChangeEventHandler<HTMLInputElement>
    required?: boolean
    min?: string | number
    max?: string | number
}

export const LabeledDateTime: React.FC<Props> = props => (
    <label className={style.labeledInput}>
        {props.label || capitalizeFirstLetter(props.name)}
        <input
            type="datetime-local"
            value={props.value}
            min={props.min}
            max={props.max}
            name={props.name}
            onChange={props.onChange}
            required={props.required}
        />
    </label>
)

LabeledDateTime.defaultProps = {
    label: undefined,
    min: '2022-04-29',
    max: '9999-12-31',
    required: false,
}
