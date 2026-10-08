import { Footer } from "@/Components/footer";
import { Header } from "@/Components/header";
import { Separator } from "@/Components/ui/separator";
import { Link, usePage } from "@inertiajs/react";
import { useLang } from "@/hooks/useLang";

export default function Show() {
    const { post, posts, latestPosts = [] } = usePage().props;
    const lang = useLang(); 
    return (
        <>
            <Header />
            <div className="relative h-[320px] w-full max-[601px]:h-[200px]">
                <img
                    src="/site/img/img-header-news.png"
                    alt="Cabeçalho News"
                    className="h-full w-full object-cover"
                />
                <div className="absolute inset-0 bg-primary mix-blend-overlay" />
            </div>

            <main className="mx-auto w-full max-w-[1314px] px-10">
                <div className="mt-[71px] flex items-center gap-2 md:gap-4 max-[601px]:justify-center">
                    <Link href={route('News.index', { categoria: post.categoria_slug }) + '#posts'} className="font-sora text-sm font-bold tracking-tight text-primary transition-all hover:opacity-80">
                        {post.categoria}
                    </Link>
                    {post.publicado &&(
                        <>
                            <div className="font-sora font-bold text-primary max-sm:text-sm">
                                /
                            </div>
                            <p className="font-sora text-sm font-light tracking-tight text-textblack">
                                <strong className="font-bold text-primary">
                                    Atualizado em
                                </strong>{" "}
                                {post.publicado}
                            </p>
                        </>
                    )}
                </div>
                <h3 className="mt-12 max-w-[725px] font-sora text-5xl font-medium tracking-tight text-primary max-[601px]:text-justify max-[601px]:text-3xl">
                    {post.titulo}
                </h3>
                <p className="mt-16 w-full max-w-[1100px] tracking-tight text-textblack max-[601px]:text-justify">
                    {post.previa}
                </p>
                <img
                    src={post.imagem}
                    alt={post.titulo}
                    className="mt-8 object-cover max-[601px]:h-[300px]"
                />

                <div className={`mt-20 lg:mt-24 grid items-start gap-12 ${latestPosts.length > 0 ? 'lg:grid-cols-[minmax(0,1fr)_320px]' : ''}`}>
                    <article
                        dangerouslySetInnerHTML={{ __html: post.conteudo }}
                        className="min-w-0 overflow-x-auto break-words max-sm:tracking-tight text-justify [&_img]:max-w-full [&_iframe]:max-w-full [&_ul]:list-disc [&_ul]:list-inside"
                    />

                    {latestPosts.length > 0 && (
                        <aside aria-labelledby="latest-news-heading" className="min-w-0">
                            <h2 id="latest-news-heading" className="font-sora text-2xl font-medium tracking-tight text-secondary">
                                {lang('ultimasNoticias')}
                            </h2>
                            <ul className="mt-8 space-y-5">
                                {latestPosts.map((item) => (
                                    <li key={item.id}>
                                        <Link
                                            href={route('News.post', { categoria: item.categoria_slug, slug: item.slug })}
                                            className="group flex items-center gap-5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary"
                                        >
                                            <div className="h-24 w-24 shrink-0 sm:h-[120px] sm:w-[120px] rounded-xl overflow-hidden">
                                                <img
                                                    src={item.imagem}
                                                    alt=""
                                                    loading="lazy"
                                                    className="object-cover w-full h-full [backface-visibility:_hidden] [transform:_translate3d(0,_0,_0)] group-hover:scale-110 transition-transform"
                                                />
                                            </div>
                                            <h3 className="line-clamp-4 min-w-0 break-words text-base font-medium leading-snug tracking-tight text-secondary transition-colors group-hover:text-primary">
                                                {item.titulo}
                                            </h3>
                                        </Link>
                                    </li>
                                ))}
                            </ul>
                        </aside>
                    )}
                </div>

                <Separator className="mt-24 h-0.5 w-full bg-[#EDF1F8]" />

                <div className="mt-10 flex items-center gap-5 max-[601px]:flex-col max-[601px]:text-justify">
                    <p className="font-sora md:text-2xl font-medium tracking-tight text-primary">
                        {lang('compartilhe')}:
                    </p>
                    <div className="flex items-center gap-1">
                        <a
                            href=""
                            className="flex h-8 w-8 items-center justify-center rounded-full border border-black"
                        >
                            <img src="/site/img/linkedin-black.svg" alt="Compartilhar Linkedin" />
                        </a>
                        <a
                            href=""
                            className="flex h-8 w-8 items-center justify-center rounded-full border border-black"
                        >
                            <img src="/site/img/face-black.svg" alt="Compartilhar Facebook" />
                        </a>
                    </div>
                </div>
            </main>

            <div className="mt-28 h-[354px] w-full bg-[#EDF1F8]"></div>

            <div className="mx-auto -mt-64 w-full max-w-[1560px] px-10 max-[1367px]:px-10">
                <h3 className="font-sora text-5xl font-medium tracking-tight text-primary max-[601px]:text-justify max-[601px]:text-3xl">
                    {lang('vejaTambem')}
                </h3>

                <div className="mt-14 grid grid-cols-3 gap-9 max-[769px]:grid-cols-1">
                    {posts.map((item, index) => (
                        <article
                            className="relative overflow-hidden rounded-[10px] flex flex-col"
                            key={index}
                        >
                            <div className="absolute top-3.5 left-3.5 rounded-[10px] bg-primary px-5 py-1 font-sora text-lg font-medium tracking-tight text-white">
                                {item.categoria}
                            </div>

                            <Link
                                href={route('News.post', { categoria: item.categoria_slug, slug: item.slug})}
                            >
                                <img
                                    src={item.imagem}
                                    alt={item.titulo}
                                    className="h-[234px] w-full rounded-[10px] object-cover"
                                />
                            </Link>
                            <div className="w-full md:pr-6 max-[601px]:text-justify">
                                <Link
                                    href={route('News.post', { categoria: item.categoria_slug, slug: item.slug})}
                                    className="transition-all hover:opacity-70"
                                >
                                    <p className="mt-8 block font-sora text-lg md:text-xl max-md:leading-tight line-clamp-3 font-medium tracking-tight text-primary transition-all hover:opacity-70">
                                        {item.titulo}
                                    </p>
                                </Link>
                                <p className="mt-4 mb-6 line-clamp-3 tracking-tight text-textblack">
                                    {item.previa}
                                </p>
                            </div>

                            <Link
                                href={route('News.post', { categoria: item.categoria_slug, slug: item.slug})}
                                className="mt-auto font-sora font-medium text-primary underline transition-all hover:opacity-70"
                            >
                                {lang('continuarLendo')}
                            </Link>
                        </article>
                    ))}
                </div>
            </div>

            <Footer />
        </>
    );
}
