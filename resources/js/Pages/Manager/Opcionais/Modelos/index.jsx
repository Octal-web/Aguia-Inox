import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faList } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { BlockContent } from '@/Components/Manager/BlockContent';

const Page = () => {
    // Content
    const { idioma, idiomas, opcional } = usePage().props;

    const breadcrumbItems = [
        { label: 'Home', link: 'Manager.Opcionais.index' },
        { label: 'Opcionais', link: 'Manager.Opcionais.index' },
        { label: opcional.titulo, link: 'Manager.Opcionais.editar', params: { id: opcional.id }},
    ];

    const contentProducts = {
        nome: ['Modelos', 'modelo'],
        controller: 'Opcionais.Modelos',
        imagens: true,
        imgClass: '',
        addId: opcional.id,
        editavel: true,
        conteudos: opcional.modelos
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faList} items={breadcrumbItems} current="Modelos" idioma={idioma.codigo} />

            <BlockContent content={contentProducts} />
        </AdminLayout>
    );
};

export default Page;
