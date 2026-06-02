import { useForm } from "@inertiajs/react";
import { Input } from "./ui/input";

import { useLang } from "@/hooks/useLang";

export function Search() {
    const lang = useLang();

    const { data, setData, get, processing, errors } = useForm({
        q: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        
        if (data.q.trim()) {
            get(route('Pesquisar.index', { q: data.q }), {
                preserveState: true,
            });
        }
    };

    return (
        <div className="absolute top-[80px] md:top-[110px] 2xl:top-[134px] left-0  w-full z-50 overflow-hidden">
            <div className="h-[130px] border-t-2 border-b-4 border-primary bg-[#EDF1F8] pt-12 md:pt-20 pb-11 shadow-lg animate-fade-in-down">
                <div className="mx-auto flex h-full w-full max-w-[1541px] items-center justify-between px-6">
                    <form
                        className="flex w-full items-center"
                        onSubmit={handleSubmit}
                    >
                        <div className="flex w-full flex-col">
                            <Input
                                placeholder={`${lang('pesquisa')} / Águia Code`}
                                id="q"
                                value={data.q}
                                onChange={(e) => setData('q', e.target.value)}
                                className="rounded-none border-t-0 border-r-0 border-b-2 border-l-0 shadow-none focus-visible:border-b-2 focus-visible:ring-0"
                            />
                            {errors.q && (
                                <span className="font-sora text-xs text-red-600">
                                    {errors.q}
                                </span>
                            )}
                        </div>
                        <button 
                            type="submit" 
                            disabled={processing}
                            className="cursor-pointer disabled:opacity-50"
                        >
                            <img src="/site/img/icon-search.png" alt="Pesquisar" />
                        </button>
                    </form>
                </div>
            </div>
        </div>
    );
}