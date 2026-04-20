import { Link, usePage } from "@inertiajs/react";

import { Footer } from "@/components/footer";
import { Header } from "@/components/header";

import { useLang } from "@/hooks/useLang";

import { OptionalItem } from "@/Components/OptionalItem";

export default function Show() {
    const lang = useLang();

    const { opcional } = usePage().props;

    return (
        <>
            <Header />
                <div className="relative h-[344px] w-full max-[601px]:h-[200px]">
                    <img src="/site/img/bg-header-work.png" alt={opcional.titulo} className="h-full w-full object-cover" />
                    
                    <div className="absolute inset-0 bg-primary bg-linear-to-b from-transparent to-black/50 mix-blend-overlay" />
                    
                    <h2 className="absolute bottom-10  left-[220px] text-nowrap max-[1367px]:left-10 font-sora text-7xl font-medium tracking-tight text-white max-[769px]:left-1/2 max-[769px]:-translate-x-1/2 max-[769px]:text-center max-[769px]:text-5xl max-[601px]:text-3xl">{lang('opcionais')}</h2>
                </div>

                <section className="pt-10">
                    <div className="container max-w-large">
                        <div className="flex">
                            <h4 className="text-secondary text-sm font-bold">{lang('opcionais')} / <Link href={route('Opcionais.index') + '#' + opcional.categoria_slug}>{opcional.categoria}</Link> / {opcional.titulo}</h4>
                        </div>
                    </div>
                </section>
                
                {opcional.modelos.map((item, index) => (
                    <OptionalItem key={index} item={item} />
                ))}
            <Footer />
        </>
    );
}