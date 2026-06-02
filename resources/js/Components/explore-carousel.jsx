import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';
import { Link } from "@inertiajs/react";
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

import { useLang } from "@/hooks/useLang";

export function ExploreCarousel({ items }) {
    const lang = useLang();

    return (
        <Swiper
            modules={[Autoplay, Navigation, Pagination]}
            spaceBetween={30}
            slidesPerView={4.2}
            centeredSlides={true}
            loop={true}
            autoplay={{
                delay: 8000,
                stopOnLastSlide: false,
                disableOnInteraction: true,
            }}
            breakpoints={{
                0: {
                    slidesPerView: 1.6,
                    spaceBetween: 20
                },
                769: {
                    slidesPerView: 2.5,
                    spaceBetween: 25,
                },
                1025: {
                    slidesPerView: 3.2,
                    spaceBetween: 30,
                },
                1367: {
                    slidesPerView: 4.2,
                    spaceBetween: 30
                },
            }}
            onMouseEnter={(swiper) => {
                swiper.autoplay.stop();
            }}
            onMouseLeave={(swiper) => {
                swiper.autoplay.start();
            }}
            className=""
        >
            {[...items, ...items, ...items].map((item, index) => (
                <SwiperSlide key={index} className="!h-auto">
                    <div className="overflow-hidden rounded-[10px] bg-[#EDF1F8] h-full flex flex-col">
                        <Link href={route('News.post', { categoria: item.categoria_slug, slug: item.slug})} className="block">
                            <img
                                src={item.imagem}
                                alt={item.titulo}
                                className="h-[298px] w-full object-cover"
                            />
                        </Link>
                        <div className="w-full rounded-b-[10px] px-5 md:px-11 pt-6 sm:pt-9">
                            <Link href={route('News.post', { categoria: item.categoria_slug, slug: item.slug})} className="transition-all hover:opacity-70">
                                <h3 className="font-sora text-lg sm:text-xl max-md:leading-tight font-medium tracking-tight text-textblack max-md:line-clamp-3">
                                    {item.titulo}
                                </h3>
                            </Link>
                            <p className="mt-2 mb-4 max-md:text-sm line-clamp-3 tracking-tight text-textblack">
                                {item.previa}
                            </p>
                        </div>
                        
                        <Link
                            href={route('News.post', { categoria: item.categoria_slug, slug: item.slug})}
                            className="mx-5 md:mx-11 max-md:text-sm mb-8 md:mb-14 mt-auto inline-block font-sora font-bold text-primary underline transition-all hover:opacity-70"
                        >
                            {lang('verProjetoCompleto')}
                        </Link>
                    </div>
                </SwiperSlide>
            ))}

        </Swiper>
    );
}