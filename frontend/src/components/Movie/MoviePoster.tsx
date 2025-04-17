import style from './moviePoster.module.scss'

interface Props {
    title: string
    poster?: string
    year: number
}

export const MoviePoster: React.FC<Props> = ({ poster, title, year }) => (
    <div className={style.posterBanner}>
        <img className={style.posterImage} src={poster} alt="poster" />
        <h1 className={style.posterContainer}>
            {title} ({year})
        </h1>
    </div>
)
