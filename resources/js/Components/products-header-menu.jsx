import { Link, usePage } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { Separator } from "./ui/separator";

import { useLang } from "@/hooks/useLang";

export function ProductsHeaderMenu({ isOpen, setIsOpen }) {
    const { segmentosMenu } = usePage().props;
    const [isVisible, setIsVisible] = useState(false);
    const [shouldRender, setShouldRender] = useState(false);

    const [segmentoSelecionado, setSegmentoSelecionado] = useState(
        segmentosMenu.length > 0 ? segmentosMenu[0] : null
    );

    const lang = useLang();

    useEffect(() => {
        if (isOpen) {
            setShouldRender(true);
            setTimeout(() => setIsVisible(true), 30);
        } else {
            setIsVisible(false);
            setTimeout(() => {
                setShouldRender(false);
                setIsOpen(false);
            }, 300);
        }
    }, [isOpen]);

    if (!shouldRender) return null;

    return (
        <div 
            className={`absolute top-[110px] 2xl:top-[134px] left-0 z-[51] w-full border-t-2 border-b-4 border-primary bg-[#EDF1F8] shadow-lg overflow-hidden transition-all duration-300 ease-in-out ${
                isVisible 
                    ? 'max-h-[560px]' 
                    : 'max-h-0'
            }`}
        >
            <div className="min-h-[525px] 2xl:min-h-[545px] pt-10 pb-8 2xl:pb-11">
                <div className="relative mx-auto flex h-full w-full max-w-[1573px] justify-between px-10">
                    <h1 className="absolute text-3xl text-secondary font-bold">{lang('segmentos')}</h1>
                    <div className="flex h-full w-full max-w-[685px] justify-between">
                        <div className="flex flex-col items-start space-y-3 2xl:space-y-4 pt-14 2xl:pt-16">
                            {segmentosMenu.map((segmento, index) => (
                                <Link
                                    key={index}
                                    href={route('Segmentos.segmento', { slug: segmento.slug })}
                                    className={`relative font-sora text-xl font-light tracking-tight pr-10 group transition-all ${
                                        segmentoSelecionado?.id === segmento.id 
                                            ? 'text-secondary font-bold' 
                                            : 'text-secondary/80 hover:text-secondary/100'
                                    }`}
                                    onMouseEnter={() => setSegmentoSelecionado(segmento)}
                                >
                                    <span className="opacity-0">{segmento.nome}</span>
                                    <span className="absolute inset-0 transition-all group-hover:font-bold">
                                        {segmento.nome}
                                    </span>
                                </Link>
                            ))}
                        </div>

                        <Separator
                            orientation="vertical"
                            className="h-auto w-0.5 bg-[#B3BAC725]"
                        />

                        <div className="flex flex-col items-start space-y-3 2xl:space-y-4 ml-16 pt-14 2xl:pt-16">
                            {segmentoSelecionado?.categorias?.map((categoria, index) => (
                                <Link
                                    key={index}
                                    href={`${route('Segmentos.segmento', { slug: segmentoSelecionado.slug })}#${categoria.slug}`}
                                    className="relative font-sora text-lg font-light tracking-tight text-secondary/70 hover:text-secondary/90 pr-10 group transition-all"
                                >
                                    <span className="opacity-0">{categoria.nome}</span>
                                    <span className="absolute inset-0 transition-all group-hover:font-medium">
                                        {categoria.nome}
                                    </span>
                                </Link>
                            )) || (
                                <span className="text-secondary/50 font-sora text-sm italic">
                                    Nenhuma categoria disponível
                                </span>
                            )}
                        </div>
                    </div>

                    <div className="flex h-full gap-16">
                        <Separator
                            orientation="vertical"
                            className="h-auto w-0.5 bg-[#B3BAC725]"
                        />
                        <div>
                            <div className="flex items-center gap-3">
                                <img src="/site/img/icon-segmento.png" alt="Ícone segmento" />
                                <h3 className="font-sora text-xl font-medium tracking-tight text-primary">
                                    Sobre esse segmento:
                                </h3>
                            </div>
                            <div className="mt-6 max-w-[358px] text-[14px] tracking-tight text-secondary">
                                {segmentoSelecionado?.descricao && (
                                    <div 
                                        dangerouslySetInnerHTML={{ 
                                            __html: segmentoSelecionado.descricao 
                                        }} 
                                    />
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}