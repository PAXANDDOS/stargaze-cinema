import { Entity } from './Entity'
import { Movie } from './Movie'

export interface Genre extends Entity {
    name: string
    movies: Movie[]
}

export type UpdateGenre = {
    name: string
}
