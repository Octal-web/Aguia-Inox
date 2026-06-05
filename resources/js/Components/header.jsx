import { ChevronDown, Menu, X } from "lucide-react";
import { useEffect, useRef, useState } from "react";
import { usePage, Link, Head } from '@inertiajs/react';
import { ScrollArea } from "@/Components/ui/scroll-area";

import Lenis from 'lenis';

import { ProductsHeaderMenu } from "./products-header-menu";
import { Search } from "./search";
import { CookieModal } from "./CookieModal";
import { LanguageSwitcher } from '../Components/LanguageSwitcher';

import { Button } from "./ui/button";

import { useLang } from "@/hooks/useLang";

export function Header() {
    const lang = useLang();
    
    const { controller, action, pagina, notifyCookie, rejectCookie, segmentosMenu, postsCategoriasMenu, dadosGerais } = usePage().props;
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const [isProductsMenuOpen, setIsProductsMenuOpen] = useState(false);
    const [isSearchOpen, setIsSearchOpen] = useState(false);
    const [isMobileProductsOpen, setIsMobileProductsOpen] = useState(false);
    const [trackingEnabled, setTrackingEnabled] = useState(false); 
    const productsMenuRef = useRef(null);
    const searchDesktopRef = useRef(null);
    const searchMobileRef = useRef(null);
    const searchToggleDesktopRef = useRef(null);
    const searchToggleMobileRef = useRef(null);
    const lenisRef = useRef(null);

    useEffect(() => {
        lenisRef.current = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            direction: 'vertical',
            smooth: true,
            smoothTouch: false,
        });

        function raf(time) {
            lenisRef.current.raf(time);
            requestAnimationFrame(raf);
        }

        requestAnimationFrame(raf);

        return () => {
            lenisRef.current.destroy();
        };
    }, []);

    const toggleMobileMenu = () => {
        setIsMobileMenuOpen(!isMobileMenuOpen);
    };

    const toggleProductsMenu = () => {
        setIsProductsMenuOpen(!isProductsMenuOpen);
    };

    const toggleSearch = (e) => {
        e.stopPropagation();
        if (isProductsMenuOpen) {
            setIsProductsMenuOpen(!isProductsMenuOpen);
        }
        if (isMobileMenuOpen) {
            setIsMobileMenuOpen(!isMobileMenuOpen);
        }
        setIsSearchOpen(!isSearchOpen);
    };

    useEffect(() => {
        if (isMobileMenuOpen) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = "unset";
        }

        return () => {
            document.body.style.overflow = "unset";
        };
    }, [isMobileMenuOpen]);

    useEffect(() => {
        function handleClickOutside(event) {
            if (
                productsMenuRef.current &&
                !productsMenuRef.current.contains(event.target)
            ) {
                setIsProductsMenuOpen(false);
            }
        }

        document.addEventListener("mousedown", handleClickOutside);
        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, [productsMenuRef]);

    useEffect(() => {
        function handleClickOutside(event) {
            if (
                (searchToggleDesktopRef.current &&
                    searchToggleDesktopRef.current.contains(event.target)) ||
                (searchToggleMobileRef.current &&
                    searchToggleMobileRef.current.contains(event.target))
            ) {
                return;
            }

            if (
                (searchDesktopRef.current &&
                    searchDesktopRef.current.contains(event.target)) ||
                (searchMobileRef.current &&
                    searchMobileRef.current.contains(event.target))
            ) {
                return;
            }

            setIsSearchOpen(false);
        }

        if (isSearchOpen) {
            document.addEventListener("mousedown", handleClickOutside);
            return () => {
                document.removeEventListener("mousedown", handleClickOutside);
            };
        }
    }, [isSearchOpen]);

    const acceptCookies = () => {
        setTrackingEnabled(true);
    };

    useEffect(() => {
        const timer = setTimeout(() => {
            if (notifyCookie || trackingEnabled) {
                // evita duplicação
                if (document.getElementById('tracking-scripts')) return;

                const container = document.createElement('div');
                container.id = 'tracking-scripts';

                // ===== GTM =====
                const gtmScript = document.createElement('script');
                gtmScript.innerHTML = `
                    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                    })(window,document,'script','dataLayer','GTM-WFF39Q5W');
                `;
                container.appendChild(gtmScript);

                const gtmNoscript = document.createElement('noscript');
                gtmNoscript.innerHTML = `
                    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WFF39Q5W"
                    height="0" width="0" style="display:none;visibility:hidden"></iframe>
                `;
                container.appendChild(gtmNoscript);

                // ===== META =====
                if (!document.querySelector('meta[name="facebook-domain-verification"]')) {
                    const meta = document.createElement('meta');
                    meta.name = 'facebook-domain-verification';
                    meta.content = 'agtpvwwr9a1d8qgyh9htrp013y0r0a';
                    document.head.appendChild(meta);
                }

                // ===== LINKEDIN =====
                const footer = document.getElementById('footer');
                if (!footer) return;

                const linkedinInit = document.createElement('script');
                linkedinInit.innerHTML = `
                    _linkedin_partner_id = "9761705";
                    window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
                    window._linkedin_data_partner_ids.push(_linkedin_partner_id);
                `;
                container.appendChild(linkedinInit);

                const linkedinScript = document.createElement('script');
                linkedinScript.type = 'text/javascript';
                linkedinScript.async = true;
                linkedinScript.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
                container.appendChild(linkedinScript);

                const linkedinNoscript = document.createElement('noscript');
                linkedinNoscript.innerHTML = `
                    <img height="1" width="1" style="display:none;" alt=""
                    src="https://px.ads.linkedin.com/collect/?pid=9761705&fmt=gif" />
                `;
                container.appendChild(linkedinNoscript);

                footer.appendChild(container);
            }
        }, 100);

        return () => clearTimeout(timer);
    }, [notifyCookie, trackingEnabled]);

    return (
        <>
            <Head>
                <title>{pagina.titulo}</title>
                <meta name="description" content={pagina.descricao} />

                <meta name="twitter:card" content="summary"/>

                <meta property="og:url" content={window.location.pathname} />
                <meta property="og:type" content="website"/>
                <meta property="og:title" content={pagina.tituloCompartilhamento} />
                <meta property="og:description" content={pagina.descricaoCompartilhamento} />
                <meta property="og:image" content={pagina.imagem.endereco} />
                <meta property="og:image:type" content={pagina.imagem.tipo} />
                <meta property="og:image:width" content={pagina.imagem.largura} />
                <meta property="og:image:height" content={pagina.imagem.altura} />

                <meta name="robots" content="index, follow"/>
                <meta name="author" content="Octal Web" />

                <link rel="icon" href={`/favicon.ico`} type="image/x-icon" />
                <link rel="canonical" href={window.location.href.split('?')[0]} />
            </Head>
            
            {!notifyCookie && (
                <CookieModal acceptCookies={acceptCookies} visible={!notifyCookie} />
            )}

            <header className="bg-white border-b-4 border-primary">
                <div className="container max-w-x-large">
                    <div className="flex h-[108px] 2xl:h-[130px] w-full items-center justify-between max-[601px]:h-[80px] max-[601px]:px-4">
                        <a href="/">
                            <img
                                src="/site/img/logo.svg"
                                alt="Águia Inox"
                                className="h-[62px] w-[244px] max-[1397px]:w-[180px] max-[601px]:w-[130px]"
                            />
                        </a>

                        <div className="hidden items-center min-[1280px]:flex">
                            <nav>
                                <ul className="flex items-center space-x-4 2xl:space-x-10">
                                    <li className="relative group">
                                        <div className="flex cursor-pointer items-center gap-1 xl:gap-0 2xl:gap-2">
                                            <Link
                                                href={route('Institucional.index')}
                                                className="relative font-sora font-normal text-secondary text-opacity-0 capitalize after:content-[attr(data-after)] after:absolute after:left-1/2 after:-translate-x-1/2 after:text-secondary after:text-opacity-100 group-hover:after:font-bold after:whitespace-nowrap after:transition-all"
                                                data-after={lang('empresa')}
                                            >
                                                {lang('empresa')}
                                            </Link>
                                        
                                            <ChevronDown
                                                size={20}
                                                className="text-primary"
                                            />
                                        </div>

                                        <div className="absolute left-1/2 -translate-x-1/2 bg-white shadow-lg rounded-md p-4 mt-2 z-50 min-w-[190px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                            <ul className="space-y-2">
                                                <li>
                                                    <Link
                                                        href={`${route('Institucional.index')}#historia`}
                                                        className="text-sm transition-all duration-500"
                                                    >
                                                        {lang('historia')}
                                                    </Link>
                                                </li>
                                                <li>
                                                    <Link
                                                        href={`${route('Institucional.index')}#selos`}
                                                        className="text-sm transition-all duration-500"
                                                    >
                                                    {lang('selos')}
                                                    </Link>
                                                </li>
                                                <li>
                                                    <Link
                                                        href={`${route('Institucional.index')}#valores`}
                                                        className="text-sm transition-all duration-500"
                                                    >
                                                        {lang('missaoVisaoValores')}
                                                    </Link>
                                                </li>
                                                <li>
                                                    <Link
                                                        href={`${route('Institucional.index')}#diferenciais`}
                                                        className="text-sm transition-all duration-500"
                                                    >
                                                        {lang('diferenciais')}
                                                    </Link>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li ref={productsMenuRef}>
                                        <button
                                            onClick={toggleProductsMenu}
                                            className="group flex cursor-pointer items-center gap-1 xl:gap-0 2xl:gap-2 font-sora text-secondary transition-all"
                                        >
                                            <span
                                                className="relative font-sora font-normal text-secondary text-opacity-0 xl:tracking-tight 2xl:tracking-normal capitalize after:content-[attr(data-after)] after:absolute after:left-1/2 after:-translate-x-1/2 after:text-secondary after:text-opacity-100 group-hover:after:font-bold after:whitespace-nowrap after:transition-all"
                                                data-after={lang('produtos')}
                                            >
                                                {lang('produtos')}
                                            </span>
                                            <ChevronDown
                                                size={20}
                                                className="text-primary"
                                            />
                                        </button>
                                        <ProductsHeaderMenu isOpen={isProductsMenuOpen} setIsOpen={setIsProductsMenuOpen} />
                                    </li>
                                    <li className="relative group">
                                        <div className="flex cursor-pointer items-center gap-1 2xl:gap-2">
                                            <Link
                                                href={route('News.index')}
                                                className="relative font-sora font-normal text-secondary text-opacity-0 xl:tracking-tight 2xl:tracking-normal capitalize after:content-[attr(data-after)] after:absolute after:left-1/2 after:-translate-x-1/2 after:text-secondary after:text-opacity-100 group-hover:after:font-bold after:whitespace-nowrap after:transition-all"
                                                data-after={lang('news')}
                                            >
                                                {lang('news')}
                                            </Link>
                                        
                                            <ChevronDown
                                                size={20}
                                                className="text-primary"
                                            />
                                        </div>

                                        <div className="absolute left-1/2 -translate-x-1/2 bg-white shadow-lg rounded-md p-4 mt-2 z-50 min-w-[190px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                            <ul className="space-y-2">
                                                {postsCategoriasMenu.map((item, index) => (
                                                    <li key={index}>
                                                        <Link
                                                            href={route('News.index', { categoria: item.slug }) + '#posts'} 
                                                            className="text-sm transition-all duration-500"
                                                        >
                                                            {item.nome}
                                                        </Link>
                                                    </li>
                                                ))}
                                            </ul>
                                        </div>
                                    </li>
                                    <li className="relative group">
                                        <Link
                                            href={route('Contato.index')}
                                            className="font-sora font-normal text-secondary text-opacity-0 xl:tracking-tight 2xl:tracking-normal capitalize after:content-[attr(data-after)] after:absolute after:left-1/2 after:-translate-x-1/2 after:text-secondary after:text-opacity-100 group-hover:after:font-bold after:whitespace-nowrap after:transition-all"
                                            data-after={lang('contato')}
                                        >
                                            {lang('contato')}
                                        </Link>
                                    </li>
                                    <li className="relative group">
                                        <Link
                                            href={route('TrabalheConosco.index')}
                                            className="font-sora font-normal text-secondary text-opacity-0 xl:tracking-tight 2xl:tracking-normal  after:content-[attr(data-after)] after:absolute after:left-1/2 after:-translate-x-1/2 after:text-secondary after:text-opacity-100 group-hover:after:font-bold after:whitespace-nowrap after:transition-all"
                                            data-after={lang('trabalheConosco')}
                                        >
                                            {lang('trabalheConosco')}
                                        </Link>
                                    </li>
                                </ul>
                            </nav>

                            <div ref={searchDesktopRef}>
                                <button
                                    ref={searchToggleDesktopRef}
                                    className="ml-6 2xl:ml-14 cursor-pointer"
                                    onClick={toggleSearch}
                                >
                                    <img src="/site/img/icon-search.png" alt="Pesquisar" />
                                </button>
                                {isSearchOpen && <Search />}
                            </div>

                            <div className="ml-4 2xl:ml-6 flex items-center gap-3 2xl:gap-4">
                                <Link
                                    aria-label={lang('soliciteOrcamento')}
                                    href={route('Home.index') + '#parceria'}
                                    className="inline-flex items-center justify-center cursor-pointer gap-2 whitespace-nowrap rounded-md transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive bg-primary text-primary-foreground shadow-xs hover:bg-secondary px-4 py-2 has-[>svg]:px-3 h-[54px] w-[273px] font-sora 2xl:text-lg font-semibold max-[1367px]:w-[230px]"
                                >
                                    {lang('soliciteOrcamento')}
                                </Link>

                                <a
                                    aria-label="Chamar no whatsapp"
                                    href="https://wa.me/5554991519032"
                                    target="_blank"
                                    className="group flex h-[54px] w-[54px] items-center justify-center rounded-[10px] border-2 border-primary transition-all hover:border-secondary hover:bg-secondary"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 448 512"
                                        className="h-8 w-8 fill-primary transition-all group-hover:fill-white"
                                    >
                                        <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157m-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1s56.2 81.2 56.1 130.5c0 101.8-84.9 184.6-186.6 184.6m101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8s-14.3 18-17.6 21.8c-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7s-12.5-30.1-17.1-41.2c-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2s-9.7 1.4-14.8 6.9c-5.1 5.6-19.4 19-19.4 46.3s19.9 53.7 22.6 57.4c2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4s4.6-24.1 3.2-26.4c-1.3-2.5-5-3.9-10.5-6.6"></path>
                                    </svg>
                                </a>
                            </div>
                            
                            <LanguageSwitcher />
                        </div>

                        <div className="flex items-center gap-5 min-[1280px]:hidden">
                            <div ref={searchMobileRef}>
                                <button
                                    ref={searchToggleMobileRef}
                                    className="mt-2 cursor-pointer"
                                    onClick={toggleSearch}
                                >
                                    <img src="/site/img/icon-search.png" alt="Pesquisar" />
                                </button>
                                {isSearchOpen && <Search />}
                            </div>
                            <button
                                onClick={toggleMobileMenu}
                                className="p-2 -mr-2"
                                aria-label="Abrir menu"
                            >
                                {isMobileMenuOpen ? (
                                    <X size={32} className="text-secondary" />
                                ) : (
                                    <Menu size={32} className="text-secondary" />
                                )}
                            </button>
                        </div>
                    </div>
                </div>

                <div 
                    className={`overflow-hidden border-t border-gray-200 bg-white shadow-lg transition-all duration-300 ease-in-out min-[1280px]:hidden ${
                        isMobileMenuOpen 
                            ? 'max-h-[600px] opacity-100' 
                            : 'max-h-0 opacity-0 border-t-0'
                    }`}
                >
                    <div className={`px-6 py-4 transition-transform duration-300 ${
                        isMobileMenuOpen ? 'translate-y-0' : '-translate-y-4'
                    }`}>
                        <nav className="mb-6">
                            <ul className="space-y-4">
                                <li className={`transition-all duration-300 delay-75 ${
                                    isMobileMenuOpen ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-4'
                                }`}>
                                    <Link
                                        href={route('Institucional.index')}
                                        className="block py-2 font-sora text-secondary transition-all hover:font-bold"
                                        onClick={() =>
                                            setIsMobileMenuOpen(false)
                                        }
                                    >
                                        {lang('empresa')}
                                    </Link>
                                </li>
                                
                                <li className={`transition-all duration-300 delay-100 ${
                                    isMobileMenuOpen ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-4'
                                }`}>
                                    <button
                                        type="button"
                                        className="flex w-full items-center justify-between py-2 font-sora text-secondary transition-all duration-500 hover:font-bold"
                                        onClick={() =>
                                            setIsMobileProductsOpen((v) => !v)
                                        }
                                    >
                                        {lang('produtos')}
                                        <ChevronDown
                                            size={20}
                                            className={`ml-2 transition-transform ${
                                                isMobileProductsOpen
                                                    ? "rotate-180"
                                                    : ""
                                            }`}
                                        />
                                    </button>
                                    {isMobileProductsOpen && (
                                        <ScrollArea className="ml-4 mt-2 h-32 space-y-2 border-l border-gray-200 pl-4">
                                            <ul>
                                                {segmentosMenu.map((segmento, index) => (
                                                        <li key={index}>
                                                            <Link
                                                                href={route('Segmentos.segmento', { slug: segmento.slug })}
                                                                className="block py-1 text-sm text-secondary"
                                                                onClick={() =>
                                                                    setIsMobileMenuOpen(
                                                                        false
                                                                    )
                                                                }
                                                            >
                                                                {segmento.nome}
                                                            </Link>
                                                        </li>
                                                    )
                                                )}
                                            </ul>
                                        </ScrollArea>
                                    )}
                                </li>

                                <li className={`transition-all duration-300 delay-150 ${
                                    isMobileMenuOpen ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-4'
                                }`}>
                                    <Link
                                    href={route('News.index')}
                                        className="block py-2 font-sora text-secondary transition-all hover:font-bold"
                                        onClick={() =>
                                            setIsMobileMenuOpen(false)
                                        }
                                    >
                                        {lang('news')}
                                    </Link>
                                </li>
                                <li className={`transition-all duration-300 delay-200 ${
                                    isMobileMenuOpen ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-4'
                                }`}>
                                    <Link
                                        href={route('Contato.index')}
                                        className="block py-2 font-sora text-secondary transition-all hover:font-bold"
                                        onClick={() =>
                                            setIsMobileMenuOpen(false)
                                        }
                                    >
                                        {lang('contato')}
                                    </Link>
                                </li>
                                <li className={`transition-all duration-300 delay-[250ms] ${
                                    isMobileMenuOpen ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-4'
                                }`}>
                                    <Link
                                        href={route('TrabalheConosco.index')}
                                        className="block py-2 font-sora text-secondary transition-all hover:font-bold"
                                        onClick={() =>
                                            setIsMobileMenuOpen(false)
                                        }
                                    >
                                        {lang('trabalheConosco')}
                                    </Link>
                                </li>
                            </ul>
                        </nav>

                        <div className={`flex items-center pb-4 transition-all duration-300 delay-300 ${
                            isMobileMenuOpen ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'
                        }`}>
                            <div className="flex justify-center mr-4">
                                <a href={`https://wa.me/${dadosGerais.whatsapp_mkt.replace(/\D/g, '')}`} target="_blank" rel="noopener noreferrer">
                                    <img
                                        src="/site/img/whats-button.svg"
                                        alt="Whatsapp"
                                        width={54}
                                        height={54}
                                    />
                                </a>
                            </div>

                            <Button
                                className="h-[50px] w-full"
                                variant={"default"}
                            >
                                {lang('soliciteOrcamento')}
                            </Button>
                            
                            <LanguageSwitcher />
                        </div>
                    </div>
                </div>
            </header>
            
            <h1 className="sr-only">{pagina.descricao}</h1>
        </>
    );
}