import { useEffect } from "react";

export const OptionalItem = ({ item }) => {
    useEffect(() => {
        const hash = window.location.hash;
        if (hash) {
            const el = document.getElementById(hash.replace("#", ""));
            if (el) {
                setTimeout(() => {
                    el.scrollIntoView({ behavior: "smooth", block: "start" });
                }, 100);
            }
        }
    }, []);

    return (
        <section id={item.slug} className="my-20">
            <div className="container max-w-medium">
                <h2 className="text-5xl font-semibold text-primary mb-10">
                    {item.titulo}
                </h2>

                <div className="grid grid-cols-2 lg:grid-cols-4 gap-y-5 sm:gap-y-10 2xl:gap-y-16 gap-x-2 sm:gap-x-4 2xl:gap-x-8">
                    {item.modelos.map((modelo, index) => (
                        <div key={index} className={item.modelos.length > 1 ? "" : "col-span-12" }>
                            <img
                                src={modelo.imagem}
                                alt={modelo.nome}
                                className="max-w-sm w-full shrink-0 mb-4"
                            />
                            <div>
                                <h3 className="text-2xl font-semibold text-primary">
                                    {modelo.nome}
                                </h3>
                                <p className="text-lg mt-4">{modelo.descricao}</p>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
};
