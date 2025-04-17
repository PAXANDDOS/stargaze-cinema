import { Entity } from './Entity'
import { Movie } from './Movie'

export interface Country extends Entity {
    name: string
    movies: Movie[]
}
