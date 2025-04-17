import { Entity } from './Entity'
import { Movie } from './Movie'

export interface Director extends Entity {
    name: string
    movies: Movie[]
}

export type UpdateDirector = {
    name: string
}
