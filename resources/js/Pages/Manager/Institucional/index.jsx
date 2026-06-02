import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCircleInfo } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Manager/ConfirmModal';
import { PageSettings } from '@/Components/Manager/PageSettings';
import { FormContent } from '@/Components/Manager/FormContent';
import { BlockContent } from '@/Components/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, selos, diferenciais } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];

    const contentStamps = {
        nome: ['Selos', 'selo'],
        controller: 'Selos',
        imagens: true,
        imgClass: '',
        editavel: true,
        conteudos: selos
    };

    const contentDifferentials = {
        nome: ['Diferenciais', 'diferencial'],
        controller: 'Diferenciais',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: diferenciais
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faCircleInfo} items={breadcrumbItems} current="Institucional" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />

            <FormContent content={conteudos[0]} full={true} idioma={idioma.codigo} />

            <FormContent content={conteudos[1]} full={true} toolbar={['Heading']} idioma={idioma.codigo} />

            <BlockContent content={contentStamps} />

            <FormContent content={conteudos[2]} full={true} idioma={idioma.codigo} />

            <div className="grid grid-cols-1 gap-x-6 md:grid-cols-3">
                <FormContent content={conteudos[3]} full={false} idioma={idioma.codigo} />

                <FormContent content={conteudos[4]} full={false} idioma={idioma.codigo} />

                <FormContent content={conteudos[5]} full={false} idioma={idioma.codigo} />
            </div>
            
            <FormContent content={conteudos[6]} full={true} idioma={idioma.codigo} />

            <BlockContent content={contentDifferentials} />

            <FormContent content={conteudos[7]} full={true} idioma={idioma.codigo} />

            <FormContent content={conteudos[8]} full={true} idioma={idioma.codigo} />
        </AdminLayout>
    );
};

export default Page;
