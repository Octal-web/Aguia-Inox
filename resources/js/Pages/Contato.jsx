import { useRef, useState } from "react";

import { Footer } from "@/Components/footer";
import { Header } from "@/Components/header";
import { Button } from "@/Components/ui/button";
import { Checkbox } from "@/Components/ui/checkbox";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { Separator } from "@/Components/ui/separator";
import { Textarea } from "@/Components/ui/textarea";
import { cn } from "@/lib/utils";
import { usePage, useForm } from "@inertiajs/react";
import { Mail, Phone } from "lucide-react";

import { useLang } from "@/hooks/useLang";

export default function Contato() {
    const [termsVisible, setTermsVisible] = useState(false);

    const lang = useLang();
    const { departamentos, conteudos, dadosGerais } = usePage().props;
    
    const urlParams = new URLSearchParams(window.location.search);

    const { data, setData, post, processing, errors, clearErrors, reset, wasSuccessful } = useForm({
        nome: '',
        empresa: '',
        email: '',
        origem: urlParams.get('utm_source') || '',
        campanha: urlParams.get('utm_campaign') || '',
        grupo: urlParams.get('utm_group') || '',
        anuncio: urlParams.get('utm_content') || '',
        posicao_formulario: 'Página de Contato',
        departamento_id: '',
        assunto: '',
        mensagem: '',
        politica: false,
    });

    const termsRef = useRef(null);

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('Contato.enviar'), {
            preserveScroll: true,
            onSuccess: (page) => {
                reset();
            },
            onError: () => {
            }
        });
    };

    return (
        <>
            <Header />
            <div className="relative h-[344px] w-full max-[601px]:h-[200px]">
                <img
                    src="/site/img/header-contato.png"
                    alt="Banner Contato"
                    className="h-full w-full object-cover"
                />
                <div className="absolute inset-0 bg-primary mix-blend-overlay" />
            </div>
            <div className="mx-auto mt-28 grid w-full max-w-[1510px] grid-cols-2 gap-44 px-10  max-[1025px]:grid-cols-1 max-[1025px]:gap-0 max-[601px]:mt-12">
                <div className="w-full max-w-[555px] max-[1025px]:mx-auto">
                    <h2 className="max-w-[500px] font-sora text-6xl font-medium tracking-tight text-primary max-[601px]:text-3xl">
                        {conteudos[0].titulo}
                    </h2>
                    <div className="mt-8 flex w-full flex-col space-y-4">
                        <Separator className="max-w-[505px]" />
                        <p className="text-sm text-secondary">
                            {`${dadosGerais.endereco} ${dadosGerais.cep} | ${dadosGerais.cidade}`}
                        </p>
                        <Separator className="max-w-[505px]" />
                        <div className="flex items-center gap-2">
                            <Mail size={15} />
                            <a
                                href={`mailto:${dadosGerais.email}`}
                                className="text-sm text-secondary"
                            >
                                {dadosGerais.email}
                            </a>
                        </div>
                        <Separator className="max-w-[505px]" />
                        <div className="flex items-center gap-2">
                            <Phone size={15} />
                            <a
                                href={`tel:${dadosGerais.telefone.replace(/\D/g, '')}`}
                                className="text-sm text-secondary"
                            >
                                {dadosGerais.telefone}
                            </a>
                        </div>
                        <Separator className="max-w-[505px]" />
                        <div className="flex items-center gap-2">
                            <Phone size={15} />
                            <a
                                href={`https://wa.me/${dadosGerais.whatsapp_mkt.replace(/\D/g, '')}`}
                                className="text-sm text-secondary"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                {dadosGerais.whatsapp_mkt}
                            </a>
                        </div>
                        <Separator className="max-w-[505px]" />
                    </div>
                    <div className="mt-16 tracking-tight text-textblack max-[601px]:text-justify" dangerouslySetInnerHTML={{ __html: conteudos[0].texto }} />
                </div>
                <form
                    className="mt-8 w-full max-w-xl space-y-7 font-sora max-[1025px]:mx-auto"
                    onSubmit={handleSubmit}
                >
                    <div className="flex flex-col space-y-5">
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
                        <div>
                            <Input
                                className="h-[50px] md:h-[60px] bg-[#edf1f8] px-4 md:px-8"
                                id="empresa"
                                value={data.empresa}
                                onChange={(e) => setData('empresa', e.target.value)}
                                placeholder={lang('nomeDaEmpresa')}
                            />
                            {errors.empresa && (
                                <span className="text-xs text-red-600">
                                    {errors.empresa}
                                </span>
                            )}
                        </div>
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
                            <Select
                                value={String(data.departamento_id)}
                                onValueChange={(value) => setData("departamento_id", Number(value))}
                            >
                                <SelectTrigger
                                    id="departamento_id"
                                    className={cn(
                                        "h-[50px] md:h-[60px] bg-[#edf1f8] px-4 md:px-8",
                                        data.departamento_id && "bg-transparent"
                                    )}
                                >
                                    <SelectValue placeholder={lang('selecioneOSetor')}>
                                        {departamentos.find((dep) => dep.value === data.departamento_id)?.label}
                                    </SelectValue>
                                </SelectTrigger>

                                <SelectContent className="bg-[#edf1f8]">
                                    {departamentos.map((dep) => (
                                        <SelectItem
                                            key={dep.value}
                                            value={String(dep.value)}
                                            className="focus:bg-primary focus:text-white"
                                        >
                                            {dep.label}
                                      </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>

                            {errors.departamento_id && (
                                <span className="text-xs text-red-600">
                                    {errors.departamento_id}
                                </span>
                            )}
                        </div>
                        <div>
                            <Input
                                className="h-[50px] md:h-[60px] bg-[#edf1f8] px-4 md:px-8"
                                id="assunto"
                                value={data.assunto}
                                onChange={(e) => setData('assunto', e.target.value)}
                                placeholder={lang('assunto')}
                            />
                            {errors.assunto && (
                                <span className="text-xs text-red-600">
                                    {errors.assunto}
                                </span>
                            )}
                        </div>
                        <div>
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

                    <div ref={termsRef} className={`bg-gray-100 text-xs sm:text-sm leading-tight text-black ${termsVisible ? 'mb-3' : 'mb-0'} overflow-hidden transition-all duration-300`} style={{ maxHeight: termsVisible ? `${termsRef.current?.scrollHeight}px` : '0px' }}>
                        <div className="py-4 px-5">
                            <p>Os dados pessoais informados neste formulário serão utilizados pela Águia Inox para atender sua solicitação, realizar contato e dar seguimento a eventual relacionamento comercial, com base nas hipóteses legais previstas na legislação aplicável.</p>
                            <p>Para mais informações sobre o tratamento de dados pessoais, bases legais e seus direitos como titular, acesse nossa <a href={route('Politicas.privacidade')} target="_blank" className="font-bold underline">{lang('politicaPrivacidade')}</a>.</p>
                        </div>
                    </div>


                    <div className="flex items-center gap-2">
                        <Checkbox
                            className="h-[30px] w-[30px] rounded-[10px] border-2 border-primary"
                            id="politica"
                            checked={data.politica}
                            onCheckedChange={(checked) => setData('politica', !!checked)}
                        />
                        <Label
                            htmlFor="politica"
                            className="font-sora font-light text-textblack max-sm:leading-tight"
                        >
                            {lang('aceito')}{" "}
                            <span
                                onClick={(e) => {
                                    e.preventDefault();
                                    setTermsVisible(!termsVisible);
                                }}
                                className="font-bold cursor-pointer underline"
                            >
                                {lang('termosUso')}
                            </span> {lang('ea')}
                            <a href={route('Politicas.privacidade')} target="_blank" className="font-bold underline">
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
                    {wasSuccessful && (
                        <div className="text-center text-green-600">
                            {lang('parceriaSucesso')}
                        </div>
                    )}
                </form>
            </div>
            <Footer />
        </>
    );
}