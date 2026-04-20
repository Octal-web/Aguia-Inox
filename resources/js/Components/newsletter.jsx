import React, { useState, useEffect } from 'react';
import { useForm, usePage } from "@inertiajs/react";
import { Button } from "./ui/button";
import { Input } from "./ui/input";

import { useLang } from "@/hooks/useLang";

export function Newsletter() {
    const { message } = usePage().props;

    const [isSuccessful, setIsSuccessful] = useState(false);

    const lang = useLang();

    const { data, setData, post, processing, errors, reset, wasSuccessful } = useForm({
        email: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('Newsletter.enviar'), {
            preserveScroll: true,
            onSuccess: () => {
                reset();
            },
        });
    };

    useEffect(() => {  
        if (message && message.type === 'newsSuccess') {
            setIsSuccessful(true);

            setTimeout(() => {
                setData({
                    email: '',
                });

                setIsSuccessful(false);
            }, 2000);
        }
    }, [message]);

    return (
        <div className="relative container max-w-x-large">
            <div className="flex flex-wrap items-center justify-between py-11 max-[1281px]:justify-center max-[1281px]:gap-6 max-[1025px]:flex-col">
                <div className="flex items-center md:gap-16 max-[1025px]:flex-col max-[601px]:text-center">
                    <h4 className="max-w-full font-sora text-4xl 2xl:text-5xl font-medium text-primary">
                        Newsletter
                    </h4>
                    <p className="max-w-full 2xl:text-lg font-medium tracking-tight text-white max-md:mb-5">
                        {lang('newsletterTexto')}
                    </p>
                </div>
                <form onSubmit={handleSubmit} className="flex flex-col">
                    <div className="flex items-start max-[1025px]:flex-col max-[1025px]:items-center max-[1025px]:gap-6">
                        <div className="relative translate-x-6 max-[1025px]:translate-x-0">
                            <Input
                                className="h-[50px] 2xl:h-[60px] w-[350px] bg-transparent px-8 text-white placeholder:text-white/70 max-[1367px]:w-[250px] max-[601px]:w-full"
                                id="email"
                                type="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                placeholder={lang('seuEmail')}
                            />
                            {errors.email && (
                                <span className="absolute text-xs bg-red-800 text-white py-1 px-2 top-full mt-1 left-0 right-6 block">
                                    {errors.email}
                                </span>
                            )}
                        </div>
                        <Button
                            type="submit"
                            aria-label={processing ? lang('cadastrando') : lang('cadastrar')}
                            disabled={processing}
                            className="relative z-10 h-[50px] 2xl:h-[60px] w-[196px] border border-white bg-white text-lg font-semibold text-primary hover:bg-primary hover:text-white max-[601px]:w-full disabled:opacity-100"
                        >
                            <span className={`${isSuccessful ? 'opacity-0' : ''}`}>{processing ? lang('cadastrando') : lang('cadastrar')}</span>

                            {isSuccessful && (
                                <span className="absolute inset-0 flex items-center justify-center">
                                    <span className="block w-5 h-6 relative">
                                        <span className="absolute top-0 left-0 w-2.5 h-4 border-r-2 border-b-2 border-secondary transform rotate-45 translate-x-1.5 translate-y-0.5" />
                                    </span>
                                </span>
                            )}
                        </Button>
                    </div>
                    {wasSuccessful && (
                        <div className="text-center text-green-400 mt-4">
                            {lang('newsSucesso')}
                        </div>
                    )}
                </form>
            </div>
        </div>
    );
}