import { useState, createContext, useContext, lazy, Suspense } from 'react'

const ProviderContext = createContext<Modal>(null!)
export const useModal = () => useContext(ProviderContext)

interface Modal {
    showModal: ShowModal
}

interface ShowModal {
    (name: string, props?: object): void
}

export const ModalProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const [Modal, setModal] = useState<any>()
    const [props, setProps] = useState<object>()

    const showModal: ShowModal = (name, props) => {
        setProps(props)
        setModal(
            lazy(() =>
                import(/* @vite-ignore */ `../components/Modals/${name}.tsx`).then(module => ({
                    default: module[name],
                }))
            )
        )
    }

    return (
        <>
            <ProviderContext.Provider
                value={{
                    showModal,
                }}
            >
                {children}
            </ProviderContext.Provider>
            <Suspense fallback={null}>
                {Modal && <Modal onClose={() => setModal(null)} {...props} />}
            </Suspense>
        </>
    )
}
