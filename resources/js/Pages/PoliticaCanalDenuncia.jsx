import { usePage } from '@inertiajs/react';

import { Footer } from "@/components/footer";
import { Header } from "@/components/header";

import { PrivacyPolicy } from '@/Components/PrivacyPolicy';

const Page = () => {
    const { conteudos } = usePage().props;

    return (
        <>
            <Header />
            <PrivacyPolicy content={conteudos[0]} />

            <h4 className="text-center text-xl mt-20">Quer fazer uma denúncia? Acesse o <a href="https://portalrh.aguiainox.com.br/portalrh/Account/Login" className="text-blue-500 underline" target="blank">canal de denúncias aqui</a>.</h4>
            <Footer />
        </>
    );
};

export default Page;
