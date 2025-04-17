import { useMemo } from 'react'

interface Props {
    cols: number
    rows: number
}

export const TablePlaceholder: React.FC<Props> = ({ cols, rows }) => {
    const items = useMemo(() => {
        const a = []
        for (let i = 0; i < rows; i++)
            for (let j = 0; j < cols; j++)
                a.push(
                    <span
                        style={{
                            width: '100%',
                            height: 40,
                            background: '#e4e4e4',
                        }}
                        key={`${i}-${j}`}
                    />
                )
        return a
    }, [cols, rows])

    return <>{items}</>
}
