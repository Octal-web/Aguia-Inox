export function AboutDifferentials({ content, differentials }) {
    return (
        <section
            id="diferenciais"
            className="scroll-mt-20 mt-[150px] flex w-full flex-col items-center"
        >
            <div className="container max-w-x-large">
                <div className="flex w-full flex-col items-center">
                    {content.titulo && (() => {
                        const [firstWord, ...rest] = content.titulo.split(' ');

                        return (
                            <h3 className="font-sora text-5xl tracking-tight text-secondary max-[769px]:text-4xl max-[601px]:text-center max-[601px]:text-3xl">
                                {firstWord}{" "}
                                <span className="font-medium text-primary">
                                    {rest}
                                </span>
                            </h3>
                        );
                    })()}
                    <p className="mt-7 w-full max-w-[920px] text-center tracking-tight text-textblack">
                        {content.texto}
                    </p>
                </div>

                <div className="mt-[109px]">
                    {differentials.map((dif, index) => (
                        <div
                            className="flex w-full items-center sm:px-16 py-5 2xl:py-7 odd:bg-[#EDF1F8] max-[601px]:flex-col max-[601px]:gap-2 max-[601px]:px-8"
                            key={index}
                        >
                            <p className="text-secondary max-[601px]:min-w-full md:w-[30%] md:pr-10 font-sora text-lg font-medium tracking-tight max-[601px]:min-w-full">
                                {dif.nome}
                            </p>
                            <p className="tracking-tight text-secondary md:w-[70%] max-[601px]:text-justify">
                                {dif.descricao}
                            </p>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
