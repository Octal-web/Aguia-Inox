import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faDownload } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Manager/ConfirmModal';
import { PageSettings } from '@/Components/Manager/PageSettings';
import { FormContent } from '@/Components/Manager/FormContent';
import { BlockContent } from '@/Components/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, downloads } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];

    const contentSegDownloads = {
        nome: ['Download/Segmento', 'download'],
        controller: 'Downloads',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: downloads['segmento'],
        addId: 'segmento'
    };

    const contentCatDownloads = {
        nome: ['Download/Categoria', 'download'],
        controller: 'Downloads',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: downloads['produtocategoria'],
        addId: 'categoria'
    };

    const contentProdDownloads = {
        nome: ['Download/Produto', 'download'],
        controller: 'Downloads',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: downloads['produto'],
        addId: 'produto'
    };


    return (
        <AdminLayout>
            <Breadcrumb icon={faDownload} items={breadcrumbItems} current="Downloads" idioma={idioma.codigo} idiomas={idiomas} />

            <BlockContent content={contentSegDownloads} />
            
            <BlockContent content={contentCatDownloads} />
            
            <BlockContent content={contentProdDownloads} />
        </AdminLayout>
    );
};

export default Page;
