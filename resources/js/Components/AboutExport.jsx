import { Reveal } from "@/Components/Reveal";

export function AboutExport({ content }) {
    return (
        <section>
            <div className="container max-w-large">
                <div className="mt-20 grid grid-cols-2 gap-24 max-[1025px]:grid max-[1025px]:grid-cols-1 max-[1025px]:gap-10">
                    <Reveal className="relative" direction="left">
                        <img
                            src={content.imagem}
                            alt={content.titulo}
                            className="h-[521px] w-full rounded-[10px] object-cover max-[1025px]:order-2 max-[1025px]:h-[400px]"
                        />
                        
                        <div className="absolute inset-0 rounded-[10px] bg-primary bg-linear-to-b from-transparent to-black/50 mix-blend-soft-light opacity-75" /> 
                    </Reveal>
                    <Reveal direction="right" className="flex flex-col justify-center max-[1025px]:text-justify">
                        {content.titulo && (() => {
                            const words = content.titulo.split(' ');
                            const firstTwo = words.slice(0, 2).join(' ');
                            const rest = words.slice(2).join(' ');

                            return (
                                <h3 className="font-sora text-5xl text-secondary max-[601px]:text-3xl">
                                    {firstTwo}<br /><span className="font-bold text-primary">{rest ? ' ' + rest : ''}</span>
                                </h3>
                            );
                        })()}
                        
                        <div className="mt-10 max-w-[563px] tracking-tight text-textblack max-[1025px]:max-w-full [&_h4]:mt-5 [&_h4]:text-xl [&_h4]:font-semibold [&_h4]:tracking-tight [&_h4]:text-[#4B4B4B]" dangerouslySetInnerHTML={{__html: content.texto }} />
                    </Reveal>
                </div>
            </div>
        </section>
    );
}