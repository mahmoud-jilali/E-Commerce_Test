"use client";

import { useEffect, useState } from 'react';
import Sidenav from "@/app/Components/sidenav/sidenav";

export default function Categories() {
    const [categories, setCategories] = useState<any>([]);
    const [ajoutModal, setAjoutModal] = useState(false);
    const [editionModal, setEditionModal] = useState(false);
    const [editionId, setEditionId] = useState<number | null>(null);
    const [categorieInfo, setCategorieInfo] = useState({id: null, nom: '', description: '' });

    async function getCategories() {
        const res = await fetch('http://localhost:8000/api/categories');
        const data = await res.json();
        setCategories(data);
    }

    const ajouterCategorie = async () => {
        const res = await fetch('http://localhost:8000/api/categories', {
            method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    nom: categorieInfo.nom,
                    description: categorieInfo.description
                })
            })
            setAjoutModal(false)
            getCategories()
        }
        useEffect(() => {
            getCategories();
        }, []);

        const handleEditClick = (categorie: any) => {
            setEditionId(categorie.id);
            setCategorieInfo({
                id: categorie.id,
                nom: categorie.name,
                description: categorie.description
            });
            openEditionModal()
        };

        const modifierCategorie = async(id: number) => {
            await fetch(`http://localhost:8000/api/categories/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nom: categorieInfo.nom,
                description: categorieInfo.description
            }),
        });
        setEditionId(null);
        await getCategories();
        setEditionModal(false)
        }

        const supprimerCategorie = async (id: number) => {
        const response = await fetch(`http://localhost:8000/api/categories/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        await getCategories();
        }

        const openAjoutModal = () => {
            setAjoutModal(true);
        };

        const closeAjoutModal = () => {
            setAjoutModal(false);
        };

        const openEditionModal = () => {
            setEditionModal(true);
        };

        const closeEditionModal = () => {
            setEditionModal(false);
        };

    return (
        <>
            <div className="min-h-screen bg-gray-100">
                <div className="flex">
                    <Sidenav />
                    <div className="flex-1 lg:ml-64">
                        <main className="p-6">
                            <div className="flex justify-between md:grid-cols-2 lg:grid-cols-4 gap-6 my-5">
                                <h1 className="mx-7 text-4xl text-blue-500">Categories</h1>
                                <button type="button" onClick={openAjoutModal} 
                                    className="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
                                    hover:bg-gradient-to-br font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 cursor-pointer flex">
                                    <span className="mx-2">Ajouter</span>
                                </button>
                            </div>
                            <div className="relative overflow-x-auto my-5">
                                {categories.length === 0 ? (
                                    <div className="col-span-2 text-center p-8">
                                        <div className="rounded-lg p-6">
                                            <h2 className="text-2xl font-semibold text-red-500">Aucun categorie trouvé</h2>
                                        </div>
                                    </div>
                                ) : (
                                    <table className="w-full text-sm text-left rtl:text-right text-gray-500">
                                        <thead className="text-xs text-gray-700 uppercase bg-gray-50">
                                            <tr>
                                                <th scope="col" className="px-6 py-3">
                                                    Nom
                                                </th>
                                                <th scope="col" className="px-6 py-3">
                                                    Description
                                                </th>
                                                <th scope="col" className="px-6 py-3">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {categories.map((categorie: any) => (
                                                <tr key={categorie.id} className="bg-white border-b border-gray-200">
                                                    <td className="px-6 py-4 font-medium text-gray-900 whitespace-nowra">
                                                        {categorie.name}
                                                    </td>
                                                    <td className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                        {categorie.description}
                                                    </td>
                                                    <td className="px-6 py-4">
                                                        <div className="flex items-center space-x-3">
                                                            <button type="button" onClick={() => handleEditClick(categorie)}
                                                                className="font-medium text-green-500 hover:text-green-700 cursor-pointer">
                                                                Modifier
                                                            </button>
                                                            <button type="button" onClick={() => supprimerCategorie(categorie.id)}
                                                                className="font-medium text-red-500 hover:text-red-700 cursor-pointer">
                                                                Supprimer
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                )}
                            </div>
                        </main>
                    </div>
                </div>
            </div>
            {ajoutModal && !editionModal && (
                <div className="absolute top-40 left-0 right-0 z-50 flex items-center justify-center">
                    <div className="bg-gray-200 p-6 rounded-lg w-[35%] mx-auto">
                        <h2 className="text-xl font-semibold mb-4">Ajouter Categorie</h2>
                        <input type="text" value={categorieInfo.nom} onChange={(e) => setCategorieInfo({...categorieInfo, nom: e.target.value})}
                            placeholder="Nom" className="w-full p-2 mb-2 border rounded"/>
                        <input type="text" value={categorieInfo.description} onChange={(e) => setCategorieInfo({...categorieInfo, description: e.target.value})}
                            placeholder="Description" className="w-full p-2 mb-2 border rounded"/>
                        <div className="flex justify-end gap-2">
                            <button
                                onClick={closeAjoutModal}
                                className="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700 cursor-pointer">Annuler
                            </button>
                            <button
                                onClick={ajouterCategorie}
                                className="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-700 cursor-pointer">Ajouter
                            </button>
                        </div>
                    </div>
                </div>
            )}
            {editionModal && !ajoutModal && (
                <div className="absolute top-40 left-0 right-0 z-50 flex items-center justify-center">
                    <div className="bg-gray-200 p-6 rounded-lg w-[35%] mx-auto">
                        <h2 className="text-xl font-semibold mb-4">Modifier categorie</h2>
                        <input type="text" value={categorieInfo.nom} onChange={(e) => setCategorieInfo({...categorieInfo, nom: e.target.value})}
                            placeholder="Nom" className="w-full p-2 mb-2 border rounded"/>
                        <input type="text" value={categorieInfo.description} onChange={(e) => setCategorieInfo({...categorieInfo, description: e.target.value})}
                            placeholder="Description" className="w-full p-2 mb-2 border rounded"/>
                        <div className="flex justify-end gap-2">
                            <button
                                onClick={closeEditionModal}
                                className="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700 cursor-pointer">Annuler
                            </button>
                            <button
                                onClick={() => categorieInfo.id && modifierCategorie(categorieInfo.id)}
                                className="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-700 cursor-pointer">Modifier
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </>
    );
}