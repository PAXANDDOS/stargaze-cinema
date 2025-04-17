import { Helmet } from 'react-helmet'

interface Props {
    title: string
    console?: boolean
    children: React.ReactNode
}

export const HelmetProvider: React.FC<Props> = ({ children, title, console }) => {
    return (
        <>
            <Helmet>
                <title>
                    {title} &#8739; {console ? 'Console' : 'Stargaze Cinema'}
                </title>
            </Helmet>
            {children}
        </>
    )
}
