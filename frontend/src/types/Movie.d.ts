import { Session } from './Session'
import { Genre } from './Genre'
import { Frame } from './Frame'
import { Director } from './Director'
import { Entity } from './Entity'
import { Language } from './Language'
import { Country } from './Country'

export interface Movie extends Entity {
    title: string
    synopsis?: string
    poster?: string
    year: number
    price: number
    runtime: number
    rating: 'PEGI 3' | 'PEGI 7' | 'PEGI 12' | 'PEGI 16' | 'PEGI 18'
    language: Language
    countries: Country[]
    genres: Genre[]
    directors: Director[]
    sessions: Session[]
    frames: Frame[]
}

export type UpdateMovie = {
    title?: string
    synopsis?: string
    poster?: string
    year?: number
    price?: number
    runtime?: number
    rating?: 'PEGI 3' | 'PEGI 7' | 'PEGI 12' | 'PEGI 16' | 'PEGI 18'
    language_id?: number
    countries_ids?: number[]
    genre_ids?: number[]
    director_ids?: number[]
}

export interface PaginatedMovies {
    paginator: {
        currentPage: number
        perPage: number
        totalPages: number
        totalItems: number
        nextPage: number
        prevPage: number
    }
    data: Movie[]
}
