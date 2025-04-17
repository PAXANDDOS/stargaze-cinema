import { useState } from 'react'
import { useToast } from '@/providers/ToastProvider'
import { useQueryClient, useMutation } from 'react-query'
import { createSession, updateSession } from '@/services/sessionService'
import { LabeledInput } from '../Inputs/LabeledInput'
import { LabeledDateTime } from '../Inputs/LabeledDateTime'
import { InputSubmit } from '../Inputs/InputSubmit'
import { Modal } from './Modal'
import type { Session } from '@/types/Session'
import style from './formModal.module.scss'

interface Props {
    session?: Session
    onClose: () => void
}

export const SessionFormModal: React.FC<Props> = ({ session, onClose }) => {
    const toast = useToast()
    const queryCache = useQueryClient()
    const createMutation = useMutation(createSession, {
        onMutate: () => toast.loading('Creating...'),
        onSuccess: () => {
            toast.success('Session created')
            onClose()
        },
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['sessions']),
    })
    const updateMutation = useMutation(updateSession, {
        onMutate: () => toast.loading('Updating...'),
        onSuccess: () => {
            toast.success('Session updated')
            onClose()
        },
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['sessions']),
    })

    const [state, setState] = useState({
        movie_id: session?.movie.id || '',
        hall_id: session?.hall.id || '',
        begin_at: session?.begin_at || new Date(),
        end_at: session?.end_at || new Date(),
    })

    const handleChange = ({ target }: React.ChangeEvent<HTMLInputElement>) => {
        let value

        if (target.name === 'movie_id' || target.name === 'hall_id') value = +target.value
        else if (target.name === 'begin_at' || target.name === 'end_at')
            value = new Date(target.value)
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

        if (!session) return

        const data = JSON.parse(JSON.stringify(state))
        for (const key in data) {
            // @ts-ignore
            if (state[key] === session[key]) {
                delete data[key]
                continue
            }
        }

        updateMutation.mutate({ id: session.id, data })
    }

    return (
        <Modal
            title={!session ? 'Create session' : 'Update session'}
            isOpen={true}
            onClose={onClose}
        >
            <div className={style.formModal}>
                <form onSubmit={!session ? handleCreate : handleUpdate}>
                    <LabeledInput
                        label="Movie ID"
                        name="movie_id"
                        type="number"
                        value={state.movie_id}
                        onChange={handleChange}
                        required
                    />
                    <LabeledInput
                        label="Hall ID"
                        name="hall_id"
                        type="number"
                        value={state.hall_id}
                        onChange={handleChange}
                        required
                    />
                    <LabeledDateTime
                        label="Begins at"
                        name="begin_at"
                        value={state.begin_at.toISOString().split('Z')[0]}
                        onChange={handleChange}
                        required
                    />
                    <LabeledDateTime
                        label="Ends at"
                        name="end_at"
                        value={state.end_at.toISOString().split('Z')[0]}
                        onChange={handleChange}
                        required
                    />
                    <InputSubmit label={!session ? 'Create session' : 'Save changes'} />
                </form>
            </div>
        </Modal>
    )
}

SessionFormModal.defaultProps = {
    session: undefined,
}
