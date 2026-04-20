import { useEffect, useRef } from 'react';
import { Link } from '@inertiajs/react';

import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Pagination } from 'swiper/modules';
import 'swiper/css';
import "swiper/css/pagination";

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export function SegmentsHome({ content, segments }) {
    const sectionRef = useRef(null);
    const slidesRef = useRef([]);
    const titleRef = useRef(null);
    const subtitleRef = useRef(null);

    useEffect(() => {
        const ctx = gsap.context(() => {
            const isMobile = window.innerWidth < 768;
            const startPosition = isMobile ? "top 60%" : "top 10%";

            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: sectionRef.current,
                    start: startPosition,
                    end: "bottom bottom",
                    reverse: true,
                    toggleActions: "play none none reverse"
                }
            });

            tl.from(titleRef.current, {
                y: 50,
                opacity: 0,
                duration: 0.8,
                ease: "power3.out"
            })
            .from(subtitleRef.current, {
                y: 30,
                opacity: 0,
                duration: 0.6,
                ease: "power2.out"
            }, "-=0.4");

            slidesRef.current.forEach((slide, index) => {
                if (slide) {
                    gsap.set(slide, {
                        y: 100,
                        opacity: 0,
                        scale: 0.8,
                        rotationX: 15
                    });

                    gsap.to(slide, {
                        y: 0,
                        opacity: 1,
                        scale: 1,
                        rotationX: 0,
                        duration: 1,
                        ease: "back.out(1.7)",
                        delay: index * 0.15,
                        scrollTrigger: {
                            trigger: sectionRef.current,
                            start: startPosition,
                            toggleActions: "play none none reverse"
                        }
                    });
                }
            });

        }, sectionRef);

        return () => ctx.revert();
    }, [segments]);

    const addSlideRef = (el, index) => {
        if (el) {
            slidesRef.current[index] = el;
        }
    };

    return (
        <section className="-mt-12 2xl:-mt-28 flex w-full flex-col gap-16 bg-[#EDF1F8] pt-32 pb-32 2xl:pb-40 overflow-hidden">
            <div className="container max-w-large">
                <div ref={sectionRef} className="max-[601px]:text-center">
                    {content.titulo && (() => {
                        const [firstWord, ...rest] = content.titulo.split(' ');
                        return (
                            <h3 
                                ref={titleRef}
                                className="font-sora text-5xl font-light tracking-tight text-secondary max-[601px]:text-3xl"
                            >
                                <span className="font-bold">{firstWord}</span>{' '}
                                {rest.join(' ')}
                            </h3>
                        );
                    })()}

                    <p 
                        ref={subtitleRef}
                        className="mt-10 max-w-[918px] tracking-tight text-secondary"
                    >
                        {content.texto}
                    </p>
                </div>
                
                <div className="relative mt-16">
                    <Swiper
                        modules={[Autoplay, Pagination]}
                        spaceBetween={30}
                        slidesPerView={2.2}
                        breakpoints={{
                            0: {
                                slidesPerView: 1.2,
                                spaceBetween: 20,
                            },
                            769: {
                                slidesPerView: 1.8,
                                spaceBetween: 20,
                            },
                            1281: {
                                slidesPerView: 2.2,
                                spaceBetween: 30,
                            },
                        }}
                        autoplay={{
                            delay: 10000,
                            disableOnInteraction: false,
                        }}
                        pagination={{
                            clickable: true
                        }}
                        className="!overflow-visible [&_.swiper-pagination]:!-bottom-14 [&_.swiper-pagination_.swiper-pagination-bullet]:!w-6 [&_.swiper-pagination_.swiper-pagination-bullet]:!rounded-full [&_.swiper-pagination_.swiper-pagination-bullet-active]:!bg-primary"
                    >
                        {segments.map((item, idx) => (
                            <SwiperSlide key={idx}>
                                <div 
                                    ref={(el) => addSlideRef(el, idx)}
                                    className="relative flex h-full w-full flex-col items-center group"
                                >
                                    <div className="relative">
                                        <img
                                            src={item.banner}
                                            alt={item.nome}
                                            className="top-0 left-0 -z-10 aspect-[60/35] md:aspect-[101/35] object-cover rounded-xl w-full"
                                            loading="lazy"
                                        />

                                        <div className="absolute inset-0 rounded-[10px] bg-primary bg-linear-to-b from-transparent to-black/50 mix-blend-soft-light opacity-75" />
                                    </div>
                                    <div className="mt-6 w-full">
                                        <div className="flex flex-row-reverse items-center justify-between">
                                            <Link
                                                href={route('Segmentos.segmento', { slug: item.slug })}
                                                className="flex h-10 md:h-14 w-10 md:w-14 items-center justify-center rounded-full border-2 border-primary bg-transparent font-sora text-5xl md:text-6xl text-primary transition-all ease-in hover:bg-primary hover:text-white"
                                            >
                                                +
                                            </Link>
                                            <Link href={route('Segmentos.segmento', { slug: item.slug })}>
                                                <h5 className="font-sora text-2xl font-medium tracking-tight text-secondary max-[601px]:text-xl">
                                                    {item.nome}
                                                </h5>
                                            </Link>
                                        </div>
                                        <div 
                                            className="mt-4 w-full max-w-[584px] tracking-tight text-textblack text-sm md:text-base" 
                                            dangerouslySetInnerHTML={{__html: item.descricao }} 
                                        />
                                    </div>
                                </div>
                            </SwiperSlide>
                        ))}
                    </Swiper>
                </div>
            </div>
        </section>
    );
}