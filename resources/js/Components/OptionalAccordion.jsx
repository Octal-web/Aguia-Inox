import { useState, useRef, useEffect } from 'react';
import { Link } from '@inertiajs/react';

import { ChevronDown } from 'lucide-react';

export const OptionalAccordion = ({ title, slug, items }) => {
    const [isOpen, setIsOpen] = useState(false);
    const [contentHeight, setContentHeight] = useState(0);
    const contentRef = useRef(null);
    const innerRef = useRef(null);
    
    useEffect(() => {
        const hash = window.location.hash.replace('#', '');

        setIsOpen(hash === slug);
    }, []);

    useEffect(() => {
        if (innerRef.current) {
            setContentHeight(innerRef.current.scrollHeight);
        }
    }, []);

    useEffect(() => {
        const handleResize = () => {
            if (innerRef.current) {
                setContentHeight(innerRef.current.scrollHeight);
            }
        };

        window.addEventListener('resize', handleResize);
        return () => window.removeEventListener('resize', handleResize);
    }, []);

    return (
        <div className={`relative before:absolute before:left-[5px] before:top-0 before:w-px before:bg-secondary ${isOpen ? 'before:-bottom-3' : 'before:-bottom-0'}`}>
            <button 
                onClick={() => setIsOpen(!isOpen)}
                className="ml-8 inline-block group w-fit text-left"
            >
                <div className="flex items-center gap-10">
                    <h4 className="font-sora text-4xl font-medium tracking-tight text-primary">
                        {title}
                    </h4>
                    <ChevronDown 
                        className={`w-6 h-6 text-gray-900 transition-transform duration-300 ${
                            isOpen ? 'rotate-180' : ''
                        }`}
                    />
                </div>
            </button>

            <div 
                ref={contentRef}
                style={{ height: isOpen ? `${contentHeight}px` : '0px' }}
                className="overflow-hidden transition-all duration-300 ease-in-out relative"
            >
                <div className="mt-3 pb-2" ref={innerRef}>
                    <ul>
                        {items.map((item, index) => (
                            <li 
                                key={index}
                                className="flex items-center gap-5 py-1.5 before:w-3 before:h-3 before:inline-block before:bg-primary before:relative before:rounded-full"
                            >
                                <Link
                                    href={route('Opcionais.opcional', {categoria: slug, slug: item.slug})}
                                    className="font-sora font-medium text-secondary transition-all hover:opacity-80 cursor-pointer"
                                >
                                    {item.titulo}
                                </Link>
                            </li>
                        ))}
                    </ul>
                </div>
            </div>
        </div>
    );
};