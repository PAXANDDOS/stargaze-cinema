import { createRoot } from 'react-dom/client'
import { Router, ReactLocation } from '@tanstack/react-location'
import { AppProvider } from './providers/AppProvider'
import { appRouter } from '@/router/appRouter'
import { consoleRouter } from '@/router/consoleRouter'
import '@/assets/styles/index.css'

const location = new ReactLocation()

const getRouter = () => {
    const subdomains = window.location.host.split('.')
    const currentSubdomain = subdomains[0] === 'www' ? subdomains[1] : subdomains[0]

    const router: any = {
        app: appRouter,
        console: consoleRouter,
    }

    return router[currentSubdomain] || router['app']
}

createRoot(document.getElementById('root')!).render(
    <AppProvider>
        <Router location={location} routes={getRouter().routes}>
            {getRouter().layout}
        </Router>
    </AppProvider>
)
