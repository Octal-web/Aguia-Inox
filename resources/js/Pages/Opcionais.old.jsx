import { Footer } from "@/components/footer";
import { Header } from "@/components/header";
import { Button } from "@/components/ui/button";
import { useLang } from "@/hooks/useLang";
import { Head, Link, usePage } from "@inertiajs/react";
import { useEffect } from "react";

export default function Show() {

    const { produto, outrosProdutos } = usePage().props;

    const lang = useLang();

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

            <div className="grid grid-cols-2 gap-[104px] max-[1025px]:pt-20 max-[769px]:grid-cols-1 max-[769px]:gap-20 max-[601px]:px-10">
                <div className="relative h-[945px] bg-[#EDF1F8] max-[769px]:order-2 max-[769px]:h-[400px] max-[601px]:h-[300px] flex items-center justify-center">
                    <img
                        src={produto.imagem}
                        alt=""
                        className=""
                    />
                </div>

                <div className="flex flex-col justify-center max-[1025px]:items-center">
                    <p className="font-sora font-light tracking-tight text-textblack">
                        {produto.categoria}
                    </p>
                    <h2 className="mt-4 max-w-[364px] font-sora text-7xl font-light tracking-tighter text-secondary max-[601px]:text-3xl">
                        {(() => {
                            const words = produto.nome.split(' ');
                            const last = words.pop();
                            const first = words.join(' ');

                            return (
                                <>
                                    {first && `${first} `}
                                    <strong className="font-semibold text-primary">{last}</strong>
                                </>
                            );
                        })()}
                    </h2>
                    <div className="mt-12 max-w-[513px] text-lg font-light tracking-tight text-textblack max-[769px]:text-center max-[601px]:text-base" dangerouslySetInnerHTML={{ __html: produto.descricao }} />
                </div>
            </div>

            <div className="mx-auto mt-32 w-full max-w-[1660px] px-10 max-[601px]:mt-10">
                <div className="w-full gap-20 border-b-2 border-primary/25 px-10 max-[601px]:flex max-[601px]:flex-col max-[601px]:items-center max-[601px]:gap-0">
                    <Button
                        className="translate-y-1 rounded-none border-b-8 border-transparent! bg-transparent p-10 font-sora text-4xl font-light text-primary shadow-none transition-all hover:bg-transparent hover:text-primary max-[601px]:text-2xl border-primary font-bold"
                    >
                        {lang('opcionais')}
                    </Button>
                </div>

                <div className="mt-14 w-full rounded-[10px] border">
                    {produto.opcionais.map((opt, index) => (
                        <div
                            id={opt.slug}
                            key={index}
                            className="flex flex-col gap-2.5 scroll-mt-10 py-8 px-6 first:rounded-t-[10px] last:rounded-b-[10px] odd:bg-[#EDF1F8]"
                        >
                            <p className="font-sora text-xl font-light tracking-tight text-secondary max-[601px]:text-base">
                                {opt.titulo}
                            </p>
                            <p>{opt.texto}</p>
                        </div>
                    ))}
                </div>
            </div>

            <div className="mx-auto mt-32 w-full max-w-[1660px] px-10">
                <div className="flex w-full items-end justify-between max-[601px]:flex-col max-[601px]:items-center max-[601px]:gap-3">
                    <h3 className="font-sora text-5xl font-medium tracking-tight text-primary max-[601px]:text-3xl">
                        {lang('vejaTambem')}
                    </h3>
                    <Link
                        href="/produtos"
                        className="font-sora text-lg tracking-tight text-secondary underline"
                    >
                        {lang('voltarProdutos')}
                    </Link>
                </div>

                <div className="mt-10 grid grid-cols-5 gap-4 max-[1025px]:grid-cols-3 max-[1025px]:gap-y-10 max-[601px]:grid-cols-1">
                    {outrosProdutos.slice(0, 5).map((prod, index) => (
                        <article className="w-full rounded-[10px]" key={index}>
                            <Link href={route('Produtos.produto', { segmento: prod.segmento, slug: prod.slug })} className="block relative h-[300px] w-full rounded-[10px] bg-[#EDF1F8]">
                                <img
                                    src={prod.imagem}
                                    alt=""
                                    className="absolute top-0 left-0 h-full w-full object-contain"
                                />
                            </Link>
                            <div className="w-full pr-6">
                                <p className="mt-6 font-sora text-lg font-semibold tracking-tight text-secondary">
                                    {prod.nome}
                                </p>
                                <Link
                                    href={route('Produtos.produto', { segmento: prod.segmento, slug: prod.slug })} 
                                    className="font-sora text-xl font-medium text-primary underline"
                                >
                                    <Button className="mt-4 h-[46px] w-full max-w-[225px] border-2 border-primary bg-transparent text-primary hover:bg-primary hover:text-white">
                                        {lang('conhecerProduto')}
                                    </Button>
                                </Link>
                            </div>
                        </article>
                    ))}
                </div>
            </div>
            <Footer />
        </>
    );
}
