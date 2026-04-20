export function AboutCompromise({ content }) {
    return (
        <div className="container max-w-large">
            <div id="valores" className="scroll-mt-10 mt-32 sm:mt-[150px] flex w-full flex-col items-center justify-center gap-9 md:px-10">
                {content.titulo && (() => {
                    const [firstWord, ...rest] = content.titulo.split(' ');

                    return (
                        <h3 className="font-sora text-5xl tracking-tight text-textblack max-[769px]:text-4xl max-[601px]:text-center max-[601px]:text-4xl">
                            {firstWord}{' '}
                            <span className="font-medium text-primary">
                                {rest}
                            </span>
                        </h3>
                    );
                })()}
                <div className="max-w-[920px] text-justify md:text-center tracking-tight text-textblack" dangerouslySetInnerHTML={{ __html: content.texto }} />
            </div>
        </div>
    );
}
