import { Link } from '@inertiajs/react';

import { Reveal } from './Reveal';

import { useLang } from "@/hooks/useLang";

export function AboutPartner({ content }) {
    const lang = useLang();

    console.log(content)

    return (
        <section className="mt-[109px]">
            <div className="container max-w-x-large">
                <div className="mx-auto grid h-fit max-w-[1610px] grid-cols-2 gap-[105px] items-end max-[1025px]:grid-cols-1">
                    <Reveal direction="left" className="2xl:ml-10 flex h-fit w-full flex-col max-[1025px]:items-center max-[1025px]:text-center max-[601px]:order-1">
                        <h3 className="font-sans text-5xl font-medium tracking-tight text-primary max-[769px]:text-4xl max-[601px]:text-3xl">
                            {content.titulo}
                        </h3>
                        <div className="mt-9 max-w-[685px] tracking-tight text-textblack" dangerouslySetInnerHTML={{ __html: content.texto }} />
                        <Link href={content.link} className="inline-flex items-center justify-center rounded-md transition-all mt-[52px] h-[60px] w-full max-w-[300px] border-2 border-primary bg-transparent font-sora text-lg font-semibold text-primary hover:bg-primary hover:text-white">
                            {lang('solicitarOrcamento')}
                        </Link>
                    </Reveal>

                    <div className="relative">
                        <img
                            src={content.imagem}
                            alt={content.titulo}
                            className="z-10 -mt-44 h-[506px] w-full object-cover rounded-[10px] max-[1025px]:-mt-0 max-[1025px]:h-[400px]"
                        />
                        
                        <div className="absolute inset-0 rounded-[10px] bg-primary bg-linear-to-b from-transparent to-black/50 mix-blend-soft-light opacity-75" />
                    </div>
                </div>
            </div>
        </section>
    );
}
