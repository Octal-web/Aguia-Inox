import { ProductVideo } from "@/Components/ProductVideo";

export function AboutFrontier({ content }) {
    
    const getEmbedUrl = (url) => {
        if (url.includes('youtube.com/watch')) {
            const videoId = url.split('v=')[1]?.split('&')[0];
            return `https://www.youtube.com/embed/${videoId}`;
        }
    };

    return (
        <section className="relative w-full mt-32 pb-32 sm:pb-48 max-[769px]:bg-white before:absolute before:left-0 before:right-0 before:bottom-0 before:h-1/3 sm:before:h-1/2 before:bg-primary">
            <div className="container max-w-x-large">
                <div className="flex w-full flex-col gap-20 max-[1025px]:gap-10 max-[769px]:-translate-x-0 max-[769px]:items-center border-t pt-28">
                    <div className="grid grid-cols-1 md:grid-cols-2 max-sm:gap-6">
                        {content.titulo && (() => {
                            const words = content.titulo.split(' ');
                            const firstTwo = words.slice(0, 2).join(' ');
                            const rest = words.slice(2).join(' ');

                            return (
                                <h3 className="font-sora text-5xl tracking-tight text-textblack max-[769px]:text-4xl max-[601px]:text-3xl">
                                    {firstTwo} <br />
                                    <span className="font-medium text-primary">
                                        {rest ? ' ' + rest : ''}
                                    </span>
                                </h3>
                            );
                        })()}
                        <div className="max-w-[633px] tracking-tight text-textblack" dangerouslySetInnerHTML={{ __html: content.texto }} />
                    </div>

                    <div className="relative group">
                        <ProductVideo url={getEmbedUrl(content.video)} />
                    </div>
                </div>
            </div>
        </section>
    );
}
