import { Logo } from '@/assets/icons/Brand'
import { Facebook, Instagram } from '@/assets/icons/Social'
import style from './footer.module.scss'

export const Footer: React.FC = () => (
    <div className={style.footer}>
        <a className={style.footerBtn}>EN</a>
        <a className={style.footerBtn}>Terms of Service</a>
        <div className={style.footerLogo}>
            <Logo />
            <h3>
                Stargaze
                <br />
                Cinema
            </h3>
        </div>
        <a className={style.footerBtn}>Contact us</a>
        <a className={style.footerBtn}>
            <Facebook width={24} />
        </a>
        <a className={style.footerBtn}>
            <Instagram width={24} />
        </a>
    </div>
)
