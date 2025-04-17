export const parseMinutes = (min: number): string => {
    const hrs = min / 60
    const rhrs = Math.floor(hrs)
    const mins = Math.round((hrs - rhrs) * 60)
    let result = ''

    if (rhrs > 0) {
        result = result.concat(`${rhrs} hour(s)`)
    }

    if (rhrs > 0 && mins > 0) {
        result = result.concat(` and ${mins} minute(s)`)
    } else if (mins > 0) {
        result = result.concat(`${mins} minute(s)`)
    }

    return result
}
