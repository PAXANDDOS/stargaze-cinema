import { Route } from '@tanstack/react-location'
import { RequireAnon } from '@/providers/AuthProvider'
import { ConsoleLayout } from '@/components/Layouts/ConsoleLayout'
import { HelmetProvider } from '@/providers/HelmetProvider'

interface Router {
    layout: JSX.Element
    routes: Route[]
}

export const consoleRouter: Router = {
    layout: <ConsoleLayout />,
    routes: [
        {
            path: '/',
            element: () =>
                import('@/pages/Console/ConsolePage').then(module => <module.ConsolePage />),
        },
        {
            path: 'movies',
            element: () =>
                import('@/pages/Console/MoviesPage').then(module => (
                    <HelmetProvider title="Movies" console>
                        <module.MoviesPage />
                    </HelmetProvider>
                )),
        },
        {
            path: 'sessions',
            element: () =>
                import('@/pages/Console/SessionsPage').then(module => (
                    <HelmetProvider title="Sessions" console>
                        <module.SessionsPage />
                    </HelmetProvider>
                )),
        },
        {
            path: 'tickets',
            element: () =>
                import('@/pages/Console/TicketsPage').then(module => (
                    <HelmetProvider title="Tickets" console>
                        <module.TicketsPage />
                    </HelmetProvider>
                )),
        },
        {
            path: 'halls',
            element: () =>
                import('@/pages/Console/HallsPage').then(module => (
                    <HelmetProvider title="Halls" console>
                        <module.HallsPage />
                    </HelmetProvider>
                )),
        },
        {
            path: 'genres',
            element: () =>
                import('@/pages/Console/GenresPage').then(module => (
                    <HelmetProvider title="Genres" console>
                        <module.GenresPage />
                    </HelmetProvider>
                )),
        },
        {
            path: 'directors',
            element: () =>
                import('@/pages/Console/DirectorsPage').then(module => (
                    <HelmetProvider title="Directors" console>
                        <module.DirectorsPage />
                    </HelmetProvider>
                )),
        },
        {
            path: 'signin',
            element: () =>
                import('@/pages/Auth/SignInPage').then(module => (
                    <RequireAnon>
                        <HelmetProvider title="Sign in" console>
                            <module.SignInPage />
                        </HelmetProvider>
                    </RequireAnon>
                )),
        },
        {
            path: '*',
            element: () =>
                import('@/pages/Console/ConsolePage').then(module => <module.ConsolePage />),
        },
    ],
}
