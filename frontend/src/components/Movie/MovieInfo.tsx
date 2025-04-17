import { useMemo } from 'react'
import type { Entity } from '@/types/Entity'
import style from './movieInfo.module.scss'

interface Props {
    title: string
    content: string | number | Entity[]
}

export const MovieInfo: React.FC<Props> = ({ title, content }) => {
    const parsedContent = useMemo(() => {
        if (Array.isArray(content)) {
            if (content.length > 1) return content.map(item => item.name).join(', ')
            return content[0].name
        }
        return content
    }, [content])

    return (
        <div className={style.movieInfoBlock}>
            <h3 className={style.movieInfoTitle}>{title}</h3>
            <span className={style.movieInfoContent}>{parsedContent}</span>
        </div>
    )
}
