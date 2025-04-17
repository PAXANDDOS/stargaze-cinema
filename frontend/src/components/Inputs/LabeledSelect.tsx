import { capitalizeFirstLetter } from '@/utils/capitalizeFirstChar'
import style from './labeledSelect.module.scss'

interface Props {
    label?: string
    name: string
    value: string | number | string[]
    onChange: React.ChangeEventHandler<HTMLSelectElement>
    required?: boolean
    multiple?: boolean
    children: React.ReactNode
}

export const LabeledSelect: React.FC<Props> = props => (
    <label className={style.labeledSelect}>
        {props.label || capitalizeFirstLetter(props.name)}
        <select
            name={props.name}
            value={props.value}
            onChange={props.onChange}
            required={props.required}
            multiple={props.multiple}
        >
            {!props.multiple && (
                <option disabled value="">
                    -- select {props.name} --
                </option>
            )}
            {props.children}
        </select>
    </label>
)

LabeledSelect.defaultProps = {
    label: undefined,
    required: false,
    multiple: false,
}
