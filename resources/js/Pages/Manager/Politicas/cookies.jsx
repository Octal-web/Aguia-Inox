import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faFileText } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { PageSettings } from '@/Components/Manager/PageSettings';
import { FormContent } from '@/Components/Manager/FormContent';

const Page = () => {
    // Content
    const { pagina, conteudos, idioma, idiomas } = usePage().props;

    const breadcrumbItems = [
        { label: 'Políticas', link: 'Politicas.privacidade' },
    ];

    return (
        <AdminLayout>
            <Breadcrumb icon={faFileText} items={breadcrumbItems} current="Cookies" idioma={idioma.codigo} idiomas={idiomas} />
            <PageSettings page={pagina} idioma={idioma.codigo} />

            <FormContent content={conteudos[0]} full={true} toolbar={['List']} idioma={idioma.codigo} />
        </AdminLayout>
    );
};

export default Page;
