import { Entity } from './Entity'
import { Session } from './Session'
import { User } from './User'

export interface Ticket extends Entity {
    place: number
    user: User
    session: Session
}

export type UpdateTicket = {
    place?: number
    user_id?: number
    session_id?: number
}
