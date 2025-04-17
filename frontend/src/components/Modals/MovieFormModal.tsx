import { useState } from 'react'
import { useToast } from '@/providers/ToastProvider'
import { useQueryClient, useMutation, useQuery } from 'react-query'
import { createMovie, updateMovie } from '@/services/movieService'
import { getLanguages } from '@/services/languageService'
import { getCountries } from '@/services/countryService'
import { getGenres } from '@/services/genreService'
import { getDirectors } from '@/services/directorService'
import { LabeledInput } from '../Inputs/LabeledInput'
import { LabeledSelect } from '../Inputs/LabeledSelect'
import { LabeledTextarea } from '../Inputs/LabeledTextarea'
import { InputSubmit } from '../Inputs/InputSubmit'
import { Modal } from './Modal'
import type { Movie } from '@/types/Movie'
import style from './formModal.module.scss'

interface Props {
    movie?: Movie
    onClose: () => void
}

export const MovieFormModal: React.FC<Props> = ({ movie, onClose }) => {
    const toast = useToast()
    const queryCache = useQueryClient()
    const languagesQuery = useQuery(['languages'], getLanguages)
    const countriesQuery = useQuery(['countries'], getCountries)
    const genresQuery = useQuery(['genres'], getGenres)
    const directorsQuery = useQuery(['directors'], getDirectors)
    const createMutation = useMutation(createMovie, {
        onMutate: () => toast.loading('Creating...'),
        onSuccess: () => {
            toast.success('Movie created')
            onClose()
        },
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['movies']),
    })
    const updateMutation = useMutation(updateMovie, {
        onMutate: () => toast.loading('Updating...'),
        onSuccess: () => {
            toast.success('Movie updated')
            onClose()
        },
        onError: (err: any) => toast.error(err.response?.data.message),
        onSettled: () => queryCache.invalidateQueries(['movies']),
    })

    const [state, setState] = useState({
        title: movie?.title || '',
        synopsis: movie?.synopsis || '',
        poster: movie?.poster || null,
        price: movie?.price || 0,
        year: movie?.year || 0,
        runtime: movie?.runtime || 0,
        rating: movie?.rating || '',
        language: movie?.language.id || '',
        countries: movie?.countries ? movie.countries.map(a => `${a.id}`) : [''],
        genres: movie?.genres ? movie.genres.map(a => `${a.id}`) : [''],
        directors: movie?.directors ? movie.directors.map(a => `${a.id}`) : [''],
    })
    const [posterPreview, setPosterPreview] = useState<any>(movie?.poster)

    const handleChange = ({
        target,
        currentTarget,
    }: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
        let value
        switch (target.name) {
            case 'price':
            case 'year':
            case 'runtime':
                value = +target.value
                break
            case 'poster':
                const poster = currentTarget as HTMLInputElement
                if (poster.files) {
                    value = poster.files[0]
                    setPosterPreview(URL.createObjectURL(poster.files[0]))
                }
                break
            case 'countries':
            case 'genres':
            case 'directors':
                const select = currentTarget as HTMLSelectElement
                value = Array.from(select.selectedOptions, option => +option.value)
                break
            default:
                value = target.value
        }
        setState({
            ...state,
            [target.name]: value,
        })
    }

    const handleCreate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()
        const data = new FormData()
        for (const key in state) {
            switch (key) {
                case 'language':
                    data.append('language_id', state[key] as string)
                    break
                case 'countries':
                    data.append('country_ids', JSON.stringify(state[key]))
                    break
                case 'genres':
                    data.append('genre_ids', JSON.stringify(state[key]))
                    break
                case 'directors':
                    data.append('director_ids', JSON.stringify(state[key]))
                    break
                default:
                    // @ts-ignore
                    data.append(key, state[key])
            }
        }
        createMutation.mutate(data)
    }

    const handleUpdate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()
        if (!movie) return
        const data = JSON.parse(JSON.stringify(state))
        for (const key in data) {
            // @ts-ignore
            if (state[key] === movie[key]) {
                delete data[key]
                continue
            }
            if (key === 'language' && state[key] === movie[key].name) delete data[key]
        }
        updateMutation.mutate({ id: movie.id, data })
    }

    return (
        <Modal title={!movie ? 'Create movie' : 'Update movie'} isOpen={true} onClose={onClose}>
            <div className={style.formModal}>
                <form onSubmit={movie ? handleUpdate : handleCreate}>
                    <LabeledInput
                        name="title"
                        value={state.title}
                        onChange={handleChange}
                        required
                    />
                    <LabeledTextarea
                        name="synopsis"
                        value={state.synopsis}
                        onChange={handleChange}
                    />
                    <LabeledInput
                        label="Runtime (in minutes)"
                        name="runtime"
                        type="number"
                        value={state.runtime}
                        onChange={handleChange}
                        required
                    />
                    <LabeledInput
                        name="price"
                        type="number"
                        value={state.price}
                        onChange={handleChange}
                        min={0}
                        max={99.99}
                        step={0.01}
                        required
                    />
                    <LabeledInput
                        label="Year"
                        type="number"
                        name="year"
                        value={state.year}
                        onChange={handleChange}
                        min={1888}
                        required
                    />
                    <LabeledSelect
                        name="rating"
                        value={state.rating}
                        onChange={handleChange}
                        required
                    >
                        <option>PEGI 3</option>
                        <option>PEGI 7</option>
                        <option>PEGI 12</option>
                        <option>PEGI 16</option>
                        <option>PEGI 18</option>
                    </LabeledSelect>
                    <LabeledSelect
                        name="language"
                        value={state.language}
                        onChange={handleChange}
                        required
                    >
                        {languagesQuery.status === 'success' &&
                            languagesQuery.data?.map(language => (
                                <option key={language.id} value={language.id}>
                                    {language.name}
                                </option>
                            ))}
                    </LabeledSelect>
                    <LabeledSelect
                        name="countries"
                        value={state.countries}
                        onChange={handleChange}
                        required
                        multiple
                    >
                        {countriesQuery.status === 'success' &&
                            countriesQuery.data?.map(country => (
                                <option key={country.id} value={country.id}>
                                    {country.name}
                                </option>
                            ))}
                        <option>John Fox</option>
                        <option>Evan You</option>
                    </LabeledSelect>
                    <LabeledSelect
                        name="genres"
                        value={state.genres}
                        onChange={handleChange}
                        required
                        multiple
                    >
                        {genresQuery.status === 'success' &&
                            genresQuery.data?.map(genre => (
                                <option key={genre.id} value={genre.id}>
                                    {genre.name}
                                </option>
                            ))}
                    </LabeledSelect>
                    <LabeledSelect
                        name="directors"
                        value={state.directors}
                        onChange={handleChange}
                        required
                        multiple
                    >
                        {directorsQuery.status === 'success' &&
                            directorsQuery.data?.map(director => (
                                <option key={director.id} value={director.id}>
                                    {director.name}
                                </option>
                            ))}
                    </LabeledSelect>
                    <div className={style.formLabel}>
                        Poster
                        <div className={style.formPoster}>
                            <label htmlFor="poster">Upload a poster</label>
                            <input
                                id="poster"
                                name="poster"
                                onChange={handleChange}
                                type="file"
                                accept="image/png, image/jpg, image/jpeg"
                            />
                            {posterPreview && <img alt="posterPreview" src={posterPreview} />}
                        </div>
                    </div>
                    <InputSubmit label={!movie ? 'Create movie' : 'Save changes'} />
                </form>
            </div>
        </Modal>
    )
}

MovieFormModal.defaultProps = {
    movie: undefined,
}
