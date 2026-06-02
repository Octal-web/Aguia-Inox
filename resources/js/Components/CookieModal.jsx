import React, { useState, useEffect } from 'react';
import { Link } from '@inertiajs/react';

const setCookie = (name, value, days) => {
    const expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/';
    };

    const getCookie = (name) => {
        return document.cookie.split('; ').reduce((r, v) => {
            const parts = v.split('=');
            return parts[0] === name ? decodeURIComponent(parts[1]) : r;
        }, '');
    };

export const CookieModal = ({ acceptCookies, visible }) => {
    const [showModal, setShowModal] = useState(true);
    const [isFadingOut, setIsFadingOut] = useState(false);

    useEffect(() => {
        const notifyCookies = getCookie('notify-cookies');
        if (notifyCookies === '1') {
            setShowModal(false);
        }
    }, []);


    useEffect(() => {
        const notifyCookies = getCookie('notify-cookies');
        if (notifyCookies === '1') {
            setShowModal(false);
        }
    }, []);

    const handleAcceptCookies = () => {
        setCookie('notify-cookies', '1', 365);
        setIsFadingOut(true);
        acceptCookies();
        setTimeout(() => {
            setShowModal(false);
        }, 200);
    };

    return (
        <>
            {showModal && visible ? (
                <div className={`fixed bottom-0 left-0 right-0 z-[999] ${isFadingOut ? 'animate-fade-out-down' : ''}`}>
                    <div className="container max-w-large">
                        <div className="bg-white px-4 sm:px-8 py-4 md:py-6 shadow-md rounded-md mb-6 md:mb-10">
                            <div>
                                <p className="text-xs sm:text-sm 2xl:text-base max-sm:leading-tight">
                                Utilizamos cookies e tecnologias semelhantes para garantir o funcionamento do site, melhorar sua experiência de navegação, analisar o uso da plataforma e, quando aplicável, personalizar conteúdos e comunicações. Você pode aceitar todos os cookies, rejeitar aqueles não essenciais ou gerenciar suas preferências. Para mais informações, acesse nossa{' '} 
                                <Link href={route('Politicas.privacidade')} className="underline">política de privacidade</Link>.
                                </p>
                            </div>
                            <button
                                aria-label="Aceitar todos os cookies"
                                onClick={handleAcceptCookies}
                                className="md:ml-auto mt-4 md:mt-2 max-2xl:text-sm block items-center justify-center cursor-pointer gap-2 whitespace-nowrap rounded-md transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive bg-primary text-primary-foreground shadow-xs hover:bg-secondary px-4 py-2 h-[45px] md:h-[54px] w-[273px] font-sora text-lg font-semibold max-[1367px]:w-[250px]"
                            >
                            Aceitar todos os cookies
                          </button>
                        </div>
                    </div>
                </div>
            ) : null}
        </>
    );
};