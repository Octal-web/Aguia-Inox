import React, { useEffect, useRef } from 'react';
import { Link } from '@inertiajs/react';
import { useLang } from '@/hooks/useLang';

import PostsPagination from './PostsPagination';

export const NewsList = ({ posts, loading, links, totalPages, onPageChange }) => {
    const lang = useLang();
    return (
        <section className="pt-24" id="posts">
            <div className="container max-w-large">
                {posts && posts.length ? (
                    <div className={`grid sm:grid-cols-2 md:grid-cols-3 gap-x-3 gap-y-12 xl:gap-x-10 xl:gap-y-16 mb-15 sm:mb-30${loading ? ' opacity-50' : ''}`}>
                        {posts.map((item, index) => (
                            <article
                                className="relative overflow-hidden rounded-[10px] flex flex-col"
                                key={index}
                            >
                                <div className="absolute top-3.5 left-3.5 rounded-[10px] bg-primary px-5 py-1 font-sora text-lg font-medium tracking-tight text-white">
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
                                    className="mt-auto font-sora font-medium text-primary underline transition-all hover:opacity-70"
                                >
                                     {lang('continuarLendo')}
                                </Link>
                            </article>
                        ))}
                    </div>
                ) : (
                    <h3 className="font-eng-secondary text-3xl text-center my-20">{lang('filtrosNaoEncontrados')}</h3>
                )}
            </div>

            {posts && posts.length ? (
                <PostsPagination 
                    links={links} 
                    totalPages={totalPages}
                    onPageChange={onPageChange}
                />
            ) : null}
        </section>
    );
};