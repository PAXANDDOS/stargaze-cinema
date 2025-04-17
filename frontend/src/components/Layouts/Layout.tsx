import { Link, Outlet } from '@tanstack/react-location'
import { Logo } from '@/assets/icons/Brand'
import { useAuth } from '@/providers/AuthProvider'
import style from './layout.module.scss'

export const Layout: React.FC = () => {
    const { user, signOut } = useAuth()

    return (
        <>
            <div className={style.layout}>
                <Link to="/movies">Movies</Link>
                <Link to="/" style={{ margin: '0 20px' }}>
                    <Logo width={36} />
                </Link>
                {user ? (
                    <button onClick={signOut}>Log out</button>
                ) : (
                    <Link to="/signin">Sign in</Link>
                )}
            </div>
            <Outlet />
        </>
    )
}
