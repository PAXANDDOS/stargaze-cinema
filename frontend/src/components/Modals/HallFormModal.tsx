import { useState } from 'react'
import { useToast } from '@/providers/ToastProvider'
import { useQueryClient, useMutation } from 'react-query'
import { createHall, updateHall } from '@/services/hallService'
import { LabeledInput } from '../Inputs/LabeledInput'
import { LabeledSelect } from '../Inputs/LabeledSelect'
import { InputSubmit } from '../Inputs/InputSubmit'
import { Modal } from './Modal'
import type { Hall } from '@/types/Hall'
import style from './formModal.module.scss'

interface Props {
    hall?: Hall
    onClose: () => void
}

export const HallFormModal: React.FC<Props> = ({ hall, onClose }) => {
    const toast = useToast()
    const queryCache = useQueryClient()
    const createMutation = useMutation(createHall, {
        onMutate: () => toast.loading('Creating...'),
        onSuccess: () => {
            toast.success('Hall created')
            onClose()
        },
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['halls']),
    })
    const updateMutation = useMutation(updateHall, {
        onMutate: () => toast.loading('Updating...'),
        onSuccess: () => {
            toast.success('Hall updated')
            onClose()
        },
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['halls']),
    })

    const [state, setState] = useState({
        name: hall?.name || '',
        capacity: hall?.capacity || '',
        type: hall?.type || '',
    })

    const handleChange = ({ target }: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        let value

        if (target.name === 'capacity') value = +target.value
        else value = target.value

        setState({
            ...state,
            [target.name]: value,
        })
    }

    const handleCreate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()
        createMutation.mutate(state)
    }

    const handleUpdate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()

        if (!hall) return

        const data = JSON.parse(JSON.stringify(state))
        for (const key in data) {
            // @ts-ignore
            if (state[key] === hall[key]) {
                delete data[key]
                continue
            }
        }

        updateMutation.mutate({ id: hall.id, data })
    }

    return (
        <Modal title={!hall ? 'Create hall' : 'Update hall'} isOpen={true} onClose={onClose}>
            <div className={style.formModal}>
                <form onSubmit={!hall ? handleCreate : handleUpdate}>
                    <LabeledInput name="name" value={state.name} onChange={handleChange} required />
                    <LabeledInput
                        name="capacity"
                        type="number"
                        value={state.capacity}
                        onChange={handleChange}
                        required
                    />
                    <LabeledSelect name="type" value={state.type} onChange={handleChange} required>
                        <option>2D</option>
                        <option>3D</option>
                        <option>4DX</option>
                        <option>5D</option>
                    </LabeledSelect>
                    <InputSubmit label={!hall ? 'Create hall' : 'Save changes'} />
                </form>
            </div>
        </Modal>
    )
}

HallFormModal.defaultProps = {
    hall: undefined,
}
