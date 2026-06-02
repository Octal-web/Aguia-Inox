import React from 'react';
import { router } from '@inertiajs/react';

const CasesPagination = ({ links, totalPages, onPageChange }) => {
    const activeLink = links.find(link => link.active);
    const currentPage = activeLink 
        ? parseInt(activeLink.label) 
        : parseInt(new URLSearchParams(window.location.search).get('page')) || 1;

    const previousLink = links.find(link => link.label.toLowerCase() === '&laquo; previous');
    const nextLink = links.find(link => link.label.toLowerCase() === 'next &raquo;');

    const handlePageChange = (pageNumber) => {
        const currentParams = new URLSearchParams(window.location.search);
        currentParams.set('page', pageNumber);

        const newUrl = window.location.pathname + '?' + currentParams.toString();

        onPageChange(newUrl);
    };

    const renderPageLinks = () => {
        const pagesToShow = [];

        if (currentPage > 3) {
            pagesToShow.push(1);

            if (currentPage > 4) {
                pagesToShow.push('...');
            }
        }

        const startPage = Math.max(1, currentPage - 1);
        const endPage = Math.min(totalPages, currentPage + 1);

        for (let i = startPage; i <= endPage; i++) {
            if (!pagesToShow.includes(i)) {
                pagesToShow.push(i);
            }
        }

        if (currentPage < totalPages - 2) {
            if (currentPage < totalPages - 3) {
                pagesToShow.push('...');
            }
            pagesToShow.push(totalPages);
        }

        return pagesToShow;
    };

    return (
        <div className="relative container max-w-large">
            <div className="mt-16 mb-4 md:mb-20 flex items-center justify-center space-x-2">
                {previousLink && previousLink.url && (
                    <button
                        onClick={() => handlePageChange(currentPage - 1)}
                        className="w-12 h-12 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-100"
                    >
                        <ArrowIcon className="fill-neutral-400 rotate-180" />
                    </button>
                )}

                {renderPageLinks().map((pageOrEllipsis, index) => {
                    if (pageOrEllipsis === '...') {
                        return (
                            <span
                                key={`ellipsis-${index}`}
                                className="w-12 h-12 flex items-center justify-center text-gray-500"
                            >
                                ...
                            </span>
                        );
                    }

                    const isActive = currentPage === pageOrEllipsis;

                    return (
                        <button
                            key={pageOrEllipsis}
                            onClick={() => handlePageChange(pageOrEllipsis)}
                            className={`
                                w-12 h-12 flex items-center justify-center rounded-full font-semibold 
                                ${isActive 
                                    ? 'bg-primary text-white' 
                                    : 'bg-gray-200 text-neutral-400 hover:bg-gray-100'}
                            `}
                        >
                            {pageOrEllipsis}
                        </button>
                    );
                })}

                {nextLink && nextLink.url && (
                    <button
                        onClick={() => handlePageChange(currentPage + 1)}
                        className="w-12 h-12 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-100"
                    >
                        <ArrowIcon className="fill-neutral-400" />
                    </button>
                )}
            </div>
        </div>
    );
};

export default CasesPagination;

const ArrowIcon = ({ className }) => {
    return (
        <svg
            width="15"
            height="15"
            viewBox="0 0 25 25"
            className={className}
            xmlns="http://www.w3.org/2000/svg"
        >
            <path d="M19.023 10.938 10.273 2.188 12.5 0l12.5 12.5L12.5 25l-2.227-2.188 8.75-8.75H0v-3.125h19.023z" />
        </svg>
    )
};