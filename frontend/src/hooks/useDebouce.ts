import { useEffect, useState } from 'react'

type T = string
interface F {
    (value: T, delay: number): T
}

export const useDebounce: F = (value, delay) => {
    const [debouncedValue, setDebouncedValue] = useState<T>(value)

    useEffect(() => {
        const handler = setTimeout(() => {
            setDebouncedValue(value)
        }, delay)

        return () => {
            clearTimeout(handler)
        }
    }, [value, delay])

    return debouncedValue
}
