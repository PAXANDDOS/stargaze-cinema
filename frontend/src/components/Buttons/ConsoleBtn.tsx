import { Link, useLocation } from '@tanstack/react-location'
import style from './consoleBtn.module.scss'

export const ConsoleBtn: React.FC<{ title: string }> = ({ title }) => {
    const path = useLocation().current.pathname

    return (
        <>
            {path.substring(1) === title.toLowerCase() ? (
                <button type="button" className={`${style.consoleBtn} ${style.btnActive}`} disabled>
                    {title}
                </button>
            ) : (
                <Link to={`${title.toLowerCase()}`}>
                    <button type="button" className={style.consoleBtn}>
                        {title}
                    </button>
                </Link>
            )}
        </>
    )
}
