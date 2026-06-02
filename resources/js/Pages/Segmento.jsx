import { Footer } from "@/components/footer";
import { Header } from "@/components/header";
import { PartnerForm } from "@/components/partner-form";
import { Link, usePage } from '@inertiajs/react';

import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";
import { useState, useEffect, useRef, useCallback } from "react";
import { useLang } from "@/hooks/useLang";

export default function Index() {

    const { segmento } = usePage().props;

    const lang = useLang();

    const [allCategories, setAllCategories] = useState(false);
    const [activeCategory, setActiveCategory] = useState('');
    const [isSticky, setIsSticky] = useState(false);
    const [hideSticky, setHideSticky] = useState(false);
    const containerRef = useRef(null);
    const menuRef = useRef(null);
    const menuOffsetRef = useRef(0);
    const isStickyRef = useRef(false);
    const activeCategoryRef = useRef('');

    useEffect(() => {
        isStickyRef.current = isSticky;
    }, [isSticky]);

    useEffect(() => {
        activeCategoryRef.current = activeCategory;
    }, [activeCategory]);

    const handleCategoriesAll = () => setAllCategories(!allCategories);

    const handleCategoryClick = useCallback((categorySlug) => {
        const element = document.getElementById(categorySlug);
        if (element) {
            const offset = isStickyRef.current ? 100 : 0;
            const elementPosition = element.getBoundingClientRect().top + window.pageYOffset - offset;
            
            window.scrollTo({
                top: elementPosition,
                behavior: 'smooth'
            });
            
            setActiveCategory(categorySlug);
        }
    }, []);

    useEffect(() => {
        const handleScroll = () => {
            if (menuRef.current && containerRef.current) {
                const scrollTop = window.pageYOffset;

                if (menuOffsetRef.current === 0) {
                    menuOffsetRef.current = menuRef.current.getBoundingClientRect().top + scrollTop;
                }

                const shouldBeSticky = scrollTop >= menuOffsetRef.current;

                if (shouldBeSticky !== isStickyRef.current) {
                    setIsSticky(shouldBeSticky);
                }

                const containerBottom = containerRef.current.getBoundingClientRect().bottom;
                setHideSticky(containerBottom < 100);

                const offset = shouldBeSticky ? 100 : 20;
                let currentActive = '';

                segmento.categorias.forEach((categoria) => {
                    const element = document.getElementById(categoria.slug);
                    if (element) {
                        const rect = element.getBoundingClientRect();
                        if (rect.top <= offset && rect.bottom > offset) {
                            currentActive = categoria.slug;
                        }
                    }
                });

                if (currentActive && currentActive !== activeCategoryRef.current) {
                    setActiveCategory(currentActive);
                }
            }
        };

        let ticking = false;
        const throttledHandleScroll = () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        };

        window.addEventListener('scroll', throttledHandleScroll, { passive: true });

        return () => window.removeEventListener('scroll', throttledHandleScroll);
    }, [segmento.categorias]);

    useEffect(() => {
        if (segmento.categorias.length > 0) {
            setActiveCategory(segmento.categorias[0].slug);
        }
    }, [segmento.categorias]);

    return (
        <>
            <Header />
            <section>
                <div className="relative h-[350px] w-full max-[601px]:h-[520px] max-[1025px]:h-[500px]">
                    <img
                        src="/site/img/bg-produtos.png"
                        alt="Produtos do Segmento"
                        className="h-full w-full object-cover"
                    />
                    <div className="absolute inset-0 bg-[#142B3E] mix-blend-overlay" />
                    <div className="absolute inset-0 bg-gradient-to-r from-[#061521] to-[#00000024] md:to-[#00000000]" />

                    <div className="absolute top-1/2 left-0 w-full -translate-y-1/2 max-[1025px]:left-1/2 max-[1025px]:-translate-x-1/2 max-[769px]:text-justify">
                        <div className="container max-w-large">
                            <h3 className="font-sora text-5xl font-semibold tracking-tight text-primary max-[769px]:text-3xl">
                                {segmento.nome}
                            </h3>
                            <h1 className="mt-4 md:mt-8 max-w-[1172px] tracking-tighter sm:tracking-tight text-white max-[1025px]:max-w-full text-sm sm:text-base" dangerouslySetInnerHTML={{ __html: segmento.descricao }} />
                        </div>
                    </div>
                </div>
            </section>

            <div ref={containerRef} className="">
                <div
                    ref={menuRef}
                    className={`max-[601px]:hidden z-50 w-full bg-white transition-transform duration-500 ${
                        isSticky ? `fixed top-0 left-0 shadow-md ${hideSticky ? 'duration-150 -translate-y-[102%]' : 'translate-y-0'}` : 'relative'
                    }
                    ${
                        segmento.categorias.length < 2 ? 'hidden' : ''
                    }`}
                >
                    <div className="container max-w-x-large">
                        <div className="relative grid w-full grid-cols-7 max-[1367px]:grid-cols-6 max-[1025px]:grid-cols-4 max-[769px]:grid-cols-3 max-[601px]:grid-cols-1 max-[601px]:gap-y-4 border-b-2 border-primary/25">
                            {segmento.categorias.map((categoria, index) => (
                                <button
                                    onClick={() => handleCategoryClick(categoria.slug)}
                                    key={index}
                                    className={`relative h-fit py-9 text-center font-sora font-medium tracking-tight transition-all duration-500
                                        ${activeCategory === categoria.slug ? 'before:bg-primary' : 'border-primary/25 hover:before:bg-primary/50'}
                                        before:absolute before:-bottom-1 before:left-1/2 before:h-1.5 before:w-[80%] before:-translate-x-1/2 before:transition-all before:duration-300`}
                                >
                                    {categoria.nome}
                                </button>
                            ))}
                        </div>
                    </div>
                </div>
                
                {segmento.categorias.length > 1 && (
                    <div className="min-[601px]:hidden grid w-full grid-cols-1 gap-y-3 pt-2">
                        {segmento.categorias
                            .slice(
                                0,
                                allCategories ? segmento.categorias.length : 3
                            )
                            .map((categoria, index) => (    
                                <button
                                    onClick={() => handleCategoryClick(categoria.slug)}
                                    key={index}
                                    className="relative h-fit border-b-2 border-primary/25 py-3 text-center font-sora font-medium tracking-tight text-primary transition-all duration-500 hover:before:bg-primary/50 before:absolute before:-bottom-1 before:left-1/2 before:h-1.5 before:w-[80%] before:-translate-x-1/2 before:bg-transparent before:transition-all before:duration-300 max-[601px]:py-3 max-[601px]:text-xl"
                                >
                                    {categoria.nome}
                                </button>
                            ))}

                        {segmento.categorias.length > 3 && (
                            <Button
                                className="h-14 text-lg"
                                onClick={handleCategoriesAll}
                            >
                                {allCategories ? "Recolher" : "Ver Mais Categorias +"}
                            </Button>
                        )}
                    </div>
                )}

                <section className={isSticky && segmento.categorias.length > 1  ? 'min-[601px]:mt-52' : ''}>
                    <div className="container max-w-large">
                        <div className="mt-20 space-y-20">
                            {segmento.categorias.map((categoria) => (
                                <div key={categoria.id} id={categoria.slug} className="scroll-mt-24">
                                    <h5 className="font-sora text-xl font-semibold tracking-tight text-secondary">
                                        {categoria.nome}
                                    </h5>
                                    <div className="mt-7 grid grid-cols-4 gap-11 max-[1441px]:grid-cols-3 max-[1025px]:grid-cols-2 max-[601px]:gap-4">
                                        {categoria.produtos.map((produto, index) => (
                                            <article
                                                className="group rounded-[10px]"
                                                key={produto.id}
                                            >
                                                <Link href={route('Produtos.produto', { segmento: segmento.slug, slug: produto.slug })} className="block relative h-[300px] w-full rounded-[10px] bg-[#EDF1F8] max-[601px]:h-[200px] overflow-hidden">
                                                    <img
                                                        src={produto.imagem}
                                                        alt={produto.nome}
                                                        className="absolute top-0 left-0 w-full h-full p-4 max-sm:py-6 object-contain transition-all group-hover:scale-110 group-hover:opacity-80"
                                                    />
                                                </Link>
                                                <div className="w-full pr-6 max-[601px]:pr-0 max-[601px]:min-h-[calc(100%_-_200px)] min-h-[calc(100%_-_300px)] flex flex-col">
                                                    <p className="mt-6 mb-4 font-sora text-lg font-semibold max-sm:leading-tight tracking-tight text-secondary">
                                                        {produto.nome}
                                                    </p>
                                                    <Link
                                                        href={route('Produtos.produto', { segmento: segmento.slug, slug: produto.slug })}
                                                        className="block mt-auto"
                                                    >
                                                        <Button className="h-[46px] w-full max-w-[252px] max-[601px]:max-w-full border-2 border-primary bg-transparent text-md text-primary font-semibold hover:bg-primary hover:text-white max-sm:text-sm">
                                                            {lang('conhecerProduto')}
                                                        </Button>
                                                    </Link>
                                                </div>
                                            </article>
                                        ))}
                                    </div>
                                </div>
                            ))}
                        </div>

                        <Separator className="mt-20 md:mt-32 h-0.5! bg-[#EDF1F8]" />
                    </div>
                </section>
            </div>
            <PartnerForm />
            <Footer />
        </>
    );
}