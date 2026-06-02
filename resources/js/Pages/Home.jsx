import React, { useEffect, useRef } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { BrandsHome } from "@/Components/brands-home";
import { CasesCarousel } from "@/Components/cases-carousel";
import { Footer } from "@/Components/footer";
import { Header } from "@/Components/header";
import { HeroCarousel } from "@/Components/hero-carousel";
import { NewsCarousel } from "@/Components/news-carousel";
import { PartnerForm } from "@/Components/partner-form";
import { SegmentsHome } from "@/Components/segments-home";

import { Reveal } from "@/Components/Reveal";

import { useLang } from "@/hooks/useLang";

export default function Home() {
    const lang = useLang();

    const { slides, casesClientes, segmentos, clientes, conteudos } = usePage().props;

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
        <>
            <Header  />
            <HeroCarousel slides={slides} />
            <CasesCarousel casesItems={casesClientes} />
            <SegmentsHome content={conteudos[0]} segments={segmentos} />
            <BrandsHome brands={clientes} />

            <section className="mt-20 md:mt-32">
                <div className="relative md:h-[440px] w-full">
                    <div
                        ref={aboutBgRef}
                        className="h-full w-full md:bg-[length:170%] bg-[60%] xl:bg-[length:100%] hidden md:block"
                        style={{
                            backgroundImage: `url(/site/img/bg-aguia-inox.jpg)`,
                        }}
                    />
                    <img src="/site/img/bg-aguia-inox-mobile.jpg" alt="Sobre a Águia Inox" loading="lazy" className="block md:hidden" />
                    
                    <div className="absolute inset-0 bg-primary bg-linear-to-b from-transparent to-black/50 mix-blend-overlay" />
                </div>

                <div className="container max-w-large">
                    <div className="mt-10 md:mt-16 grid grid-cols-2 gap-[122px] max-[769px]:grid-cols-1">
                        <Reveal direction="right" className="max-[601px]:text-justify">
                            <p className="font-sora font-medium tracking-tight text-primary">
                                {lang('aAguiaInox')}
                            </p>
                            <h4 className="mt-2.5 max-w-[450px] font-sora text-4xl sm:text-5xl font-light tracking-tight text-secondary">
                                {conteudos[1].titulo}
                            </h4>
                            <div className="mt-10 w-full max-w-[618px] tracking-tight text-textblack" dangerouslySetInnerHTML={{__html: conteudos[1].texto }} />
                            <Link href={route('Institucional.index')} aria-label={lang('conhecaAguaInox')} className="inline-flex items-center justify-center rounded-md transition-all mt-10 h-[60px] w-full max-w-[300px] border-2 border-primary bg-transparent font-sora text-lg font-semibold text-primary hover:bg-primary hover:text-white">
                                {lang('conhecaAguaInox')}
                            </Link>
                        </Reveal>
                        <Reveal direction="left" className="relative -mt-40 max-h-screen h-[700px] w-full max-[769px]:-mt-0 max-2xl:-mt-10 max-[601px]:h-[300px]">
                            <img
                                src="/site/img/selo.png"
                                alt="Selo Águia Inox"
                                className="absolute max-md:w-1/3 -bottom-20 md:-bottom-[100px] 2xl:-bottom-[125px] -left-2 md:-left-[125px] z-10 animate-spin [animation-duration:_20s]"
                                loading="lazy"
                            />
                            <img
                                src={conteudos[1].imagem}
                                alt={conteudos[1].titulo}
                                className="absolute top-0 left-0 h-full w-full rounded-[10px] object-cover"
                                loading="lazy"
                            />
                            
                            <div className="absolute inset-0 rounded-[10px] bg-primary bg-linear-to-b from-transparent to-black/50 mix-blend-soft-light opacity-75" /> 
                        </Reveal>
                    </div>
                </div>
            </section>

            {/*<div className="relative mt-44 2xl:mt-60 h-[410px] 2xl:h-[580px] w-full bg-primary max-[769px]:hidden"></div>

            <div className="relative h-fit w-full bg-bottom pt-[763px] pb-32 md:pb-[246px] xl:pb-0 2xl:pb-[246px] max-[769px]:mt-28 max-[769px]:flex max-[769px]:flex-col max-[769px]:gap-20 max-[769px]:pt-10 max-2xl:pt-[530px]" style={{ backgroundImage: 'url("/site/img/pattern-bg.jpg")' }}>
                <div className="absolute -top-1/4 left-1/2 max-2xl:h-[580px] h-[778px] w-full max-w-[1630px] -translate-x-1/2 max-[769px]:static max-[769px]:-top-1/6 max-[769px]:-translate-x-0 max-[601px]:h-fit">
                    <div className="relative container max-w-large h-full">
                        <div className="flex w-full gap-10 max-[769px]:flex-col h-full">
                            <Reveal direction="left" className="relative z-10 w-full max-w-[970px] overflow-hidden rounded-[10px] max-[769px]:h-fit max-[769px]:max-w-full">
                                <img
                                    src={conteudos[4].imagem}
                                    alt=""
                                    className="absolute top-0 left-0 -z-10 h-full w-full object-cover"
                                />
                                <div className="absolute inset-0 -z-10 rounded-[10px] bg-primary/80 mix-blend-soft-light max-[601px]:bg-black" />
                                <div className="flex h-full flex-col items-start justify-end gap-5 max-sm:px-10 p-14">
                                    <p className="font-sora text-3xl font-light tracking-tight text-white">
                                        {conteudos[4].titulo}
                                    </p>
                                    <p className="max-w-[540px] tracking-tight text-white max-md:text-justify">
                                        {conteudos[4].texto}
                                    </p>
                                    <Link href={conteudos[4].link} target={conteudos[4].nova_aba ? '_blank' : '_self'}  className="inline-flex items-center justify-center h-[50px] 2xl:h-[60px] w-[194px] border-2 border-white rounded-md transition-all bg-transparent font-sora text-lg text-white font-semibold hover:border-primary hover:bg-primary hover:text-white">
                                        Saber mais
                                    </Link>
                                </div>
                            </Reveal>
                            <Reveal direction="right" className="relative z-10 w-full max-w-[575px] rounded-[10px] max-[769px]:h-fit max-[769px]:max-w-full max-[601px]:overflow-hidden">
                                <img
                                    src={conteudos[5].imagem}
                                    alt=""
                                    className="absolute top-0 left-0 -z-10 h-full w-full object-cover rounded-[10px]"
                                />
                                <div className="absolute inset-0 -z-10 rounded-[10px] bg-primary/80 mix-blend-soft-light max-[601px]:bg-black" />
                                <img
                                    src="/site/img/selo-white.png"
                                    alt=""
                                    className="absolute right-0 -bottom-[200px] 2xl:-bottom-[260px] z-10 object-contain max-2xl:w-64 max-[769px]:hidden animate-spin [animation-duration:_20s]"
                                />
                                <div className="flex h-full w-full flex-col items-start justify-end gap-5 max-sm:px-10 p-14">
                                    <p className="font-sora text-3xl font-light tracking-tight text-white">
                                        {conteudos[5].titulo}
                                    </p>
                                    <p className="max-w-[540px] tracking-tight text-white max-md:text-justify">
                                        {conteudos[5].texto}
                                    </p>
                                    <Link href={conteudos[5].link} target={conteudos[5].nova_aba ? '_blank' : '_self'} className="inline-flex items-center justify-center h-[50px] 2xl:h-[60px] w-[194px] border-2 border-white rounded-md transition-all bg-transparent font-sora text-lg text-white font-semibold hover:border-primary hover:bg-primary hover:text-white">
                                        Saber mais
                                    </Link>
                                </div>
                            </Reveal>
                        </div>
                    </div>
                </div> */}
            <div className="relative mt-28 md:mt-40 pt-20 md:pt-40 pb-24 2xl:py-40 w-full bg-bottom" style={{ backgroundImage: 'url("/site/img/pattern-bg.jpg")' }}>

                <section className="overflow-hidden pb-0 xl:pb-24 2xl:pb-0">
                    <div className="container max-w-large">
                        <div className="lg:flex w-full justify-between max-[1025px]:h-fit max-[1025px]:flex-col max-[1025px]:gap-10">
                            <Reveal direction="left" className="relative max-[601px]:px-6 max-[601px]:text-justify w-full lg:max-w-[37%] z-[2]">
                                <div className="hidden lg:block absolute w-[50vw] right-0 top-0 -bottom-[246px] bg-bottom -z-[1]" style={{
                                    backgroundImage: 'url("/site/img/pattern-bg.jpg")',
                                    WebkitMaskImage: 'linear-gradient(to right, black 90%, transparent 100%)',
                                    maskImage: 'linear-gradient(to right, black 90%, transparent 100%)',
                                    WebkitMaskRepeat: 'no-repeat',
                                    maskRepeat: 'no-repeat',
                                }} />
                                <p className="font-sora tracking-tight text-white">
                                    News
                                </p>
                                <h4 className="mt-2.5 w-full max-w-[350px] font-sora text-4xl md:text-5xl font-light tracking-tight text-white max-[601px]:max-w-full">
                                    <strong className="font-bold text-primary">
                                        {conteudos[3].titulo}
                                    </strong>{" "}
                                    {conteudos[3].subtitulo}
                                </h4>
                                <p className="mt-7 w-full max-w-[353px] tracking-tight text-white max-[601px]:max-w-full">
                                    {conteudos[3].texto}
                                </p>
                                <Link href={route('News.index')} aria-label={lang('verTodasNoticias')} className="inline-flex items-center justify-center mt-16 lg:mt-[100px] h-[60px] w-full max-w-[300px] border-2 border-white rounded-md transition-all bg-transparent font-sora text-lg text-white hover:border-primary hover:bg-primary hover:text-white">
                                    {lang('verTodasNoticias')}
                                </Link>
                            </Reveal>
                            <NewsCarousel />
                        </div>
                    </div>
                </section>
            </div>
            <PartnerForm />
            <Footer />
        </>
    );
}
