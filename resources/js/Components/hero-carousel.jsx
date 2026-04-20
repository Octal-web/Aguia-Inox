import { useEffect, useState, useRef } from 'react';

import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';

import { Button } from './ui/button';
import FormattedTitle from './FormattedTitle';

import 'swiper/css';
import "swiper/css/effect-fade";

export function HeroCarousel({ slides = [] }) {
    const swiperRef = useRef(null);
    const [activeIndex, setActiveIndex] = useState(0);

    const handleSlideChange = (swiper) => {
        setActiveIndex(swiper.realIndex);
    };

    const goToSlide = (index) => {
        if (swiperRef.current) {
            swiperRef.current.slideTo(index);
        }
    };

    return (
        <div className="relative h-[calc(100vh_-_108px)] 2xl:h-[calc(100vh_-_130px)]">
            <Swiper
                ref={swiperRef}
                modules={[Autoplay, Pagination, EffectFade]}
                spaceBetween={0}
                slidesPerView={1}
                autoplay={{
                    delay: 5000,
                    disableOnInteraction: true,
                }}
                speed={800}
                loop={true}
                effect="fade"
                fadeEffect={{ crossFade: true }}
                onSlideChange={handleSlideChange}
                onSwiper={(swiper) => {
                    swiperRef.current = swiper;
                }}
                className="w-full h-full"
            >
                {slides.map((slide, index) => (
                    <SwiperSlide key={slide.id || index}>
                        <div className="relative h-full w-full bg-gradient-to-tr md:bg-gradient-to-r from-[#0D2940] to-transparent to-80% md:to-60%">
                            {slide.tipo === 'imagem' ? (
                                <>
                                    <img
                                        src={slide.imagem}
                                        alt={slide.titulo || ''}
                                        className="absolute top-0 left-0 -z-10 h-full w-full object-cover hidden md:block"
                                        loading="lazy"
                                    />
                                    {slide.imagem_mobile && (
                                        <img
                                            src={slide.imagem_mobile}
                                            alt={slide.titulo || ''}
                                            className="absolute top-0 left-0 -z-10 h-full w-full object-cover block md:hidden"
                                            loading="lazy"
                                        />
                                    )}
                                </>
                            ) : slide.tipo === 'video' ? (
                                <>
                                    <video
                                        src={slide.video}
                                        autoPlay
                                        muted
                                        loop
                                        playsInline
                                        className="absolute top-0 left-0 -z-10 h-full w-full object-cover hidden md:block"
                                    />
                                    {slide.video_mobile && (
                                        <video
                                            src={slide.video_mobile}
                                            autoPlay
                                            muted
                                            loop
                                            playsInline
                                            className="absolute top-0 left-0 -z-10 h-full w-full object-cover block md:hidden"
                                        />
                                    )}
                                </>
                            ) : null}
                            
                            <div className="flex max-h-screen h-[600px] md:h-[500px] w-full items-end max-md:pb-16 md:items-center xl:h-[550px] 2xl:h-[900px]">
                                <div className="container max-w-large">
                                    <div className={`max-w-[590px] transition-opacity duration-1000 ease-in-out z-[1] ${
                                        activeIndex === index
                                            ? 'animate-fade-in-down'
                                            : 'opacity-0'
                                    }`}>
                                        {slide.titulo ? (
                                            <>
                                                <h1 className="sr-only">{slide.titulo}</h1>
                                                <FormattedTitle text={slide.titulo} />
                                            </>
                                        ) : ''}
                                        
                                        {slide.descricao && (
                                            <p className="mt-6 mb-8 w-full max-w-[480px] text-base tracking-tight text-white md:text-lg">
                                                {slide.descricao}
                                            </p>
                                        )}
                                        
                                        {slide.link && slide.texto_botao && (
                                            <Button 
                                                onClick={() => window.open(slide.link, '_blank')}
                                                className="h-[54px] w-[273px] bg-white font-sora text-lg font-semibold text-primary hover:bg-primary hover:text-white max-[601px]:text-base"
                                            >
                                                {slide.texto_botao}
                                            </Button>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </SwiperSlide>
                ))}
            </Swiper>

            <div className="absolute top-1/2 right-4 -translate-y-1/2 md:right-10 z-10">
                <div className="hidden flex-col items-center gap-2 md:flex">
                    {Array.from({ length: slides.length }).map((_, index) => (
                        <button
                            key={index}
                            aria-label={`Ir para o Slide ${index + 1}`}
                            onClick={() => goToSlide(index)}
                            className={`h-14 w-1 rounded-[10px] bg-whit transition-all duration-300 ease-in-out hover:bg-opacity-50 ${
                                activeIndex === index ? "h-28 bg-white bg-opacity-100" : "bg-white bg-opacity-30"
                            }`}
                        />
                    ))}
                </div>
            </div>
        </div>
    );
}