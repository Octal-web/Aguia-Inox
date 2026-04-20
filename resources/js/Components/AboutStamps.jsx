import { StampVideo } from './StampVideo';
import { Reveal } from './Reveal';

import { useLang } from "@/hooks/useLang";

export function AboutStamps({ stamps }) {
    const lang = useLang();

    return (
        <section
            id="selos"
            className="relative pt-6 md:mt-48 flex w-full flex-col space-y-28 py-36 max-[1025px]:mt-20 overflow-hidden"
        >
            <img
                src="/site/img/pattern-bg.jpg"
                alt="Fundo Selo"
                className="absolute !mt-0 -top-0 left-0 -z-10 h-full w-full object-cover"
            />

            {stamps.map((stamp, index) => {
                const isMobile = window.innerWidth <= 1025;
                const isEven = index % 2 === 0;

                return (
                    <div key={stamp.id} className="relative">
                        <div
                            className={`flex w-full items-center gap-16 md:gap-[104px] max-[1281px]:grid max-[1281px]:grid-cols-2 max-[1281px]:px-[5%] max-[1025px]:grid-cols-1 max-[1025px]:justify-center ${
                                isMobile ? "justify-center" : (isEven ? "justify-end" : "justify-start")
                            }`}
                        >
                            {(isMobile || isEven) && (
                                <Reveal 
                                    direction={isMobile ? "up" : "left"} 
                                    className="max-[1025px]:flex max-[1025px]:flex-col max-[1025px]:items-center"
                                >
                                    <span className="font-sora tracking-tight text-white">{lang('selo')}</span>
                                    <h3 className="mt-2.5 font-sora text-5xl text-white max-[769px]:text-4xl max-[601px]:text-4xl max-[1025px]:text-center">
                                        <strong className="font-bold text-primary">
                                            {stamp.nome.split(" ")[0]}
                                        </strong>
                                        <br />
                                        {stamp.nome.split(" ").slice(1).join(" ")}
                                    </h3>
                                    <p className="mt-8 max-w-[421px] tracking-tight text-white max-[1025px]:text-center">
                                        {stamp.descricao}
                                    </p>
                                </Reveal>
                            )}

                            <StampVideo src={stamp.video} poster={stamp.imagem} />

                            {!isMobile && !isEven && (
                                <Reveal direction="right" className="max-[1025px]:flex max-[1025px]:flex-col max-[1025px]:items-center">
                                    <span className="font-sora tracking-tight text-white">{lang('selo')}</span>
                                    <h3 className="mt-2.5 font-sora text-5xl text-white max-[769px]:text-4xl max-[601px]:text-4xl max-[1025px]:text-center">
                                        <strong className="font-bold text-primary">
                                            {stamp.nome.split(" ")[0]}
                                        </strong>
                                        <br />
                                        {stamp.nome.split(" ").slice(1).join(" ")}
                                    </h3>
                                    <p className="mt-8 max-w-[421px] tracking-tight text-white">
                                        {stamp.descricao}
                                    </p>
                                </Reveal>
                            )}

                            <div className="absolute max-sm:-top-10 max-sm:opacity-50 left-1/2 -translate-x-1/2 ">
                                <img src={stamp.selo} alt={stamp.nome} className="animate-spin [animation-duration:_25s]" />
                            </div>
                        </div>
                    </div>
                );
            })}
        </section>
    );
}