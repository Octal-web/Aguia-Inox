import { useEffect } from 'react';

export const OptionalItem = ({ item }) => {

    useEffect(() => {
        const hash = window.location.hash;
        if (hash) {
            const el = document.getElementById(hash.replace('#', ''));
            if (el) {
                setTimeout(() => {
                    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        }
    }, []);
    
    return (
        <section className="my-20">
            <div className="container max-w-medium">
                <h2 className="text-5xl font-semibold text-primary mb-10">{item.nome}</h2>

                <div className="flex items-center">
                    <img src={item.imagem} className="max-w-sm pr-10" />

                    <p className="text-lg">{item.descricao}</p>
                </div>
            </div>
        </section>
    );
};