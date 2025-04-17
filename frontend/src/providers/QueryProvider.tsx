import { QueryClient, QueryClientProvider } from 'react-query'

const queryClient = new QueryClient()

export const QueryProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    return <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
}
