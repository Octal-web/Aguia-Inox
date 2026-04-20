import { Footer } from "@/components/footer";
import { Header } from "@/components/header";
import { Button } from "@/components/ui/button";
import { usePage } from "@inertiajs/react";

import { ProductVideo } from "@/components/ProductVideo";

import { useLang } from "@/hooks/useLang";

export default function TrabalheConosco() {
    const lang = useLang();
    
    const { conteudos } = usePage().props;

    const getEmbedUrl = (url) => {
        if (url.includes('youtube.com/watch')) {
            const videoId = url.split('v=')[1]?.split('&')[0];
            return `https://www.youtube.com/embed/${videoId}`;
        }
    };

    return (
        <>
            <Header />
            <div className="relative h-[344px] w-full max-[601px]:h-[200px]">
                <img
                    src="/site/img/bg-header-work.png"
                    alt="Imagem Cabçalho"
                    className="h-full w-full object-cover"
                />
                <div className="absolute inset-0 bg-primary bg-linear-to-b from-transparent to-black/50 mix-blend-overlay" />
                <h2 className="absolute bottom-10  left-[220px] text-nowrap max-[1367px]:left-10 font-sora text-7xl font-medium tracking-tight text-white max-[769px]:left-1/2 max-[769px]:-translate-x-1/2 max-[769px]:text-center max-[769px]:text-5xl max-[601px]:text-3xl">
                    {conteudos[0].titulo}
                </h2>
            </div>

            <div className="mx-auto mt-8 w-full max-w-[1560px] px-10 max-[1367px]:px-10 max-[769px]:flex max-[769px]:flex-col max-[769px]:items-center max-[769px]:text-center">
                <div className="max-w-[980px] tracking-tight text-textblack" dangerouslySetInnerHTML={{ __html: conteudos[0].texto }} />
                <a href={conteudos[0].link} target={conteudos[0].nova_aba ? '_blank' : 'self'} className="inline-flex items-center justify-center cursor-pointer gap-2 whitespace-nowrap rounded-md transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive bg-primary text-primary-foreground shadow-xs hover:bg-secondary px-4 py-2 has-[>svg]:px-3 mt-16 h-[60px] w-full max-w-[334px] font-sora text-lg font-semibold">
                    {lang('preenchaCurriculo')}
                </a>
            </div>

            <div className="relative mt-[576px] h-[555px] w-full bg-primary max-[769px]:mt-[350px] max-[769px]:h-[300px] max-[426px]:mt-[250px] max-[426px]:h-[200px]">
                <div className="absolute -top-[400px] left-1/2 flex w-full max-w-[1560px] -translate-x-1/2 flex-col gap-10 px-10 max-[1367px]:px-10 max-[769px]:-top-[250px] max-[769px]:items-center max-[426px]:-top-[180px]">
                    <p className="font-sora text-3xl tracking-tight text-primary max-[769px]:text-center">
                        {lang('depoimentosColaboradores')}
                    </p>
                    <ProductVideo url={getEmbedUrl(conteudos[0].video)} />
                </div>
            </div>
            <Footer margin={false} />
        </>
    );
}
