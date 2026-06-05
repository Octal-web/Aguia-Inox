import { useLang } from "@/Hooks/useLang";
import { Head, Link } from "@inertiajs/react";

const Erro = ({ status }) => {
    const lang = useLang();

    const handleRedirect = () => {
        const isManager = window.location.pathname.startsWith("/manager");

        if (isManager) return "Manager.Home.index";

        return "Home.index";
    };

    return (
        <>
            <Head>
                <title>Águia Inox | Error</title>
                <link rel="icon" href={`/favicon.ico`} type="image/x-icon" />
            </Head>

            <main className="min-h-screen flex items-center justify-center container max-w-large">
                <div className="absolute inset-0 -z-10 h-full w-full bg-white bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]">
                    <div className="absolute left-0 right-0 top-0 -z-10 m-auto h-[310px] w-[310px] rounded-full bg-primary opacity-20 blur-[100px]" />
                </div>
                <div className="text-center">
                    <img
                        src={`/site/img/logo.svg`}
                        alt="Logo"
                        className="mx-auto block max-xl:max-w-40 max-w-[100%] mb-10"
                    />
                    <h1 className="text-9xl md:text-[300px] font-bold">
                        {status}
                    </h1>

                    <p
                        className="text-base md:text-xl mb-20 text-custom-gray"
                        dangerouslySetInnerHTML={{
                            __html: lang(status, window.location.pathname),
                        }}
                    />

                    <Link
                        href={route(handleRedirect())}
                        className="w-fit mx-auto block px-8 py-2 gap-2 rounded-md bg-primary hover:bg-secondary text-white text-sm md:text-lg lg:text-sm xl:text-base 2xl:text-lg font-bold ring-1 ring-white transition-all hover:brightness-90 hover:text-tertiary hover:shadow truncate"
                    >
                        {lang("back")}
                    </Link>
                </div>
            </main>
        </>
    );
};

export default Erro;
