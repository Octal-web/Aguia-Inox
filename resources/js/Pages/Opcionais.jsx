import { useEffect } from "react";
import { usePage } from "@inertiajs/react";

import { Footer } from "@/Components/footer";
import { Header } from "@/Components/header";

import { useLang } from "@/hooks/useLang";

import { OptionalAccordion } from "@/Components/OptionalAccordion";

export default function Show() {
    const lang = useLang();

    const { opcionaisCategorias } = usePage().props;

    useEffect(() => {
        const hash = window.location.hash;
        if (hash) {
            const el = document.getElementById(hash.replace('#', ''));
            if (el) {
                setTimeout(() => {
                    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        }
    }, []);

    return (
        <>
            <Header />
                <div className="relative h-[344px] w-full max-[601px]:h-[200px]">
                    <img src="/site/img/bg-header-work.png" alt="Cabeçalho" className="h-full w-full object-cover" />
                    
                    <div className="absolute inset-0 bg-primary bg-linear-to-b from-transparent to-black/50 mix-blend-overlay" />
                    
                    <h2 className="absolute bottom-10  left-[220px] text-nowrap max-[1367px]:left-10 font-sora text-7xl font-medium tracking-tight text-white max-[769px]:left-1/2 max-[769px]:-translate-x-1/2 max-[769px]:text-center max-[769px]:text-5xl max-[601px]:text-3xl">{lang('opcionais')}</h2>
                </div>

                <section className="pt-28">
                    <div className="container max-w-large">
                        <div className="grid grid-cols-3 gap-20">
                            {opcionaisCategorias.map((item, index) => (
                                <OptionalAccordion key={index} title={item.nome} slug={item.slug} items={item.opcionais} />
                            ))}
                        </div>
                    </div>
                </section>
            <Footer />
        </>
    );
}