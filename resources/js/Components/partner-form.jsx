import { useState, useEffect } from 'react';

import { cn } from "@/lib/utils";
import { useForm } from "@inertiajs/react";
import { Button } from "./ui/button";
import { Checkbox } from "./ui/checkbox";
import { Input } from "./ui/input";
import { InputMask } from '@react-input/mask';
import { Label } from "./ui/label";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "./ui/select";
import { Textarea } from "./ui/textarea";

import { useLang } from "@/hooks/useLang";

export function PartnerForm() {
    const [phoneMask, setPhoneMask] = useState("(__) ____-____");
    const [showSuccess, setShowSuccess] = useState(false);
    const [fadeOut, setFadeOut] = useState(false);

    const lang = useLang();
    
    const { data, setData, post, processing, errors, reset, wasSuccessful } = useForm({
        nome: '',
        email: '',
        cnpj: '',
        telefone: '',
        cargo: '',
        // assunto: '',
        mensagem: '',
        politica: false,
    });

    useEffect(() => {
        const numbers = data.telefone.replace(/\D/g, '');
        
        if (numbers.length >= 10) {
            setPhoneMask("(__) _____-____");
        } else if (numbers.length < 10) {
            setPhoneMask("(__) ____-____");
        }
    }, [data.telefone]);

    useEffect(() => {
        if (wasSuccessful) {
            setShowSuccess(true);
            setFadeOut(false);
            
            const fadeTimer = setTimeout(() => {
                setFadeOut(true);
            }, 3000);
            
            const hideTimer = setTimeout(() => {
                setShowSuccess(false);
            }, 4000);

            return () => {
                clearTimeout(fadeTimer);
                clearTimeout(hideTimer);
            };
        }
    }, [wasSuccessful]);

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('Parceiros.enviar'), {
            preserveScroll: true,
            onSuccess: () => {
                reset();
            },
        });
    };

    useEffect(() => {
        const hash = window.location.hash;
        if (hash) {
            const element = document.querySelector(hash);
            if (element) {
                setTimeout(() => {
                    element.scrollIntoView();
                }, 200);
            }
        }
    }, []);

    return (
        <div className="mt-36 w-full bg-white max-[601px]:mt-20 scroll-mt-10" id="parceria">
            <div className="relative container max-w-large">
                <h4 className="font-sora text-4xl sm:text-5xl font-light text-secondary text-center max-[601px]:text-center max-[601px]:text-3xl">
                    <strong className="font-bold text-primary">
                        {lang('sejaParceiro')}
                    </strong><br className="md:hidden" />{" "}
                    Águia Inox
                </h4>
                <p className="mt-10 w-full max-w-[935px] tracking-tight text-center text-textblack mx-auto max-[601px]:text-center">
                    {lang('sejaParceiroTexto')}
                </p>
                <form
                    className="mt-20 px-2 md:px-10 flex w-full flex-col items-center space-y-7 font-sora"
                    onSubmit={handleSubmit}
                >
                    <div className="flex w-full flex-col items-center space-y-5">
                        <div className="grid w-full">
                            <div>
                                <Input
                                    className="h-[50px] md:h-[60px] bg-[#edf1f8] px-4 md:px-8"
                                    id="nome"
                                    value={data.nome}
                                    onChange={(e) => setData('nome', e.target.value)}
                                    placeholder={lang('seuNome')}
                                />
                                {errors.nome && (
                                    <span className="text-xs text-red-600">
                                        {errors.nome}
                                    </span>
                                )}
                            </div>
                        </div>
                        <div className="grid w-full grid-cols-2 max-[601px]:gap-5 gap-8 max-[601px]:grid-cols-1">
                            <div>
                                <Input
                                    className="h-[50px] md:h-[60px] bg-[#edf1f8] px-4 md:px-8"
                                    id="email"
                                    type="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    placeholder={lang('seuEmail')}
                                />
                                {errors.email && (
                                    <span className="text-xs text-red-600">
                                        {errors.email}
                                    </span>
                                )}
                            </div>

                            <div>
                                <Input
                                    className="h-[50px] md:h-[60px] bg-[#edf1f8] px-4 md:px-8"
                                    id="cnpj"
                                    value={data.cnpj}
                                    onChange={(e) => setData('cnpj', e.target.value)}
                                    placeholder={lang('seuCNPJ')}
                                />
                                {errors.cnpj && (
                                    <span className="text-xs text-red-600">
                                        {errors.cnpj}
                                    </span>
                                )}
                            </div>
                        </div>
                        <div className="grid w-full grid-cols-2 max-[601px]:gap-5 gap-8 max-[601px]:grid-cols-1">
                            <div>
                                <InputMask
                                    className="h-[50px] md:h-[60px] bg-[#edf1f8] px-4 md:px-8 flex w-full rounded-md border border-input py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-hidden focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                    id="telefone"
                                    mask={phoneMask}
                                    replacement={{ _: /\d/ }}
                                    value={data.telefone}
                                    onChange={(e) => setData('telefone', e.target.value)}
                                    placeholder={lang('seuTelefone')}
                                />
                                {errors.telefone && (
                                    <span className="text-xs text-red-600">
                                        {errors.telefone}
                                    </span>
                                )}
                            </div>

                            <div>
                                <Input
                                    className="h-[50px] md:h-[60px] bg-[#edf1f8] px-4 md:px-8"
                                    id="cargo"
                                    value={data.cargo}
                                    onChange={(e) => setData('cargo', e.target.value)}
                                    placeholder={lang('seuCargo')}
                                />
                                {errors.cargo && (
                                    <span className="text-xs text-red-600">
                                        {errors.cargo}
                                    </span>
                                )}
                            </div>

                            {/* <div>
                                <Select
                                    value={data.assunto}
                                    onValueChange={(value) => setData("assunto", value)}
                                >
                                    <SelectTrigger
                                        id="assunto"
                                        className={cn(
                                            "h-[50px] md:h-[60px] bg-[#edf1f8] px-4 md:px-8",
                                            data.assunto && "bg-transparent"
                                        )}
                                    >
                                        <SelectValue placeholder={lang('selecioneOAssunto')} />
                                    </SelectTrigger>
                                    <SelectContent className="bg-[#edf1f8]">
                                        <SelectItem
                                            value="comercial"
                                            className="focus:bg-primary focus:text-white"
                                        >
                                            {lang('comercial')}
                                        </SelectItem>
                                        <SelectItem
                                            value="parceria"
                                            className="focus:bg-primary focus:text-white"
                                        >
                                            {lang('parceria')}
                                        </SelectItem>
                                        <SelectItem
                                            value="investimento"
                                            className="focus:bg-primary focus:text-white"
                                        >
                                            {lang('investimento')}
                                        </SelectItem>
                                        <SelectItem
                                            value="outros"
                                            className="focus:bg-primary focus:text-white"
                                        >
                                            {lang('outros')}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                {errors.assunto && (
                                    <span className="text-xs text-red-600">
                                        {errors.assunto}
                                    </span>
                                )}
                            </div> */}
                        </div>

                        <div className="w-full">
                            <Textarea
                                id="mensagem"
                                className="min-h-[200px] md:min-h-[266px] w-full resize-none rounded-md border bg-[#edf1f8] px-4 md:px-8 py-5 text-base shadow-xs"
                                value={data.mensagem}
                                onChange={(e) => setData('mensagem', e.target.value)}
                                placeholder={lang('escreverMensagem')}
                            />
                            {errors.mensagem && (
                                <span className="text-xs text-red-600">
                                    {errors.mensagem}
                                </span>
                            )}
                        </div>
                    </div>

                    <div className="flex w-full items-center justify-center gap-2">
                        <Checkbox
                            className="h-[30px] w-[30px] rounded-[10px] border-2 border-primary"
                            id="politica"
                            checked={data.politica}
                            onCheckedChange={(checked) => setData("politica", !!checked)}
                        />
                        <Label
                            htmlFor="politica"
                            className="font-sora font-light text-textblack max-sm:leading-tight"
                        >
                            {lang('aceito')}{" "}
                            <a href={route('Politicas.privacidade')} target="_blank" className="font-bold underline lowercase">
                                {lang('politicaPrivacidade')}
                            </a>{" "}
                            {lang('siteAguiaInox')}
                        </Label>
                    </div>
                    {errors.politica && (
                        <span className="block text-xs text-red-600">
                            {errors.politica}
                        </span>
                    )}
                    <Button
                        type="submit"
                        aria-label={processing ? lang('enviandoMensagem') : lang('enviarMensagem')}
                        disabled={processing}
                        className="h-[50px] md:h-[60px] w-[300px] border border-primary bg-white text-lg font-semibold text-primary hover:bg-primary hover:text-white max-[601px]:w-full disabled:opacity-50"
                    >
                        {processing ? lang('enviandoMensagem') : lang('enviarMensagem')}
                    </Button>
                    {showSuccess && (
                        <div className={cn(
                            "absolute -bottom-2 bg-white text-lg text-center font-bold text-green-600 py-6 px-10 transition-opacity duration-1000",
                            fadeOut ? "opacity-0" : "opacity-100"
                        )}>
                            {lang('parceriaSucesso')}
                        </div>
                    )}
                </form>
            </div>
        </div>
    );
}