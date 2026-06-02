import React, { useEffect, useRef } from 'react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { Reveal } from "@/Components/Reveal";

export function AboutCompany({ content }) {
    const aboutBgRef = useRef(null);

    useEffect(() => {  
        gsap.registerPlugin(ScrollTrigger);     
        gsap.fromTo(aboutBgRef.current, 
        {
            backgroundPositionY: '100%',
        },
        {
            backgroundPositionY: '0%',
            duration: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: aboutBgRef.current,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true
            }
        });
    }, []);

    return (
        <section id="historia">
            <div className="relative md:h-[280px] w-full">
                <div
                    ref={aboutBgRef}
                    className="h-full w-full md:bg-[length:170%] bg-[60%] xl:bg-[length:100%] hidden md:block"
                    style={{
                        backgroundImage: `url(/site/img/bg-aguia-inox.jpg)`,
                    }}
                />
                <img src="/site/img/bg-aguia-inox-mobile.jpg" alt="Águia Inox" className="block md:hidden" />
                    
                <div className="absolute inset-0 opacity-50 bg-primary mix-blend-overlay" />
                <div className="absolute inset-0 opacity-20 bg-black" />
            </div>

            <div className="container max-w-large">
                <div className="mt-12 md:mt-20 grid grid-cols-2 items-center gap-24 max-[1025px]:grid max-[1025px]:grid-cols-1 max-[1025px]:gap-10">
                    <Reveal direction="left" className="flex flex-col gap-8">
                        <h2 className="font-sora text-7xl font-medium tracking-tight text-primary max-[769px]:text-4xl max-[601px]:text-5xl max-[1025px]:text-center">
                            {content.titulo}
                        </h2>
                        <div className="max-w-[618px] tracking-tight text-textblack max-[1025px]:max-w-full max-[1025px]:text-justify" dangerouslySetInnerHTML={{__html: content.texto }} />
                    </Reveal>
                    <Reveal className="max-md:hidden relative" direction="right">
                        <img
                            src={content.imagem}
                            alt={content.titulo}
                            className="z-10 h-[778px] w-full rounded-[10px] object-cover max-[1025px]:-mt-0 max-[1025px]:h-[400px]"
                        />
                        
                        <div className="absolute inset-0 rounded-[10px] bg-primary bg-linear-to-b from-transparent to-black/50 mix-blend-soft-light opacity-75 transition-all" /> 
                    </Reveal>
                </div>
            </div>
        </section>
    );
}