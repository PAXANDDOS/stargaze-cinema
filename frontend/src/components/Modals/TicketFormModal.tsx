import { useState } from 'react'
import { useToast } from '@/providers/ToastProvider'
import { useQueryClient, useMutation } from 'react-query'
import { createTicket, updateTicket } from '@/services/ticketService'
import { LabeledInput } from '../Inputs/LabeledInput'
import { InputSubmit } from '../Inputs/InputSubmit'
import { Modal } from './Modal'
import type { Ticket } from '@/types/Ticket'
import style from './formModal.module.scss'

interface Props {
    ticket?: Ticket
    onClose: () => void
}

export const TicketFormModal: React.FC<Props> = ({ ticket, onClose }) => {
    const toast = useToast()
    const queryCache = useQueryClient()
    const createMutation = useMutation(createTicket, {
        onMutate: () => toast.loading('Creating...'),
        onSuccess: () => {
            toast.success('Ticket created')
            onClose()
        },
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['tickets']),
    })
    const updateMutation = useMutation(updateTicket, {
        onMutate: () => toast.loading('Updating...'),
        onSuccess: () => {
            toast.success('Ticket updated')
            onClose()
        },
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['tickets']),
    })

    const [state, setState] = useState({
        user_id: ticket?.user.id || '',
        session_id: ticket?.session.id || '',
        place: ticket?.place || 1,
    })

    const handleChange = ({ target }: React.ChangeEvent<HTMLInputElement>) =>
        setState({
            ...state,
            [target.name]: +target.value,
        })

    const handleCreate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()
        createMutation.mutate(state)
    }

    const handleUpdate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()

        if (!ticket) return

        const data = JSON.parse(JSON.stringify(state))
        for (const key in data) {
            // @ts-ignore
            if (state[key] === ticket[key]) {
                delete data[key]
                continue
            }
        }

        updateMutation.mutate({ id: ticket.id, data })
    }

    return (
        <Modal title={!ticket ? 'Create ticket' : 'Update ticket'} isOpen={true} onClose={onClose}>
            <div className={style.formModal}>
                <form onSubmit={!ticket ? handleCreate : handleUpdate}>
                    <LabeledInput
                        label="User ID"
                        name="user_id"
                        type="number"
                        value={state.user_id}
                        onChange={handleChange}
                        required
                    />
                    <LabeledInput
                        label="Session ID"
                        name="session_id"
                        type="number"
                        value={state.session_id}
                        onChange={handleChange}
                        required
                    />
                    <LabeledInput
                        name="place"
                        type="number"
                        value={state.place}
                        onChange={handleChange}
                        required
                    />
                    <InputSubmit label={!ticket ? 'Create ticket' : 'Save changes'} />
                </form>
            </div>
        </Modal>
    )
}

TicketFormModal.defaultProps = {
    ticket: undefined,
}
