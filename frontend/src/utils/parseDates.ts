import type { Entity } from '@/types/Entity'

interface Parser {
    (data: Entity | Entity[]): Entity | Entity[]
}

const isEntity = (key: any) => !!key && Object.prototype.hasOwnProperty.call(key, 'id')
const isDate = (key: any) => typeof key === 'string' && key.charAt(10) === 'T' && !!Date.parse(key)

const parseInObject = (entity: any) => {
    Object.keys(entity).forEach(key => {
        if (isDate(entity[key])) {
            entity[key] = new Date(entity[key])
        } else if (isEntity(entity[key])) {
            entity[key] = parseInObject(entity[key])
        } else if (Array.isArray(entity[key])) {
            entity[key] = parseInArray(entity[key])
        }
    })
    return entity
}

const parseInArray = (arr: Entity[]) =>
    arr.length > 0 ? arr.map(item => parseInObject(item)) : arr

export const parseDates: Parser = data =>
    Array.isArray(data) ? parseInArray(data) : parseInObject(data)
