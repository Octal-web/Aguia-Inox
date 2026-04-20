import { Reveal } from "./Reveal";

export function AboutValues({ values }) {
    return (
        <section
            className="relative mt-[232px] h-[510px] w-full bg-primary max-[1025px]:mt-20 max-[1025px]:h-fit max-[1025px]:bg-white"
        >
            <div className="container max-w-large">
                <div className="grid grid-cols-3 gap-14 px-2 md:px-10 md:-translate-y-32 max-[1025px]:static max-[1025px]:-translate-x-0 max-lg:grid-cols-1 max-lg:auto-rows-[1fr]">
                    {values.map((item, index) => (
                        <Reveal
                            delay={index * 0.8}
                            scale={true}
                            key={index}
                            className="flex h-full lg:h-fit min-h-[484px] w-full flex-col items-center justify-start rounded-[10px] border-[3px] border-primary bg-[#EDF1F8] px-3 py-10 max-[601px]:min-h-fit"
                        >
                            <img
                                src={item.imagem}
                                alt={item.titulo}
                                className="h-[120px] w-[120px] object-contain"
                            />
                            <h4 className="mt-10 font-sora text-4xl font-medium tracking-tight text-primary">
                                {item.titulo}
                            </h4>
                            <p className="mt-2.5 max-w-[314px] text-center tracking-tight text-textblack max-[1025px]:max-w-[95%]">
                                {item.texto}
                            </p>
                        </Reveal>
                    ))}
                </div>
            </div>
        </section>
    );
}
