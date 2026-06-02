import React, { useEffect } from 'react';
import { Link, usePage, useForm } from '@inertiajs/react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faWarehouse , faSave, faArrowLeft } from '@fortawesome/free-solid-svg-icons';

import AdminLayout from '@/Layouts/AdminLayout';
import { Breadcrumb } from '@/Components/Manager/Breadcrumb';
import { FormGroup } from '@/Components/Manager/Inputs/FormGroup';

const Page = () => {
    const { idioma, produtosCategorias, opcionais } = usePage().props;

    const { data, setData, post, processing, errors } = useForm();

    const breadcrumbItems = [
        { label: 'Segmentos', link: 'Manager.Segmentos.index' },
        { label: 'Produtos', link: 'Manager.Produtos.index' },
    ];

    const inputItems = [
        [{ titulo: 'Nome', name: 'nome', tamanho: 'col-span-12 lg:col-span-4', tipo: 'texto', max: 120 }, { titulo: 'Vídeo', name: 'video', tamanho: 'col-span-12 lg:col-span-4', tipo: 'link' }],
        [{ titulo: 'Categorias', name: 'produtos_categorias', tamanho: 'col-span-12 lg:col-span-4', tipo: 'select', isMulti: true, options: produtosCategorias }, { titulo: 'Opcionais', name: 'opcionais', tamanho: 'col-span-12 lg:col-span-4', tipo: 'select', isMulti: true, options: opcionais }],
        [{ titulo: 'Descrição', name: 'descricao', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', editor: true, 'toolbar': ['Bold', 'Italic'], max: 1080 }],
        [{ titulo: 'Imagem', name: 'img', tamanho: 'col-span-12 md:col-span-6 lg:col-span-4', tipo: 'imagem', crop: false, largura: 1280, altura: 720 }],
        [{ titulo: 'Título página', name: 'titulo_pagina', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto', max: 70 }],
        [{ titulo: 'Descrição página', name: 'descricao_pagina', tamanho: 'col-span-12 lg:col-span-8', tipo: 'texto_longo', 'editor': false, max: 220 }]
    ];

    const initializeData = (inputItems) => {
        let initialData = {};
        inputItems.forEach(group => {
            group.forEach(item => {
                initialData[item.name] = item.tipo === 'check' ? false : '';
            });
        });
        return initialData;
    };

    useEffect(() => {
        const initialData = initializeData(inputItems);
        setData(initialData);
    }, []); 

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('Manager.Produtos.novo'), {
            preserveScroll: true
        });
        console.log(data);

        console.log(errors);
    };

    const onChange = (name, value) => {
        console.log(name)
        setData(name, value);
    };

    const handleImageCrop = (croppedImage, fileExtension, name) => {
        setData(prevData => ({
            ...prevData,
            [name]: croppedImage
        }));

        const resizeBlobImage = (blob, scale = 0.5) => {
            return new Promise((resolve) => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    const newWidth = img.width * scale;
                    const newHeight = img.height * scale;

                    canvas.width = newWidth;
                    canvas.height = newHeight;
                    ctx.drawImage(img, 0, 0, newWidth, newHeight);

                    canvas.toBlob(resizedBlob => {
                        resolve(resizedBlob);
                    }, blob.type);
                };

                img.src = URL.createObjectURL(blob);
            });
        };

        resizeBlobImage(croppedImage).then(resizedBlob => {
            setData(prevData => ({
                ...prevData,
                [`${name}_alt`]: resizedBlob
            }));
        });
    };

    return (
        <AdminLayout>
            <Breadcrumb icon={faWarehouse} items={breadcrumbItems} current="Adicionar" idioma={idioma.codigo} />

            <div className="mb-6 rounded-sm border border-stroke bg-white px-5 py-5 shadow-md">
                <div className="mt-10">
                    <form onSubmit={handleSubmit}>
                        {inputItems.map((group, groupIndex) => (
                            <div key={groupIndex} className="grid grid-cols-12 gap-x-6">
                                {group.map((input, index) => (
                                    <div key={index} className={`w-full ${input.tamanho}`}>
                                        <FormGroup
                                            input={input}
                                            idioma={idioma}
                                            value={data[input.name]}
                                            onChange={onChange}
                                            handleImageCrop={handleImageCrop}
                                        />
                                        {errors[input.name] && <p className="text-sm text-red-500 -mt-5 mb-3">{errors[input.name]}</p>}
                                    </div>
                                ))}
                            </div>
                        ))}

                        <div className="flex items-center justify-end">
                            <Link href={route('Manager.Produtos.index')} className="flex items-center w-fit rounded-lg border border-red-700 text-red-700 px-3 py-2 mr-3 cursor-pointer transition-all hover:bg-red-100">
                                <FontAwesomeIcon icon={faArrowLeft} className="mr-2" />
                                Voltar
                            </Link>

                            <button
                                type="submit"
                                className="block relative w-fit rounded-lg border border-gray-300 px-3 py-2 cursor-pointer transition-all hover:bg-slate-200"
                            >   
                                <FontAwesomeIcon icon={faSave} className="text-slate-700 mr-2" />
                                Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AdminLayout>
    );
};

export default Page;