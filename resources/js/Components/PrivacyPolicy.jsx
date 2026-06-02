export const PrivacyPolicy = ({ content }) => {
    return (
        <section className="pt-20 md:pt-30">
            <div className="container max-w-medium">
                <h1 className="text-5xl md:text-6xl text-secondary font-bold mb-10">{content.titulo}</h1>
                <div className="font-secondary text-eng-tertiary [&_h1]:text-3xl [&_h1]:font-bold [&_h1]:text-secondary [&_h2]:text-xl [&_h2]:text-primary [&_h1]:mb-2 [&_ul]:list-inside [&_ul_li]:list-disc [&_ul>li>p]:inline [&_ul>li>p]:my-0 [&_ol]:list-inside [&_ol_li]:list-decimal [&_ol>li>p]:inline [&_ol>li>p]:my-0 [&_a]:underline" dangerouslySetInnerHTML={{ __html: content.texto }} /> 
            </div>
        </section>
    );
};