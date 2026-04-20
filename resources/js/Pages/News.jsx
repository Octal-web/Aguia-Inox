import { ExploreCarousel } from "@/components/explore-carousel";
import { Footer } from "@/components/footer";
import { Header } from "@/components/header";
import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";
import { Link, router, usePage } from "@inertiajs/react";
import { useState, useEffect } from "react";
import { ChevronDown, Filter, X } from "lucide-react";

import { NewsList } from '@/Components/NewsList';

import { useLang } from "@/hooks/useLang";

const getInitialCategory = () => {
    const params = new URLSearchParams(window.location.search);
    return params.get('categoria') || 'todos';
};

export default function Index() {
    const lang = useLang();
    
    const { postDestaquePrincipal, postsDestaquesSecundarios, posts: initialPosts, casesClientes, postsCategorias } = usePage().props;
    const [selectedCategory, setSelectedCategory] = useState(getInitialCategory);
    const [posts, setPosts] = useState(initialPosts);
    const [loading, setLoading] = useState(false);
    const [mobileFilterOpen, setMobileFilterOpen] = useState(false);

    useEffect(() => {
        setLoading(false);
    }, []);

    const handlePageChange = (url) => {
        setLoading(true);

        router.visit(url, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['posts'],
            onSuccess: (page) => {
                setPosts(page.props.posts);
                setLoading(false);
            }
        });
    };

    const handleCategoryClick = (category) => {
        setLoading(true);
        setMobileFilterOpen(false);

        const currentParams = new URLSearchParams(window.location.search);

        currentParams.delete('page');
console.log(category)
        if (category === null) {
            currentParams.delete('categoria');
            setSelectedCategory(null);
        } else {
            currentParams.set('categoria', category);
            setSelectedCategory(category);
        }

        const newUrl = window.location.pathname + (currentParams.toString() ? '?' + currentParams.toString() : '');

        router.visit(newUrl, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['posts'],
            onSuccess: (page) => {
                setPosts(page.props.posts);
                setLoading(false);
            }
        });
    };

    const getSelectedCategoryName = () => {
        if (!selectedCategory || selectedCategory === 'todos') {
            return lang('todos');
        }
        const category = postsCategorias.find(cat => cat.slug === selectedCategory);
        return category ? category.nome : lang('todos');
    };

    return (
        <>
            <Header />

            <div className="relative h-[1000px] w-full bg-gradient-to-b from-[#041421] via-[#041522] via-[#022645] to-[#003867] pt-[157px] max-[769px]:h-fit max-[769px]:py-20">
                <div className="absolute inset-0 bg-cover mix-blend-overlay opacity-70" style={{ backgroundImage: 'url(/site/img/bg-news.png)'}} />
                <div className="absolute inset-0 bg-gradient-to-tr from-[#041421] to-transparent opacity-90" />

                {postDestaquePrincipal.length > 0 && (
                    <div className="relative mx-auto grid w-full max-w-[1560px] grid-cols-2 gap-[230px] px-10 max-[1367px]:px-10 max-[769px]:grid-cols-1 max-[769px]:gap-10 max-[769px]:text-justify">
                        <div className="w-full max-[769px]:flex max-[769px]:flex-col max-[769px]:items-center max-[769px]:order-1">
                            <Link
                                href={route('News.post', { categoria: postDestaquePrincipal[0].categoria_slug, slug: postDestaquePrincipal[0].slug})}
                                className="transition-all hover:opacity-70"
                            >
                                <h3 className="max-w-[510px] font-sora text-5xl font-medium tracking-tight text-primary max-[601px]:text-3xl">
                                    {postDestaquePrincipal[0].titulo}
                                </h3>
                            </Link>
                            <p className="mt-7 mb-12 w-full max-w-[450px] tracking-tight text-white">
                                {postDestaquePrincipal[0].previa}
                            </p>
                            <Link
                                href={route('News.post', { categoria: postDestaquePrincipal[0].categoria_slug, slug: postDestaquePrincipal[0].slug})}
                                className="font-sora text-xl font-medium tracking-tight text-primary underline max-[601px]:text-base transition-all hover:opacity-70"
                            >
                                {lang('continuarLendo')}
                            </Link>
                        </div>
                        <div className="relative h-[441px] overflow-hidden rounded-[10px] max-[769px]:h-[300px]">
                            <div className="absolute top-3.5 left-5 z-10 rounded-[10px] bg-primary px-5 py-1 font-sora text-lg font-medium tracking-tight text-white max-[601px]:text-sm">
                                {postDestaquePrincipal[0].categoria}
                            </div>
                            <img
                                src={postDestaquePrincipal[0].imagem}
                                alt={postDestaquePrincipal[0].titulo}
                                className="absolute top-0 left-0 h-full w-full object-cover"
                            />
                        </div>
                    </div>
                )}
            </div>

            {postsDestaquesSecundarios.length > 2 && (
                <div className="relative h-[420px] w-full bg-[#EDF1F8] max-[769px]:h-fit">
                    <div className="max-[769px]: absolute -top-64 left-1/2 mx-auto grid w-full max-w-[1560px] -translate-x-1/2 px-10 max-[1367px]:px-10 max-[769px]:static max-[769px]:top-20 max-[769px]:-translate-x-0 max-[769px]:py-20">
                        <h3 className="font-sora text-5xl font-medium tracking-tight text-primary max-[769px]:text-center max-[601px]:text-3xl">
                            {lang('destaques')}
                        </h3>
                        <div className="mt-14 grid grid-cols-3 gap-9 max-[1025px]:grid-cols-2 max-[769px]:grid-cols-1">
                            {postsDestaquesSecundarios.map((item, index) => (
                                <article
                                    className="relative overflow-hidden rounded-[10px] max-[769px]:text-justify flex flex-col"
                                    key={index}
                                >
                                    <div className="absolute top-3.5 left-3.5 rounded-[10px] bg-primary px-5 py-1 font-sora text-lg font-medium tracking-tight text-white max-[601px]:text-sm">
                                        {item.categoria}
                                    </div>
                                    <Link href={route('News.post', { categoria: item.categoria_slug, slug: item.slug})}>
                                        <img
                                            src={item.imagem}
                                            alt={item.titulo}
                                            className="h-[234px] w-full rounded-[10px] object-cover"
                                        />
                                    </Link>
                                    <div className="w-full pr-6 max-[601px]:text-justify">
                                        <Link
                                            href={route('News.post', { categoria: item.categoria_slug, slug: item.slug})}
                                            className="mt-8 block font-sora text-lg md:text-xl max-md:leading-tight line-clamp-3 font-medium tracking-tight text-primary transition-all hover:opacity-70"
                                        >
                                            {item.titulo}
                                        </Link>
                                        <p className="mt-4 mb-6 line-clamp-3 tracking-tight text-textblack">
                                            {item.previa}
                                        </p>
                                    </div>
                                    
                                    <Link
                                        href={route('News.post', { categoria: item.categoria_slug, slug: item.slug})}
                                        className="mt-auto font-sora font-medium text-primary underline max-[601px]:text-base transition-all hover:opacity-70"
                                    >
                                        {lang('continuarLendo')}
                                    </Link>
                                </article>
                            ))}
                        </div>
                    </div>
                </div>
            )}

            <div id="posts" className="max-[601px]:hidden mx-auto mt-32 grid w-full max-w-[1560px] grid-cols-6 gap-2 max-[1367px]:px-10 text-center max-[1367px]:grid-cols-5 max-[1281px]:grid-cols-4 max-[1025px]:grid-cols-3 border-b-2 border-primary/25">
                <button
                    type="button"
                    onClick={() => handleCategoryClick(null)}
                    className={`relative h-fit py-9 font-sora text-2xl font-medium tracking-tight text-primary before:absolute before:-bottom-1 before:left-0 before:h-1.5 before:w-full ${
                        !selectedCategory || selectedCategory === 'todos'
                            ? "before:bg-primary"
                            : "before:bg-transparent"
                    }`}
                >
                    {lang('todos')}
                </button>

                {postsCategorias.map((category, index) => (
                    <button
                        type="button"
                        key={index}
                        onClick={() => handleCategoryClick(category.slug)}
                        className={`relative h-fit py-9 text-center font-sora text-2xl font-medium tracking-tight text-primary transition-all duration-500 before:absolute before:-bottom-1 before:left-0 before:h-1.5 before:w-full before:transition-all ${
                            selectedCategory === category.slug
                                ? "before:bg-primary"
                                : "before:bg-transparent hover:before:bg-[#b4dae3]"
                        }`}
                    >
                        {category.nome}
                    </button>
                ))}
            </div>

            <div className="min-[601px]:hidden mx-auto mt-20 w-full max-w-[1560px] px-10">
                <div className="relative">
                    <button
                        type="button"
                        onClick={() => setMobileFilterOpen(!mobileFilterOpen)}
                        className="flex w-full items-center justify-between rounded-lg border-2 border-primary/25 bg-white px-6 py-4 font-sora text-xl font-medium tracking-tight text-primary shadow-sm transition-all hover:border-primary/50"
                    >
                        <div className="flex items-center gap-3">
                            <Filter className="h-5 w-5" />
                            <span>{getSelectedCategoryName()}</span>
                        </div>
                        <ChevronDown 
                            className={`h-5 w-5 transition-transform duration-200 ${
                                mobileFilterOpen ? 'rotate-180' : ''
                            }`} 
                        />
                    </button>

                    {mobileFilterOpen && (
                        <>
                            <div 
                                className="fixed inset-0 z-40 bg-black/20"
                                onClick={() => setMobileFilterOpen(false)}
                            />
                            
                            <div className="absolute top-full left-0 z-50 mt-2 w-full rounded-lg border border-gray-200 bg-white shadow-lg animate-fade-in-down">
                                <div className="max-h-80 overflow-y-auto py-2">
                                    <button
                                        type="button"
                                        onClick={() => handleCategoryClick(null)}
                                        className={`flex w-full items-center px-6 py-3 text-left font-sora text-lg font-medium tracking-tight transition-colors hover:bg-gray-50 ${
                                            !selectedCategory || selectedCategory === 'todos'
                                                ? 'bg-primary/10 text-primary'
                                                : 'text-gray-700'
                                        }`}
                                    >
                                        {lang('todos')}
                                    </button>
                                    
                                    {postsCategorias.map((category, index) => (
                                        <button
                                            type="button"
                                            key={index}
                                            onClick={() => handleCategoryClick(category.slug)}
                                            className={`flex w-full items-center px-6 py-3 text-left font-sora text-lg font-medium tracking-tight transition-colors hover:bg-gray-50 ${
                                                selectedCategory === category.slug
                                                    ? 'bg-primary/10 text-primary'
                                                    : 'text-gray-700'
                                            }`}
                                        >
                                            {category.nome}
                                        </button>
                                    ))}
                                </div>
                            </div>
                        </>
                    )}
                </div>
            </div>

            <NewsList
                posts={posts.data}
                loading={loading}
                links={posts.links}
                totalPages={posts.last_page}
                onPageChange={handlePageChange}
            />

            <Separator className="mx-auto mt-24 mb-16 h-0.5! w-full max-w-[1560px] bg-[#EDF1F8] px-10 max-[1367px]:px-10" />

            <div className="w-full scroll-mt-10" id="cases">
                <p className="mb-16 md:pl-[220px] max-md:text-center font-sora text-5xl font-medium tracking-tight text-primary max-[769px]:text-center max-[601px]:text-3xl">
                    {lang('exploreNossosCases')}
                </p>

                <ExploreCarousel items={casesClientes} />
            </div>

            <Footer />
        </>
    );
}