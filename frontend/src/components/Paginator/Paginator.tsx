import { Arrow } from '@/assets/icons/Misc'
import React, { useMemo } from 'react'
import style from './paginator.module.scss'

interface Props {
    paginator: {
        currentPage: number
        perPage: number
        totalItems: number
        totalPages: number
        nextPage: number | null
        prevPage: number | null
    }
    setPage: React.Dispatch<React.SetStateAction<number>>
}

export const Paginator: React.FC<Props> = ({ paginator, setPage }) => {
    const pages = useMemo(() => {
        const arr: JSX.Element[] = []
        for (let i = 1; i <= paginator.totalPages; i++) {
            if (i == paginator.currentPage) {
                arr.push(<span key={i}>{paginator.currentPage}</span>)
                continue
            }
            if (i >= 10) {
                arr.push(<span key={i}>...</span>)
                break
            }
            arr.push(
                <button key={i} onClick={() => setPage(i)}>
                    {i}
                </button>
            )
        }
        return arr
    }, [paginator.currentPage, paginator.totalPages, setPage])

    return (
        <div className={style.paginator}>
            <button disabled={!paginator.prevPage} onClick={() => setPage(paginator.prevPage!)}>
                <Arrow width={10} />
            </button>
            {pages}
            <button disabled={!paginator.nextPage} onClick={() => setPage(paginator.nextPage!)}>
                <Arrow width={10} style={{ transform: 'rotateY(180deg)' }} />
            </button>
        </div>
    )
}
