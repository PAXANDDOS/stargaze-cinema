import { useQuery } from 'react-query'
import { useMatch } from '@tanstack/react-location'
import { MovieDescription } from '@/components/Movie/MovieDescription'
import { MovieSelector } from '@/components/Movie/MovieSelector'
import { MovieFramer } from '@/components/Movie/MovieFramer'
import { MoviePoster } from '@/components/Movie/MoviePoster'
import { getMovie } from '@/services/movieService'
import style from './movie.module.scss'

export const MoviePage: React.FC = () => {
    const { params } = useMatch()
    const { data } = useQuery([params.movie], () => getMovie(params.movie))

    return (
        <div className={style.moviePage}>
            {!data ? (
                'Loading movie data ...'
            ) : (
                <>
                    <MoviePoster title={data.title} poster={data.poster} year={data.year} />
                    <div className={style.movieBody}>
                        <MovieSelector movie={data} />
                        <MovieFramer frames={data.frames} />
                        <MovieDescription movie={data} />
                    </div>
                </>
            )}
        </div>
    )
}
