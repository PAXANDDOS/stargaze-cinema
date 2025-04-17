import { Outlet, Link, Navigate } from '@tanstack/react-location'
import { useAuth } from '@/providers/AuthProvider'
import { hasAdminRole } from '@/services/authService'
import { ConsoleBtn } from '../Buttons/ConsoleBtn'
import { Logo } from '@/assets/icons/Brand'
import style from './consoleLayout.module.scss'

export const ConsoleLayout: React.FC = () => {
    const { user, signOut } = useAuth()

    return (
        <>
            {hasAdminRole(user) ? (
                <div className={style.layout}>
                    <div className={style.head}>
                        <Link to="/">
                            <Logo width={36} />
                        </Link>
                        <span>Administration console</span>
                        <button onClick={signOut}>Log out</button>
                    </div>
                    <div className={style.body}>
                        <div className={style.sidebar}>
                            <ConsoleBtn title="Movies" />
                            <ConsoleBtn title="Sessions" />
                            <ConsoleBtn title="Tickets" />
                            <ConsoleBtn title="Halls" />
                            <ConsoleBtn title="Genres" />
                            <ConsoleBtn title="Directors" />
                        </div>
                        <Outlet />
                    </div>
                </div>
            ) : (
                <>
                    <Navigate to="/signin" />
                    <Outlet />
                </>
            )}
        </>
    )
}
