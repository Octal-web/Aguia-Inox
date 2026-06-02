import { usePage } from '@inertiajs/react';

import { Header } from "@/Components/header";
import { Footer } from "@/Components/footer";

import useScrollToHash from "@/hooks/useScrollToHash";

import { AboutCompany } from "@/Components/AboutCompany";
import { AboutExport } from "@/Components/AboutExport";
import { AboutStamps } from "@/Components/AboutStamps";
import { AboutCompromise } from "@/Components/AboutCompromise";
import { AboutValues } from "@/Components/AboutValues";
import { AboutDifferentials } from "@/Components/AboutDifferentials";
import { AboutFrontier } from "@/Components/AboutFrontier";
import { AboutPartner } from "@/Components/AboutPartner";

export default function Empresa() {
    useScrollToHash();

    const { selos, diferenciais, conteudos } = usePage().props;
    
    return (
        <>
            <Header />

            <AboutCompany content={conteudos[0]} />

            <AboutExport content={conteudos[1]} />

            <AboutStamps stamps={selos} />

            <AboutCompromise content={conteudos[2]} />

            <AboutValues values={[conteudos[3], conteudos[4], conteudos[5]]} />

            <AboutDifferentials content={conteudos[6]} differentials={diferenciais} />

            <AboutFrontier content={conteudos[7]} />

            <AboutPartner content={conteudos[8]} />

            <Footer />
        </>
    );
}
