import { usePage } from '@inertiajs/react';

import { Footer } from "@/Components/footer";
import { Header } from "@/Components/header";

import { PrivacyPolicy } from '@/Components/PrivacyPolicy';

const Page = () => {
    const { conteudos } = usePage().props;

    return (
        <>
            <Header />
            <PrivacyPolicy content={conteudos[0]} />
            <Footer />
        </>
    );
};

export default Page;
