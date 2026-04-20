import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay } from 'swiper/modules';

import { Link, usePage } from "@inertiajs/react";

import 'swiper/css';

export function NewsCarousel() {
    const { posts } = usePage().props;

    return (
        <div className="lg:w-[63%] max-lg:mt-20">
            <Swiper
                modules={[Autoplay]}
                spaceBetween={40}
                slidesPerView={2.1}
                loop={false}
                pagination={{ clickable: true }}
                autoplay={{
                    delay: 10000,
                    disableOnInteraction: false,
                }}
                breakpoints={{
                    320: {
                        slidesPerView: 1.2,
                        spaceBetween: 16,
                    },
                    768: {
                        slidesPerView: 1.5,
                        spaceBetween: 20,
                    },
                    1024: {
                        slidesPerView: 1.8,
                        spaceBetween: 30,
                    },
                    1280: {
                        slidesPerView: 2,
                        spaceBetween: 40,
                    },
                }}
                className="lg:!overflow-visible h-full"
            >
                {posts.map((item, idx) => (
                    <SwiperSlide key={idx} className="select-none">
                        <div className="max-sm:h-[450px] max-lg:h-[500px] group relative flex h-full w-full items-end overflow-hidden rounded-[10px] border-[3px] border-primary p-8 sm:p-10 duration-500 transition-all hover:border-white">
                            <img
                                src={item.imagem}
                                alt={item.titulo}
                                loading="lazy"
                                className="absolute top-0 left-0 -z-10 h-full w-full object-cover"
                            />
                            <div className="absolute inset-0 -z-10 h-full w-full rounded-[10px] bg-primary opacity-80 mix-blend-overlay transition-all duration-500 group-hover:opacity-0 group-hover:h-0" />
                            <div className="absolute inset-0 -z-10 h-full w-full rounded-[10px] bg-gradient-to-t from-black/70 to-transparent transition-all duration-500 group-hover:opacity-100" />
                            <div className="w-full">
                                <p className="text-sm font-bold tracking-tight text-white">
                                    {item.categoria}
                                </p>
                                <Link aria-label={item.titulo} href={route('News.post', { categoria: item.categoria_slug, slug: item.slug})}>
                                    <p className="mt-2.5 font-sora text-2xl font-medium tracking-tighter text-white">
                                        {item.titulo}
                                    </p>
                                    <p className="mt-4 tracking-tight text-white line-clamp-3">
                                        {item.previa}
                                    </p>
                                </Link>
                            </div>
                        </div>
                    </SwiperSlide>
                ))}
            </Swiper>
        </div>
    );
}