import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEarthAmericas } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Manager/ConfirmModal';
import { PageSettings } from '@/Components/Manager/PageSettings';
import { FormContent } from '@/Components/Manager/FormContent';
import { BlockContent } from '@/Components/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, idioma, idiomas, segmentos, produtosCategorias } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];

    const contentSegments = {
        nome: ['Segmentos', 'segmento'],
        controller: 'Segmentos',
        imagens: true,
        imgClass: '',
        editavel: true,
        conteudos: segmentos
    };

    const contentCategories = {
        nome: ['Categorias', 'categoria'],
        controller: 'Produtos.Categorias',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: produtosCategorias
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faEarthAmericas} items={breadcrumbItems} current="Segmentos" idioma={idioma.codigo} idiomas={idiomas} />
            
            <BlockContent content={contentSegments} />
            
            <BlockContent content={contentCategories} />
        </AdminLayout>
    );
};

export default Page;
