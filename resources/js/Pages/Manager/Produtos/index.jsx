import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faWarehouse  } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Manager/ConfirmModal';
import { PageSettings } from '@/Components/Manager/PageSettings';
import { FormContent } from '@/Components/Manager/FormContent';
import { BlockContent } from '@/Components/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, produtos } = usePage().props;

    const breadcrumbItems = [
        { label: 'Segmentos', link: 'Manager.Segmentos.index' },
    ];

    const contentProducts = {
        nome: ['Produtos', 'produto'],
        controller: 'Produtos',
        imagens: true,
        imgClass: '',
        editavel: true,
        conteudos: produtos
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faWarehouse} items={breadcrumbItems} current="Produtos" idioma={idioma.codigo} idiomas={idiomas} />
            
            <BlockContent content={contentProducts} />
        </AdminLayout>
    );
};

export default Page;
