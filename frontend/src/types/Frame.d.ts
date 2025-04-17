import { Entity } from './Entity'
import { Movie } from './Movie'

export interface Frame extends Entity {
    image: string
    movie: Movie
}
