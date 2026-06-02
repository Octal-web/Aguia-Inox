import { Link, usePage } from '@inertiajs/react';

import { cn } from "@/lib/utils";
import { ChevronUp, Mail, Phone } from "lucide-react";
import { Newsletter } from "./newsletter";
import { Separator } from "./ui/separator";

import { useLang } from "@/hooks/useLang";

export function Footer({ margin = true }) {
    const lang = useLang();

    const { segmentosMenu, dadosGerais } = usePage().props;

    const scrollToTop = () => {
        window.scrollTo({
            top: 0,
        });
    };

    return (
        <footer className={cn("mt-20 sm:mt-32 md:mt-48 w-full", !margin && "mt-0")} id="footer">
            <div className="overflow-hidden relative w-full bg-black">
                <img src="/site/img/news-bg.jpg" alt="Newsletter" className="absolute max-md:hidden left-[55%] -translate-y-[20%] -translate-x-1/2 z-[0]" />
                <div className="absolute inset-0 h-full w-full rounded-[10px] bg-primary opacity-80 mix-blend-overlay z-[0]" />
                <Newsletter />
            </div>
            <Separator className="bg-primary" />
            <div className="bg-custom-gradient w-full pt-14 max-[1025px]:h-fit">
                <div className="container max-w-x-large">
                    <div className="flex h-full w-full justify-between max-[1025px]:flex-col max-[1025px]:items-center max-[1025px]:text-center pb-7">
                        <div className="flex flex-col items-start max-[1025px]:items-center">
                            <img
                                src="/site/img/logowhite.svg"
                                alt="Águia Inox"
                                width={244}
                                height={61}
                            />
                            <p className="mt-16 text-sm tracking-tight text-white/65 transition-all duration-500">
                                Rua Júlio João Zanotto, 1300 <br /> Garibaldina
                                95723-000 | Garibaldi-RS /Brasil
                            </p>
                            <div
                                className="mt-24 flex cursor-pointer items-center gap-1 max-[1025px]:flex-col"
                                onClick={scrollToTop}
                            >
                                <ChevronUp className="text-white/65 hover:text-white/100 transition-all duration-500" />
                                <p className="font-sora text-sm text-white/65 hover:text-white/100 transition-all duration-500 underline">
                                    {lang('voltarAoTopo')}
                                </p>
                            </div>
                        </div>
                        <Separator
                            orientation="vertical"
                            className="h-auto bg-white/25"
                        />
                        <div className="pt-4">
                            <span className="font-sora font-medium text-primary capitalize">
                                {lang('empresa')}
                            </span>
                            <ul className="mt-4 [1025px]space-y-1 max-[1025px]:flex max-[1025px]:flex-wrap max-[1025px]:justify-around max-[1025px]:space-x-1">
                                <li>
                                    <Link
                                        href={`${route('Institucional.index')}#historia`}
                                        aria-label={lang('historia')}
                                        className="text-sm text-white/65 hover:text-white/100 transition-all duration-500"
                                    >
                                        {lang('historia')}
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href={`${route('Institucional.index')}#selos`}
                                        aria-label={lang('selos')}
                                        className="text-sm text-white/65 hover:text-white/100 transition-all duration-500"
                                    >
                                       {lang('selos')}
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href={`${route('Institucional.index')}#valores`}
                                        aria-label={lang('missaoVisaoValores')}
                                        className="text-sm text-white/65 hover:text-white/100 transition-all duration-500"
                                    >
                                        {lang('missaoVisaoValores')}
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href={`${route('Institucional.index')}#diferenciais`}
                                        aria-label={lang('diferenciais')}
                                        className="text-sm text-white/65 hover:text-white/100 transition-all duration-500"
                                    >
                                        {lang('diferenciais')}
                                    </Link>
                                </li>
                            </ul>
                        </div>
                        <Separator
                            orientation="vertical"
                            className="h-auto bg-white/25"
                        />
                        <div className="pt-4">
                            <span className="font-sora font-medium text-primary">
                                {lang('produtos')}
                            </span>
                            <ul className="mt-4 max-[1025px]:flex max-[1025px]:flex-wrap max-[1025px]:justify-center max-[1025px]:gap-x-5 max-[1025px]:gap-y-2 [1025px]:space-y-0.5">
                                {segmentosMenu.map((segmento, index) => (
                                    <li key={index}>
                                        <Link
                                            href={route('Segmentos.segmento', { slug: segmento.slug })}
                                            aria-label={segmento.nome}
                                            className="text-sm text-white/65 hover:text-white/100 transition-all duration-500"
                                        >
                                            {segmento.nome}
                                        </Link>
                                    </li>
                                ))}
                            </ul>
                        </div>
                        <Separator
                            orientation="vertical"
                            className="h-auto bg-white/25"
                        />
                        <div className="flex flex-col [1025px]:space-y-4 pt-4  max-[1025px]:flex-row max-[1025px]:space-x-4 max-[1025px]:flex-wrap max-[1025px]:justify-around">
                            <Link
                                href={route('News.index')}
                                aria-label={lang('news')}
                                className="font-sora font-medium text-primary"
                            >
                                {lang('news')}
                            </Link>
                            <Link
                                href={route('Contato.index')}
                                aria-label={lang('contato')}
                                className="font-sora font-medium text-primary"
                            >
                                {lang('contato')}
                            </Link>
                            <Link
                                href={route('TrabalheConosco.index')}
                                aria-label={lang('trabalheConosco')}
                                className="font-sora font-medium text-primary"
                            >
                                {lang('trabalheConosco')}
                            </Link>
                            <a
                                href="https://portalrh.aguiainox.com.br/portalrh/Account/Login"
                                aria-label={lang('politicaCanalDenuncia')}
                                className="font-sora font-medium text-primary"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                {lang('canalDenuncias')}
                            </a>
                        </div>
                        <Separator
                            orientation="vertical"
                            className="h-auto bg-white/25"
                        />
                        <div className="flex flex-col gap-8 pt-4">
                            <div className="flex flex-col space-y-2 max-[1025px]:items-center">
                                <div className="flex items-center gap-2">
                                    <Mail size={15} className="text-white/60" />
                                    <a
                                        href={`mailto:${dadosGerais.email}`}
                                        aria-label={`Enviar e-mail para ${dadosGerais.email}`}
                                        className="text-sm text-white/65 hover:text-white/100 transition-all duration-500"
                                    >
                                        {dadosGerais.email}
                                    </a>
                                </div>
                                <div className="mt-2 flex items-center gap-2">
                                    <Phone size={15} className="text-white/60" />
                                    <a
                                        href={`tel:${dadosGerais.telefone.replace(/\D/g, '')}`}
                                        aria-label={`Ligar para o telefone ${dadosGerais.telefone}`}
                                        className="text-sm text-white/65 hover:text-white/100 transition-all duration-500"
                                    >
                                        {dadosGerais.telefone}
                                    </a>
                                </div>
                                <div className="flex items-center gap-2">
                                    <Phone size={15} className="text-white/60" />
                                    <a
                                        href={`https://wa.me/${dadosGerais.whatsapp_mkt.replace(/\D/g, '')}`}
                                        aria-label={`Enviar whatsapp para ${dadosGerais.whatsapp_mkt}`}
                                        className="text-sm text-white/65 hover:text-white/100 transition-all duration-500"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        {dadosGerais.whatsapp_mkt}
                                    </a>
                                </div>
                            </div>

                            <div className="flex items-center gap-2 max-[1025px]:flex-col">
                                <p className="text-sm text-white/65 hover:text-white/100 transition-all duration-500">
                                    {lang('nossasRedes')}:
                                </p>
                                <div className="flex items-center gap-2">
                                    {dadosGerais.facebook && (
                                        <a
                                            href={dadosGerais.facebook}
                                            className="flex h-10 w-10 items-center justify-center rounded-full border border-white/65"
                                        >
                                            <img src="/site/img/face.svg" alt="Facebook" />
                                        </a>
                                    )}
                                    {dadosGerais.instagram && (
                                        <a
                                            href={dadosGerais.instagram}
                                            className="flex h-10 w-10 items-center justify-center rounded-full border border-white/65"
                                        >
                                            <img src="/site/img/instagram-icon.svg" alt="Instagram" />
                                        </a>
                                    )}
                                    {dadosGerais.linkedin && (
                                        <a
                                            href={dadosGerais.linkedin}
                                            className="flex h-10 w-10 items-center justify-center rounded-full border border-white/65"
                                        >
                                            <img
                                                src="/site/img/linkedin-icon.svg"
                                                alt="Linkedin"
                                                width={13}
                                                height={13}
                                            />
                                        </a>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="h-[105px] w-full bg-[#031421] max-[1025px]:h-fit max-[1025px]:py-6">
                    <div className="mx-auto flex h-full w-full max-w-[1658px] items-center justify-between px-10  max-[1025px]:flex-col max-[1025px]:gap-6">
                        <p className="font-sora text-sm font-light text-[#B9B9B988] hover:text-[#B9B9B9] transition-all duration-500">
                            © Águia Inox {new Date().getFullYear()}
                        </p>
                        <div className="flex items-center gap-1 font-sora text-sm font-light  max-[1025px]:flex-col max-[1025px]:gap-6">
                            <a
                                href={route('Politicas.privacidade')}
                                aria-label={lang('politicaPrivacidade')}
                                className="relative text-[#B9B9B988] hover:text-[#B9B9B9] transition-all duration-500"
                            >
                                {lang('politicaPrivacidade')}
                            </a>
                            <span className="text-[#B9B9B988]">|</span>
                            <a
                                href={route('Politicas.cookies')}
                                aria-label={lang('politicaCookies')}
                                className="relative text-[#B9B9B988] hover:text-[#B9B9B9] transition-all duration-500"
                            >
                                {lang('politicaCookies')}
                            </a>
                            <span className="text-[#B9B9B988]">|</span>
                            <a
                                href={route('Politicas.canalDenuncia')}
                                aria-label={lang('politicaCanalDenuncia')}
                                className="relative text-[#B9B9B988] hover:text-[#B9B9B9] transition-all duration-500"
                            >
                                {lang('politicaCanalDenuncia')}
                            </a>
                        </div>
                        <div className="flex items-center gap-2 max-[1025px]:flex-col">
                            <p className="font-sora text-sm font-light text-[#B9B9B988] transition-all duration-500">
                                {lang('desenvolvidoPor')}:{" "}
                            </p>
                            <img src={`/site/img/8poroito-logo.png`} alt="8poroito" className="opacity-50" />
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    );
}
