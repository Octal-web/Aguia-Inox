import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faImage } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { BlockContent } from '@/Components/Manager/BlockContent';

const Page = () => {
    // Content
    const { idioma, idiomas, produto } = usePage().props;

    const breadcrumbItems = [
        { label: 'Segmentos', link: 'Manager.Segmentos.index' },
        { label: 'Produtos', link: 'Manager.Produtos.index' },
        { label: produto.nome, link: 'Manager.Produtos.editar', params: { id: produto.id }},
    ];

    const contentImages = {
        nome: ['Imagens', 'imagem'],
        controller: 'Produtos.Imagens',
        imagens: true,
        imgClass: '',
        addId: produto.id,
        editavel: true,
        conteudos: produto.imagens
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faImage} items={breadcrumbItems} current="Imagens" idioma={idioma.codigo} idiomas={idiomas} id={produto.id} />

            <BlockContent content={contentImages} />
        </AdminLayout>
    );
};

export default Page;
