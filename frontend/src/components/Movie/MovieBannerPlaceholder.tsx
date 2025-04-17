import { useMemo } from 'react'
import style from './movieBanner.module.scss'

interface Props {
    items: number
}

export const MovieBannerPlaceholder: React.FC<Props> = ({ items }) => {
    const props = useMemo(() => {
        const a = []
        for (let i = 0; i < items; i++)
            a.push(<div className={`${style.banner} ${style.pulseAnimation}`} key={i} />)
        return a
    }, [items])

    return <>{props}</>
}
