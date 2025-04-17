import { useState } from 'react'
import { useToast } from '@/providers/ToastProvider'
import { useQueryClient, useMutation } from 'react-query'
import { createGenre, updateGenre } from '@/services/genreService'
import { LabeledInput } from '../Inputs/LabeledInput'
import { InputSubmit } from '../Inputs/InputSubmit'
import { Modal } from './Modal'
import type { Genre } from '@/types/Genre'
import style from './formModal.module.scss'

interface Props {
    genre?: Genre
    onClose: () => void
}

export const GenreFormModal: React.FC<Props> = ({ genre, onClose }) => {
    const toast = useToast()
    const queryCache = useQueryClient()
    const createMutation = useMutation(createGenre, {
        onMutate: () => toast.loading('Creating...'),
        onSuccess: () => {
            toast.success('Genre created')
            onClose()
        },
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['genres']),
    })
    const updateMutation = useMutation(updateGenre, {
        onMutate: () => toast.loading('Updating...'),
        onSuccess: () => {
            toast.success('Genre updated')
            onClose()
        },
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['genres']),
    })

    const [state, setState] = useState({
        name: genre?.name || '',
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

        if (!genre) return

        const data = JSON.parse(JSON.stringify(state))
        if (state.name === genre.name) delete data.name

        updateMutation.mutate({ id: genre.id, data })
    }

    return (
        <Modal title={!genre ? 'Create genre' : 'Update genre'} isOpen={true} onClose={onClose}>
            <div className={style.formModal}>
                <form onSubmit={!genre ? handleCreate : handleUpdate}>
                    <LabeledInput name="name" value={state.name} onChange={handleChange} required />
                    <InputSubmit label={!genre ? 'Create genre' : 'Save changes'} />
                </form>
            </div>
        </Modal>
    )
}

GenreFormModal.defaultProps = {
    genre: undefined,
}
