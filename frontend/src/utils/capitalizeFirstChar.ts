interface Capitalizer {
    (chars: string | string[], locale?: string): string
}

export const capitalizeFirstLetter: Capitalizer = ([first, ...rest], locale = navigator.language) =>
    first.toLocaleUpperCase(locale) + rest.join('')
