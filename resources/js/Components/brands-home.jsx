import { Button } from "./ui/button";
import { Separator } from "./ui/separator";

import { Reveal } from "./Reveal";

import { useLang } from "@/hooks/useLang";

export function BrandsHome({ brands }) {
    const lang = useLang();

    return (
        <section className="mt-28 2xl:mt-44 w-full bg-white">
            <div className="container max-w-large">
                <div className="flex w-full flex-col items-center md:px-10">
                    <h3 className="font-sora text-5xl font-light tracking-tight text-secondary max-[601px]:text-center max-[601px]:text-4xl">
                        <strong className="font-bold text-primary">{lang('nossos')}</strong>{" "}
                        {lang('clientes')}
                    </h3>

                    <Separator className="mt-14 max-2xl:mb-16 mb-24 bg-[#B3BAC7] max-[601px]:my-8 max-xl:mt-12" />

                    <div className="flex w-full flex-wrap items-center justify-center max-[769px]:grid max-[769px]:grid-cols-3 max-[769px]:gap-2 max-[601px]:grid-cols-2">
                        {brands.map((brand, index) => (
                            <Reveal className="md:w-1/5 p-4 md:p-8" origin="bottom" delay={index * 0.3} scale={true} key={index}>
                                <div key={index} className="transition-all grayscale hover:grayscale-0">
                                    <img
                                        src={brand.logo}
                                        alt={brand.nome}
                                        loading="lazy"
                                        className="object-contain"
                                    />
                                </div>
                            </Reveal>
                        ))}
                    </div>

                    <a href="#parceria" aria-label={lang('queroSerCliente')} className="inline-flex items-center justify-center cursor-pointer gap-2 whitespace-nowrap rounded-md transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive shadow-xs px-4 py-2 has-[>svg]:px-3 mt-16 2xl::mt-28 h-[60px] w-full max-w-[300px] border-2 border-primary bg-transparent font-sora text-lg font-semibold text-primary hover:bg-primary hover:text-white">
                        {lang('queroSerCliente')}
                    </a>
                </div>
            </div>
        </section>
    );
}
