import { Footer } from "@/components/footer";
import { Header } from "@/components/header";
import { PartnerForm } from "@/components/partner-form";
import { Link, usePage } from '@inertiajs/react';

import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";

import { useLang } from "@/hooks/useLang";

export default function Index() {
    const lang = useLang();

    const { produtos, q } = usePage().props;

    return (
        <>
            <Header />
            <section>
                <div className="relative h-[300px] w-full max-[601px]:h-[200px]">
                    <img
                        src="/site/img/bg-produtos.png"
                        alt="Resultados dos Produtos"
                        className="h-full w-full object-cover"
                    />
                    <div className="absolute inset-0 bg-[#142B3E] mix-blend-overlay" />
                    <div className="absolute inset-0 bg-linear-to-r from-[#061521] to-[#00000000]" />

                    <div className="absolute top-1/2 left-0 w-full -translate-y-1/2 max-[1025px]:left-1/2 max-[1025px]:-translate-x-1/2 max-[1025px]:px-10 max-[1025px]:pl-6 max-[769px]:text-center">
                        <div className="container max-w-large">
                            <h3 className="font-sora text-5xl font-semibold tracking-tight text-white max-[769px]:text-3xl">
                                {`${lang('resultadosPesquisa')} "${q}"`}
                            </h3>
                        </div>
                    </div>
                </div>
            </section>

            <section className="mt-20 md:mt-36">
                <div className="container max-w-large">
                    <div className="mt-7 grid grid-cols-4 gap-x-11 gap-y-14 max-[1441px]:grid-cols-3 max-[1025px]:grid-cols-2 max-[601px]:gap-x-4 max-[601px]:gap-y-12">
                        {produtos.length ? (
                            produtos.map((produto, index) => (
                                <article
                                    className="group rounded-[10px] flex flex-col"
                                    key={produto.id}
                                >
                                    <Link href={route('Produtos.produto', { segmento: produto.segmento_slug, slug: produto.slug })} className="block relative h-[300px] w-full rounded-[10px] bg-[#EDF1F8] max-[601px]:h-[200px] overflow-hidden">
                                        <img
                                            src={produto.imagem}
                                            alt={produto.nome}
                                            className="absolute top-0 left-0 w-full h-full object-contain max-[601px]:object-contain transition-all group-hover:scale-110 group-hover:opacity-80"
                                        />
                                    </Link>
                                    <div className="w-full pr-6 max-[601px]:pr-0 mt-auto">
                                        <p className="mt-6 font-sora text-lg font-semibold tracking-tight text-secondary">
                                            {produto.nome}
                                        </p>
                                        <Link
                                            href={route('Produtos.produto', { segmento: produto.segmento_slug, slug: produto.slug })}
                                            className="block mt-4"
                                        >
                                            <Button className="mt-auto h-[46px] w-full max-w-[252px] max-[601px]:max-w-full border-2 border-primary bg-transparent text-md text-primary font-semibold hover:bg-primary hover:text-white">
                                                {lang('conhecerProduto')}
                                            </Button>
                                        </Link>
                                    </div>
                                </article>
                            ))
                        ) : (
                            <h3 className="text-primary text-3xl text-center my-20 col-span-4 max-[1441px]:col-span-3 max-[1025px]:col-span-2">{lang('filtrosNaoEncontrados')}</h3>
                        )}
                    </div>

                    <Separator className="mt-32 h-0.5! bg-[#EDF1F8]" />
                </div>
            </section>
            <PartnerForm />
            <Footer />
        </>
    );
}