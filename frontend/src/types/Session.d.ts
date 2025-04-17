import { Entity } from './Entity'
import { Hall } from './Hall'
import { Movie } from './Movie'

export interface Session extends Entity {
    begin_at: Date
    end_at: Date
    movie: Movie
    hall: Hall
}

export type UpdateSession = {
    begin_at: string
    end_at: string
    movie_id: number
    hall_id: number
}
