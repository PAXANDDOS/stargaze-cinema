import { useState, createContext, useContext, useRef } from 'react'
import { createPortal } from 'react-dom'
import type { AxiosError, AxiosResponse } from 'axios'
import { Loading, Success, Warning } from '@/assets/icons/Misc'
import style from '@/assets/styles/toast.module.scss'

const ProviderContext = createContext<Toast>(null!)
export const useToast = () => useContext(ProviderContext)

interface ToastPromise {
    promise: Promise<AxiosResponse<any, any>>
    title?: string
    onSuccess?: (res: AxiosResponse) => void
    onError?: (err: AxiosError) => void
}

interface Toast {
    loading: (title?: string) => void
    success: (title?: string) => void
    error: (message?: string) => void
    promise: (params: ToastPromise) => void
    clear: () => void
}

export const ToastProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const [rendered, setRendered] = useState(false)
    const [title, setTitle] = useState('')
    const [icon, setIcon] = useState<any>(null)
    const ref = useRef<HTMLDivElement>(null)

    const promise = ({ promise, title, onSuccess, onError }: ToastPromise) => {
        setTitle(title ? title : 'Processing...')
        setIcon(<Loading className={style.spinAnimation} />)
        setRendered(true)
        promise
            .then((res: AxiosResponse) => {
                setIcon(<Success />)
                setTitle(res.data.message ? res.data.message : 'Success')
                setTimeout(clear, 5000)
                onSuccess && onSuccess(res)
            })
            .catch((err: AxiosError<any>) => {
                const responseMsg = err.response?.data.message
                const msg = Array.isArray(responseMsg)
                    ? () => {
                          let mergedMsg = ''
                          responseMsg.forEach((object: any) => {
                              for (const key in object) {
                                  const value = object[key]
                                  mergedMsg = `${mergedMsg}\n${key}:${value}`
                              }
                          })
                          return mergedMsg.trim()
                      }
                    : responseMsg
                setIcon(<Warning />)
                setTitle(msg)
                setTimeout(clear, 5000)
                onError && onError(err)
            })
    }

    const loading = (title?: string) => {
        title ? setTitle(title) : setTitle('Processing...')
        setIcon(<Loading className={style.spinAnimation} />)
        setRendered(true)
    }

    const success = (title?: string) => {
        title ? setTitle(title) : setTitle('Success')
        setIcon(<Success />)
        setRendered(true)
        setTimeout(clear, 5000)
    }

    const error = (message?: string) => {
        if (message) {
            const msg = Array.isArray(message)
                ? () => {
                      let mergedMsg = ''
                      message.forEach((object: any) => {
                          for (const key in object) {
                              const value = object[key]
                              mergedMsg = `${mergedMsg}\n${key}:${value}`
                          }
                      })
                      return mergedMsg.trim()
                  }
                : message
            setTitle(msg)
        } else setTitle('Something went wrong.')
        setIcon(<Warning />)
        setRendered(true)
        setTimeout(clear, 5000)
    }

    const clear = () => {
        if (!ref.current) return
        ref.current.className = `${style.toast} ${style.popDownAnimation}`
        ref.current.onanimationend = () => setRendered(false)
    }

    return (
        <>
            <ProviderContext.Provider
                value={{
                    loading,
                    success,
                    error,
                    promise,
                    clear,
                }}
            >
                {children}
            </ProviderContext.Provider>
            {createPortal(
                rendered && (
                    <div className={style.toastContainer}>
                        <div className={style.toast} ref={ref}>
                            {icon}
                            <span>{title}</span>
                        </div>
                    </div>
                ),
                document.getElementById('root')!
            )}
        </>
    )
}
