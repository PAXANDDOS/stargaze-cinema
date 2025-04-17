import { Pagination, Navigation } from 'swiper'
import { Swiper, SwiperSlide } from 'swiper/react'
import type { Frame } from '@/types/Frame'
import style from './movieFramer.module.scss'
import 'swiper/css'
import 'swiper/css/pagination'
import 'swiper/css/navigation'

interface Props {
    frames: Frame[]
}

export const MovieFramer: React.FC<Props> = ({ frames }) => (
    <div className={style.movieFramer}>
        <Swiper
            pagination={{
                dynamicBullets: true,
            }}
            style={{
                // @ts-ignore
                '--swiper-navigation-color': '#6772eb',
                '--swiper-pagination-color': '#6772eb',
            }}
            navigation={true}
            modules={[Pagination, Navigation]}
            className={style.swiper}
        >
            {frames.map(frame => (
                <SwiperSlide key={frame.id}>
                    <a href={frame.image} target="_blank" rel="noreferrer">
                        <img src={frame.image} alt={`frame${frame.id}`} className={style.frame} />
                    </a>
                </SwiperSlide>
            ))}
        </Swiper>
    </div>
)
