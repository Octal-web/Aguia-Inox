import { Footer } from "@/Components/footer";
import { Gallery } from "@/Components/gallery";
import { Header } from "@/Components/header";
import { Button } from "@/Components/ui/button";
import { cn } from "@/lib/utils";
import { Link, usePage } from "@inertiajs/react";
import { useState } from "react";

import { ProductVideo } from '@/Components/ProductVideo';

import { useLang } from "@/hooks/useLang";
import { FileText, Download } from "lucide-react";

export default function Show() {
    const lang = useLang();

    const { produto, outrosProdutos, conteudos } = usePage().props;
    const [option, setOption] = useState("opcionais");

    const handleOption = (value) => setOption(value);

    const handleDownload = (download) => {
        // Opção 1: Download direto via link
        window.open(download.arquivo, '_blank')
        
        // Opção 2: Download via rota (descomente se preferir)
        // router.get(route('downloads.file', download.id))
    }

    return (
        <>
            <Header />

            <div className="grid grid-cols-2 gap-[104px] max-[1025px]:pt-20 max-[769px]:grid-cols-1 max-[769px]:gap-12 max-[601px]:px-6">
                <div className="relative h-[945px] bg-[#EDF1F8] max-[769px]:order-2 max-[769px]:h-[400px] max-[601px]:h-[300px] flex items-center justify-center">
                    <img
                        src={produto.imagem}
                        alt={produto.nome}
                        className="max-h-full"
                    />
                </div>

                <div className="flex flex-col justify-center max-[1025px]:items-center max-md:order-2 md:py-10">
                    <p className="font-sora font-light tracking-tight text-textblack">
                        {produto.categoria}
                    </p>
                    <h2 className="mt-4 max-w-[390px] font-sora md:text-6xl 2xl:text-7xl font-light tracking-tighter text-secondary text-3xl">
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
                    <div className="mt-12 max-w-[670px] 2xl:text-lg font-light tracking-tight text-textblack max-[769px]:tracking-tighter max-[601px]:text-base" dangerouslySetInnerHTML={{ __html: produto.descricao }} />
                </div>
            </div>
            
            {produto.opcionais.length || produto.downloads.length ? (
                <div className="mx-auto mt-32 w-full max-w-[1660px] px-10 max-[601px]:mt-10">
                    <div className="w-full gap-20 border-b-2 border-primary/25 px-10 max-[601px]:flex max-[601px]:flex-col max-[601px]:items-center max-[601px]:gap-0">
                        <Button
                            onClick={() => handleOption("opcionais")}
                            className={cn(
                                "translate-y-1 rounded-none border-b-8 border-transparent! bg-transparent p-10 font-sora text-4xl font-light text-primary shadow-none transition-all hover:bg-transparent hover:text-primary max-[601px]:text-2xl",
                                option === "opcionais" ? "border-primary font-bold" : "border-transparent"
                            )}
                        >
                            {lang('opcionais')}
                        </Button>
                        {produto.downloads.length > 0 && (
                            <Button
                                onClick={() => handleOption("downloads")}
                                className={cn(
                                    "translate-y-1 rounded-none border-b-8 border-transparent! bg-transparent p-10 font-sora text-4xl font-light text-primary shadow-none transition-all duration-500 hover:bg-transparent hover:text-primary max-[601px]:text-2xl",
                                    option === "downloads" ? "border-primary font-bold" : "border-transparent"
                                )}
                            >
                                {lang('downloads')}
                            </Button>
                        )}
                    </div>

                    {option === "opcionais" && (
                        <div className="mt-14 w-full rounded-[10px] border">
                            {produto.opcionais.map((opt, index) => (
                                <div
                                    key={index}
                                    className="flex items-center gap-9 py-2.5 px-6 first:rounded-t-[10px] last:rounded-b-[10px] odd:bg-[#EDF1F8]"
                                >
                                    <Link
                                        href={`${route('Opcionais.opcional', {
                                            categoria: opt.categoria_slug,
                                        })}#${opt.slug}`}
                                        className={`flex max-h-14 min-w-14 items-center justify-center rounded-full border-2 border-primary bg-white font-sora text-6xl text-primary transition-all duration-500 ease-in hover:bg-primary hover:text-white max-[601px]:hidden ${!opt.categoria_slug ? 'opacity-0 pointer-events-none' : ''}`}
                                    >
                                        +
                                    </Link>

                                    <p className="font-sora text-xl font-light tracking-tight text-secondary max-[601px]:text-base">
                                        {opt.titulo}
                                    </p>
                                </div>
                            ))}
                        </div>
                    )}

                    {(option === "downloads" && produto.downloads.length > 0) && (
                        <div className="mt-14 grid grid-cols-2 lg:grid-cols-5 gap-4">
                            {produto.downloads.map((download) => (
                                <a
                                    key={download.id}
                                    className="bg-white rounded-lg border border-gray-200 overflow-hidden hover:border-primary/30 hover:shadow-md transition-all duration-200 cursor-pointer"
                                    href={route('Produtos.downloadArquivo', {segmento: produto.segmento, slug: produto.slug, id: download.id})}
                                >
                                    <div className="aspect-[2] bg-[#EDF1F8] flex items-center justify-center">
                                        {download.imagem ? (
                                            <img
                                                src={download.imagem}
                                                alt={download.titulo}
                                                className="w-full h-full object-cover"
                                            />
                                        ) : (
                                            <FileText className="w-12 h-12 text-gray-400" />
                                        )}
                                    </div>

                                    <div className="p-3">
                                        <div className="flex items-center justify-between gap-2">
                                            <h3 className="font-sora text-sm font-medium tracking-tight text-secondary line-clamp-2 flex-1">
                                                {download.titulo}
                                            </h3>
                                            <Download className="w-4 h-4 text-primary flex-shrink-0" />
                                        </div>
                                    </div>
                                </a>
                            ))}
                        </div>
                    )}
                </div>
            ) : null}

            {produto.imagens && produto.imagens.length > 0 && (
                <div className="mt-36">
                    <div className="container max-w-large">
                        <h4 className="font-sora text-4xl font-medium tracking-tight text-primary max-[601px]:pr-6 max-[601px]:text-center max-[601px]:text-3xl">
                            {lang('galeriaProjeto')}
                        </h4>

                        <Gallery items={produto.imagens || []} />
                    </div>
                </div>
            )}

            {produto.video && (
                <div className="mt-[600px] h-[564px] w-full bg-secondary max-[1025px]:mt-[400px] max-[1025px]:h-[400px] max-[769px]:mt-20 max-[769px]:h-fit max-[769px]:bg-white">
                    <div className="container max-w-x-large">
                        <div className="relative">
                            <div className="absolute -top-[390px] w-full left-0 right-0 overflow-hidden rounded-[10px] max-[1025px]:-top-[200px]">
                                <ProductVideo url={produto.video} />
                            </div>
                        </div>
                    </div>
                </div>
            )}

            <div className="mx-auto mt-32 w-full max-w-[1660px] px-6 md:px-10">
                <div className="flex w-full items-end justify-between max-[601px]:flex-col max-[601px]:items-center max-[601px]:gap-3">
                    <h3 className="font-sora text-5xl font-medium tracking-tight text-primary max-[601px]:text-3xl">
                        {lang('vejaTambem')}
                    </h3>
                </div>

                <div className="mt-7 grid grid-cols-5 gap-x-5 gap-y-14 max-[1441px]:grid-cols-3 max-[1025px]:grid-cols-2 max-[601px]:gap-x-4 max-[601px]:gap-y-12">
                    {outrosProdutos.slice(0, 5).map((prod, index) => (
                        <article
                            className="group rounded-[10px] flex flex-col"
                            key={prod.id}
                        >
                            <Link href={route('Produtos.produto', { segmento: prod.segmento, slug: prod.slug })}  className="block relative h-[300px] w-full rounded-[10px] bg-[#EDF1F8] max-[601px]:h-[200px] overflow-hidden">
                                <img
                                    src={prod.imagem}
                                    alt={prod.nome}
                                    className="absolute top-0 left-0 p-4 w-full h-full object-contain transition-all group-hover:scale-110 group-hover:opacity-80"
                                />
                            </Link>
                            <div className="w-full pr-6 max-[601px]:pr-0 mt-auto">
                                <h5 className="mt-6 font-sora text-lg font-semibold tracking-tight text-secondary">
                                    {prod.nome}
                                </h5>
                                <Link
                                    href={route('Produtos.produto', { segmento: prod.segmento, slug: prod.slug })} 
                                    className="block mt-4"
                                >
                                    <Button className="mt-auto h-[46px] w-full max-w-[252px] max-[601px]:max-w-full border-2 border-primary bg-transparent text-md text-primary font-semibold hover:bg-primary hover:text-white">
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
