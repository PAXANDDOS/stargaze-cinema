import { Entity } from './Entity'

export interface User extends Entity {
    name: string
    email: string
    roles: 'User' | 'Administartor' | 'Moderator'
    token: string
}
