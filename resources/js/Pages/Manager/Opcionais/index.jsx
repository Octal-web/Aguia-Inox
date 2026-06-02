import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faList } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { BlockContent } from '@/Components/Manager/BlockContent';

const Page = () => {
    // Content
    const { idioma, idiomas, categorias, opcionais } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Opcionais', link: 'Manager.Opcionais.index' },
    ];

    const contentCategories = {
        nome: ['Categorias', 'categoria'],
        controller: 'Opcionais.Categorias',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: categorias
    };

    const contentOptionals = {
        nome: ['Opcionais', 'opcional'],
        controller: 'Opcionais',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: opcionais
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faList} items={breadcrumbItems} current="Opcionais" idioma={idioma.codigo} idiomas={idiomas} />

            <BlockContent content={contentCategories} />
            
            <BlockContent content={contentOptionals} />
        </AdminLayout>
    );
};

export default Page;
