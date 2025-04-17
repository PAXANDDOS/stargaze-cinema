import { useState } from 'react'
import { useQuery } from 'react-query'
import { Modal } from './Modal'
import { useToast } from '@/providers/ToastProvider'
import { useAuth } from '@/providers/AuthProvider'
import { getSession } from '@/services/sessionService'
import { parseMinutes } from '@/utils/parseMinutes'
import { axiosClient } from '@/utils/axiosClient'
import { Card } from '@/assets/icons/Misc'
import type { Session } from '@/types/Session'
import style from './orderModal.module.scss'

interface Props {
    session: Session
    poster: string
    onClose: () => void
}

export const OrderModal: React.FC<Props> = ({ session, poster, onClose }) => {
    const { user } = useAuth()
    const toast = useToast()
    const { data } = useQuery(['session', session.id], () => getSession(session.id))
    const [creds, setCreds] = useState({
        number: '',
        date: '',
        cvv: '',
    })
    const [selectedPlace, setSelectedPlace] = useState<number>()

    const handleOrder = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()
        for (const key in creds) {
            // @ts-ignore
            if (!creds[key]) return toast.error(`Card ${key} is missing`)
        }
        toast.promise({
            promise: axiosClient.post('/tickets', {
                place: selectedPlace,
                user_id: user?.id,
                session_id: data?.id,
            }),
            title: 'Ordering...',
            onSuccess: onClose,
        })
    }

    const handleChange = ({ target }: React.ChangeEvent<HTMLInputElement>) => {
        switch (target.name) {
            case 'number':
            case 'cvv':
                if (!/^\d+$/.test(target.value)) return
                break
            case 'date':
                if (!/^[/\d+]*$/.test(target.value)) return
        }

        setCreds({
            ...creds,
            [target.name]: target.name === 'date' ? target.value : +target.value,
        })
    }

    return (
        <Modal title="Book a ticket" isOpen={true} onClose={onClose}>
            {!data ? (
                <div className={style.orderModal}>
                    <div className={style.orderPreview}>
                        <img src={poster} alt="poster" />
                    </div>
                </div>
            ) : (
                <div className={style.orderModal}>
                    <div className={style.placeSelector}>
                        {new Array(data?.hall.capacity).fill(undefined).map((place, index) => {
                            return (
                                <div
                                    key={index}
                                    className={style.place}
                                    onClick={() => setSelectedPlace(index + 1)}
                                >
                                    {index + 1}
                                </div>
                            )
                        })}
                    </div>
                    {!!selectedPlace && selectedPlace > 0 ? (
                        <form className={style.orderForm} onSubmit={handleOrder}>
                            <div className={style.amountInfoBlock}>
                                <div className={style.amountInfoLine}>
                                    <span>Ticket for the {data.movie.title}</span>
                                    <div>
                                        <span>Seat #</span>
                                        <span>{selectedPlace}</span>
                                    </div>
                                </div>
                                <div className={style.amountInfoLine}>
                                    <span>Hall</span>
                                    <div>
                                        <span>
                                            {data.hall.name} {data?.hall.type}
                                        </span>
                                    </div>
                                </div>
                                <div className={style.amountInfoLine}>
                                    <span>Runtime</span>
                                    <div>
                                        <span>{parseMinutes(data.movie.runtime)}</span>
                                    </div>
                                </div>
                                <div className={style.amountInfoLine}>
                                    <span>Begins at</span>
                                    <div>
                                        <span>
                                            {session.begin_at.toLocaleString([], {
                                                weekday: 'short',
                                                day: '2-digit',
                                                month: 'long',
                                                hour: '2-digit',
                                                minute: '2-digit',
                                            })}
                                        </span>
                                    </div>
                                </div>
                                <div className={style.amountInfoLine}>
                                    <span>Total to pay:</span>
                                    <div>
                                        <span>{data.movie.price}</span>
                                        <span>&nbsp;USD</span>
                                    </div>
                                </div>
                            </div>
                            <div className={style.cardHead}>
                                <Card />
                                <span>Card</span>
                            </div>
                            <div className={style.cardForm}>
                                <div className={style.cardInput}>
                                    <span>Card number</span>
                                    <input
                                        autoComplete="cc-number"
                                        placeholder="••••&nbsp;••••&nbsp;••••&nbsp;••••"
                                        type="tel"
                                        minLength={16}
                                        maxLength={16}
                                        name="number"
                                        value={creds.number}
                                        onChange={handleChange}
                                    />
                                    <hr />
                                </div>
                                <div>
                                    <div className={style.cardInput}>
                                        <span>Expiration date</span>
                                        <input
                                            placeholder="MM/YY"
                                            type="tel"
                                            minLength={5}
                                            maxLength={5}
                                            autoComplete="cc-exp"
                                            name="date"
                                            value={creds.date}
                                            onChange={handleChange}
                                        />
                                        <hr />
                                    </div>
                                    <div className={style.cardInput} style={{ marginLeft: '18px' }}>
                                        <span>CVV2</span>
                                        <input
                                            placeholder="•••"
                                            minLength={3}
                                            maxLength={3}
                                            type="password"
                                            autoComplete="cc-csc"
                                            name="cvv"
                                            value={creds.cvv}
                                            onChange={handleChange}
                                        />
                                        <hr />
                                    </div>
                                </div>
                            </div>
                            <input
                                type="submit"
                                value="Order a ticket"
                                className={style.cardSubmit}
                            />
                        </form>
                    ) : (
                        <h4 className={style.orderSeatPreview}>Select a seat to proceed</h4>
                    )}
                </div>
            )}
        </Modal>
    )
}
