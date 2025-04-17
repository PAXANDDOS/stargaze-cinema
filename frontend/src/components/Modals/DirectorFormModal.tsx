import { useState } from 'react'
import { useToast } from '@/providers/ToastProvider'
import { useQueryClient, useMutation } from 'react-query'
import { createDirector, updateDirector } from '@/services/directorService'
import { LabeledInput } from '../Inputs/LabeledInput'
import { InputSubmit } from '../Inputs/InputSubmit'
import { Modal } from './Modal'
import type { Director } from '@/types/Director'
import style from './formModal.module.scss'

interface Props {
    director?: Director
    onClose: () => void
}

export const DirectorFormModal: React.FC<Props> = ({ director, onClose }) => {
    const toast = useToast()
    const queryCache = useQueryClient()
    const createMutation = useMutation(createDirector, {
        onMutate: () => toast.loading('Creating...'),
        onSuccess: () => {
            toast.success('Director created')
            onClose()
        },
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['directors']),
    })
    const updateMutation = useMutation(updateDirector, {
        onMutate: () => toast.loading('Updating...'),
        onSuccess: () => {
            toast.success('Director updated')
            onClose()
        },
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['directors']),
    })

    const [state, setState] = useState({
        name: director?.name || '',
    })

    const handleChange = ({ target }: React.ChangeEvent<HTMLInputElement>) =>
        setState({
            ...state,
            [target.name]: target.value,
        })

    const handleCreate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()
        createMutation.mutate(state)
    }

    const handleUpdate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()

        if (!director) return

        const data = JSON.parse(JSON.stringify(state))
        if (state.name === director.name) delete data.name

        updateMutation.mutate({ id: director.id, data })
    }

    return (
        <Modal
            title={!director ? 'Create director' : 'Update director'}
            isOpen={true}
            onClose={onClose}
        >
            <div className={style.formModal}>
                <form onSubmit={!director ? handleCreate : handleUpdate}>
                    <LabeledInput name="name" value={state.name} onChange={handleChange} required />
                    <InputSubmit label={!director ? 'Create director' : 'Save changes'} />
                </form>
            </div>
        </Modal>
    )
}

DirectorFormModal.defaultProps = {
    director: undefined,
}
