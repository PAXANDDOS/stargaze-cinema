import { MovieInfo } from '@/components/Movie/MovieInfo'
import { parseMinutes } from '@/utils/parseMinutes'
import type { Movie } from '@/types/Movie'
import style from './movieDescription.module.scss'

interface Props {
    movie: Movie
}

export const MovieDescription: React.FC<Props> = ({ movie }) => (
    <div className={style.movieDesc}>
        <div className={style.movieDescContent}>
            <h2>{movie.title}</h2>
            <p>{movie.synopsis}</p>
        </div>
        <div className={style.movieDescInfo}>
            <MovieInfo title="Year" content={movie.year} />
            <MovieInfo title="Country" content={movie.countries} />
            <MovieInfo title="Language" content={movie.language.name} />
            <MovieInfo title="Genre" content={movie.genres} />
            <MovieInfo title="Director" content={movie.directors} />
            <MovieInfo title="Average ticket price" content={`$${movie.price}`} />
            <MovieInfo title="Runtime" content={parseMinutes(movie.runtime)} />
            <MovieInfo title="Rating" content={movie.rating} />
        </div>
    </div>
)
