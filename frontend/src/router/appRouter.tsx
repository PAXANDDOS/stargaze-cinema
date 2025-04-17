import { Route, Navigate } from '@tanstack/react-location'
import { HelmetProvider } from '@/providers/HelmetProvider'
import { RequireAnon } from '@/providers/AuthProvider'
import { Layout } from '@/components/Layouts/Layout'
import { Footer } from '@/components/Footer/Footer'

interface Router {
    layout: JSX.Element
    routes: Route[]
}

export const appRouter: Router = {
    layout: <Layout />,
    routes: [
        {
            path: '/',
            element: <Navigate to="/movies" />,
        },
        {
            path: 'movies',
            children: [
                {
                    path: '/',
                    element: () =>
                        import('@/pages/Movies/MoviesPage').then(module => (
                            <HelmetProvider title="Movies">
                                <module.MoviesPage />
                                <Footer />
                            </HelmetProvider>
                        )),
                },
                {
                    path: ':movie',
                    element: () =>
                        import('@/pages/Movie/MoviePage').then(module => (
                            <HelmetProvider title="Movie">
                                <module.MoviePage />
                                <Footer />
                            </HelmetProvider>
                        )),
                },
            ],
        },
        {
            path: 'signin',
            element: () =>
                import('@/pages/Auth/SignInPage').then(module => (
                    <HelmetProvider title="Sign in">
                        <module.SignInPage />
                    </HelmetProvider>
                )),
        },
        {
            path: 'signup',
            element: () =>
                import('@/pages/Auth/SignUpPage').then(module => (
                    <RequireAnon>
                        <HelmetProvider title="Sign up">
                            <module.SignUpPage />
                        </HelmetProvider>
                    </RequireAnon>
                )),
        },
        {
            path: '*',
            element: <Navigate to="/movies" />,
        },
    ],
}
