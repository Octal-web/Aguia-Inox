import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEnvelope } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { ConfirmModal } from '@/Components/Manager/ConfirmModal';
import { PageSettings } from '@/Components/Manager/PageSettings';
import { FormContent } from '@/Components/Manager/FormContent';
import { BlockContent } from '@/Components/Manager/BlockContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas, contatos, newsletters, parcerias, departamentos } = usePage().props;

    const breadcrumbItems = [
        // { label: 'Home', link: 'Home.index' },
        // { label: 'Projects', link: 'Home.index' },
    ];

    const contentContacts = {
        nome: ['Contatos', 'contato'],
        controller: 'Contato',
        imagens: false,
        imgClass: '',
        editavel: false,
        conteudos: contatos
    };

    const contentPartners = {
        nome: ['Parcerias', 'parceria'],
        controller: 'Parcerias',
        imagens: false,
        imgClass: '',
        editavel: false,
        conteudos: parcerias
    };
    
    const contentNewsletters = {
        nome: ['Assinaturas de Newsletters', 'newsletter'],
        controller: 'Newsletter',
        imagens: false,
        imgClass: '',
        editavel: false,
        conteudos: newsletters
    };
    
    const contentDepartments = {
        nome: ['Departamentos', 'departamento'],
        controller: 'Departamentos',
        imagens: false,
        imgClass: '',
        editavel: true,
        conteudos: departamentos
    };


    return (
        <AdminLayout>
            <Breadcrumb icon={faEnvelope} items={breadcrumbItems} current="Contato" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />
            
            <FormContent content={conteudos[0]} full={true} idioma={idioma.codigo} />

            <BlockContent content={contentContacts} />

            <BlockContent content={contentPartners} />

            <BlockContent content={contentNewsletters} />

            <BlockContent content={contentDepartments} />
        </AdminLayout>
    );
};

export default Page;
