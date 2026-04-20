import React, { useRef, useEffect, useState } from 'react';
import { Link } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Pagination } from 'swiper/modules';

import { useLang } from "@/hooks/useLang";

import 'swiper/css';
import 'swiper/css/pagination';

gsap.registerPlugin(ScrollTrigger);

export const CasesCarousel = ({ casesItems }) => {
    const lang = useLang();
    
    const containerRef = useRef(null);
    const scrollContainerRef = useRef(null);
    const [isMobile, setIsMobile] = useState(false);
    
    const breakpoints = {
        mobile: 601,
        tablet: 1024,
        notebook: 1440,
    };
    
    useEffect(() => {
        const checkMobile = () => {
            setIsMobile(window.innerWidth <= breakpoints.mobile);
        };
        
        checkMobile();
        window.addEventListener('resize', checkMobile);
        
        return () => window.removeEventListener('resize', checkMobile);
    }, []);
    
    useEffect(() => {
        // Não executa o ScrollTrigger no mobile
        if (window.innerWidth <= breakpoints.mobile) return;
        
        const container = containerRef.current;
        const scrollContainer = scrollContainerRef.current;
        
        if (!container || !scrollContainer || !casesItems.length) return;
        
        const getStartValue = () => {
            const windowWidth = window.innerWidth;

            if (windowWidth <= breakpoints.tablet) {
                return "top 15%";
            } else if (windowWidth <= breakpoints.notebook) {
                return "top 10%";
            } else {
                return "top 10%";
            }
        };
        
        const getSlideWidth = () => {
            const windowWidth = window.innerWidth;
            if (windowWidth <= breakpoints.tablet) {
                return 45;
            } else {
                return 23;
            }
        };
        
        const slideWidthPercent = getSlideWidth();
        const slideWidth = (window.innerWidth * slideWidthPercent) / 100;
        const totalWidth = slideWidth * casesItems.length;
        const containerWidth = container.offsetWidth;
        const scrollDistance = totalWidth - containerWidth;
        
        if (scrollDistance <= 0) return;
        
        scrollContainer.style.width = `${casesItems.length * slideWidthPercent}vw`;
        scrollContainer.querySelectorAll('.line-slide').forEach(slide => {
            slide.style.width = `${slideWidthPercent}vw`;
        });
        
        const horizontalScroll = gsap.to(scrollContainer, {
            x: -scrollDistance,
            ease: "none",
            scrollTrigger: {
                trigger: container,
                start: getStartValue(),
                end: () => `+=${scrollDistance}`,
                scrub: 1,
                pin: true,
                pinSpacing: true,
                anticipatePin: 1,
                invalidateOnRefresh: true,
                refreshPriority: -1,
                onRefresh: () => {
                    const newSlideWidthPercent = getSlideWidth();
                    const newSlideWidth = (window.innerWidth * newSlideWidthPercent) / 100;
                    const newTotalWidth = newSlideWidth * casesItems.length;
                    const newScrollDistance = newTotalWidth - container.offsetWidth;
                    
                    scrollContainer.style.width = `${casesItems.length * newSlideWidthPercent}vw`;
                    scrollContainer.querySelectorAll('.line-slide').forEach(slide => {
                        slide.style.width = `${newSlideWidthPercent}vw`;
                    });
                },
                onUpdate: (self) => {
                    const progress = self.progress;
                    const activeIndex = Math.min(Math.floor(progress * casesItems.length), casesItems.length - 1);
                    
                    const indicators = container.querySelectorAll('.progress-dot');
                    indicators.forEach((dot, index) => {
                        if (index === activeIndex) {
                            dot.classList.add('active');
                        } else {
                            dot.classList.remove('active');
                        }
                    });
                    
                    scrollContainer.querySelectorAll('.line-slide img').forEach((img, index) => {
                        const offset = (index - activeIndex) * 0.1;
                        gsap.set(img, { scale: 1 + Math.abs(offset) * 0.05 });
                    });
                }
            }
        });
        
        return () => {
            if (horizontalScroll) {
                horizontalScroll.kill();
            }
            ScrollTrigger.getAll().forEach(trigger => {
                if (trigger.trigger === container) {
                    trigger.kill();
                }
            });
        };
    }, [casesItems, isMobile]);
    
    useEffect(() => {
        const style = document.createElement('style');
        style.textContent = `
            .progress-dot.active {
                background: rgba(0, 0, 0, 0.9) !important;
                transform: scale(1.2);
            }
            
            .line-slide img {
                transition: transform 0.6s ease-out;
            }
            
            .gsap-pin-spacer {
                overflow: visible !important;
            }
        `;
        document.head.appendChild(style);
        
        return () => {
            if (document.head.contains(style)) {
                document.head.removeChild(style);
            }
        };
    }, []);

    return (
        <div className="mt-16 sm:mt-20 2xl:mt-28 flex w-full flex-col gap-16 overflow-hidden">
            <div className="container max-w-large">
                <div ref={containerRef} style={{ minHeight: isMobile ? 'auto' : '90vh' }}>
                    <div className="flex w-full items-center justify-between max-[601px]:flex-col">
                        <h3 className="font-sora text-5xl font-medium tracking-tight text-secondary max-[601px]:text-3xl">
                            {lang('cases')}
                        </h3>
                        <Link
                            href={route('News.index') + '#cases'}
                            className="font-sora text-lg tracking-tight text-secondary underline max-[601px]:text-base"
                        >
                            {lang('verTodosOsCases')}
                        </Link>
                    </div>
                    
                    <div className="mt-16 w-full">
                        {isMobile ? (
                            <Swiper
                                modules={[Pagination]}
                                spaceBetween={14}
                                slidesPerView={1.15}
                                centeredSlides={false}
                                pagination={{
                                    clickable: true,
                                    bulletClass: 'swiper-pagination-bullet !bg-secondary/30',
                                    bulletActiveClass: 'swiper-pagination-bullet-active !bg-secondary',
                                }}
                                className="!pb-12 !overflow-visible"
                            >
                                {casesItems.map((item, idx) => (
                                    <SwiperSlide key={idx}>
                                        <div className="h-[480px] w-full group relative flex items-end overflow-hidden rounded-[10px] bg-gradient-to-t from-[#000000] to-transparent to-20% pb-8">
                                            <img
                                                src={item.imagem}
                                                alt={item.titulo}
                                                className="absolute top-0 left-0 -z-10 h-full w-full object-cover"
                                                loading="lazy"
                                            />

                                            <div className="absolute inset-0 rounded-[10px] bg-primary bg-linear-to-b from-transparent to-black/50 mix-blend-soft-light opacity-75 transition-all group-hover:opacity-40" /> 
                                            <div className="relative flex w-full items-center justify-around px-4">
                                                <Link
                                                    href={route('News.post', { categoria: item.categoria_slug, slug: item.slug})}
                                                    className="relative flex min-h-[50px] w-fit px-6 max-w-[288px] items-center justify-center rounded-3xl bg-white/45 backdrop-blur-md"
                                                >
                                                    <p className="font-sora font-medium text-white line-clamp-2 !leading-tight text-base tracking-tightest">
                                                        {item.titulo}
                                                    </p>
                                                </Link>
                                            </div>
                                        </div>
                                    </SwiperSlide>
                                ))}
                            </Swiper>
                        ) : (
                            <div 
                                ref={scrollContainerRef} 
                                className="-ml-5 flex will-change-transform" 
                                style={{ width: `${casesItems.length * 23}vw` }}
                            >
                                {casesItems.map((item, idx) => (
                                    <div
                                        key={idx}
                                        className="line-slide h-[480px] xl:h-[420px] 2xl:h-[666px] 2xl:pb-24 pl-5 basis-[calc(100%/3.6)] max-[1281px]:basis-[calc(100%/2.5)] max-[723px]:basis-[calc(100%/1.2)] select-none"
                                    >
                                        <div className="group relative flex h-full w-full items-end overflow-hidden rounded-[10px] bg-gradient-to-t from-[#000000] to-transparent to-20% pb-8">
                                            <img
                                                src={item.imagem}
                                                alt={item.titulo}
                                                className="absolute top-0 left-0 -z-10 h-full w-full object-cover"
                                                loading="lazy"
                                            />

                                            <div className="absolute inset-0 rounded-[10px] bg-primary bg-linear-to-b from-transparent to-black/50 mix-blend-soft-light opacity-75 transition-all group-hover:opacity-40" /> 
                                            <div className="relative flex w-full items-center justify-around">
                                                <Link
                                                    href={route('News.post', { categoria: item.categoria_slug, slug: item.slug})}
                                                    className="relative flex min-h-[50px] xl:h-[64px] 2xl:h-[72px] max-2xl:w-fit px-4 2xl:px-6 max-w-[236px] 2xl:max-w-[288px] items-center justify-center rounded-3xl bg-white/45 backdrop-blur-md"
                                                >
                                                    <h4 className="font-sora xl:text-lg 2xl:text-xl font-medium text-white line-clamp-2 !leading-tight tracking-tightest">
                                                        {item.titulo}
                                                    </h4>
                                                </Link>
                                                <Link
                                                    href={route('News.post', { categoria: item.categoria_slug, slug: item.slug})}
                                                    className="flex h-12 2xl:h-14 w-12 2xl:w-14 items-center justify-center rounded-full border-2 border-primary bg-white font-sora text-5xl 2xl:text-6xl text-primary transition-all ease-in hover:bg-primary hover:text-white"
                                                >
                                                    +
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}