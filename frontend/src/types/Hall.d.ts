import { Entity } from './Entity'

export interface Hall extends Entity {
    name: string
    capacity: number
    type: '2D' | '3D' | '4DX' | '5D'
}

export type UpdateHall = {
    name?: string
    capacity?: number
    type?: '2D' | '3D' | '4DX' | '5D'
}
