import { StrictMode } from 'react'
import { AuthProvider } from './AuthProvider'
import { ToastProvider } from './ToastProvider'
import { QueryProvider } from './QueryProvider'
import { ModalProvider } from './ModalProvider'

export const AppProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    return (
        <StrictMode>
            <ToastProvider>
                <AuthProvider>
                    <QueryProvider>
                        <ModalProvider>{children}</ModalProvider>
                    </QueryProvider>
                </AuthProvider>
            </ToastProvider>
        </StrictMode>
    )
}
