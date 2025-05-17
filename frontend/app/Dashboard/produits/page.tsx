"use client";

import { useEffect, useState } from 'react';
import Sidenav from "@/app/Components/sidenav/sidenav";

export default function Produits() {

    const [produits, setProduits] = useState<any>([]);
    const [categories, setCategories] = useState<any>([]);
    const [ajoutModal, setAjoutModal] = useState(false);
    const [editionModal, setEditionModal] = useState(false);
    const [editionId, setEditionId] = useState<number | null>(null);
    const [produitInfo, setProduitInfo] = useState({id: null, categorie: '', nom: '', quantite: '', 
        prix: '', description: '' })

    async function getProduits() {
        const res = await fetch('http://localhost:8000/api/produits');
        const data = await res.json();
        setProduits(data);
    }

    async function getCategories() {
        const res = await fetch('http://localhost:8000/api/categories');
        const data = await res.json();
        setCategories(data);
    }

    const ajouterProduit = async () => {
        const res = await fetch('http://localhost:8000/api/produits', {
            method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    categorie: produitInfo.categorie,
                    nom: produitInfo.nom,
                    quantite: produitInfo.quantite,
                    prix: produitInfo.prix,
                    description: produitInfo.description
                })
            })
            setAjoutModal(false)
            getProduits()
        }
        useEffect(() => {
            getProduits();
            getCategories();
        }, []);

        const handleEditClick = (produit: any) => {
            setEditionId(produit.id);
            setProduitInfo({
                id: produit.id,
                categorie: produit.categorie,
                nom: produit.nom,
                quantite: produit.quantite,
                prix: produit.prix,
                description: produit.description
            });
            openEditionModal()
        };

        const modifierProduit = async(id: number) => {
            await fetch(`http://localhost:8000/api/produits/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                categorie: produitInfo.categorie,
                nom: produitInfo.nom,
                quantite: produitInfo.quantite,
                prix: produitInfo.prix,
                description: produitInfo.description
            }),
        });
        setEditionId(null);
        await getProduits();
        setEditionModal(false)
        }

        const supprimerProduit = async (id: number) => {
        const response = await fetch(`http://localhost:8000/api/produits/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        await getProduits();
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

    return(
        <>
            <div className="min-h-screen bg-gray-100">
                <div className="flex">
                    <Sidenav />
                    <div className="flex-1 lg:ml-64">
                        <main className="p-6">
                            <div className="flex justify-between md:grid-cols-2 lg:grid-cols-4 gap-6 my-5">
                                <h1 className="mx-7 text-4xl text-blue-500">Produits</h1>
                                <button type="button" onClick={openAjoutModal}
                                className="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
                                hover:bg-gradient-to-br font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 cursor-pointer flex">
                                <span className="mx-2">Ajouter</span></button>
                            </div>
                            <div className="relative overflow-x-auto my-5">
                                {produits.length === 0 ? (
                                    <div className="col-span-2 text-center p-8">
                                        <div className="rounded-lg p-6">
                                            <h2 className="text-2xl font-semibold text-red-500">Aucun produit trouvé</h2>
                                        </div>
                                    </div>
                                ) : (
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500">
                                    <thead className="text-xs text-gray-700 uppercase bg-gray-50">
                                        <tr>
                                            <th scope="col" className="px-6 py-3">
                                                Categorie
                                            </th>
                                            <th scope="col" className="px-6 py-3">
                                                Nom
                                            </th>
                                            <th scope="col" className="px-6 py-3">
                                                Quantité
                                            </th>
                                            <th scope="col" className="px-6 py-3">
                                                Prix
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
                                        {produits.map((produit: any) => (
                                        <tr key={produit.id} className="bg-white border-b border-gray-200">
                                            <td className="px-6 py-4 font-medium text-blue-700 whitespace-nowrap">
                                                {produit.categorie}
                                            </td>
                                            <td className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                {produit.nom}
                                            </td>
                                            <td className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                               {produit.quantite}
                                            </td>
                                            <td className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                {produit.prix}
                                            </td>
                                            <td className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                {produit.description}
                                            </td>
                                            <td className="px-6 py-4">
                                                <div className="flex items-center space-x-3">
                                                    <button type="button" onClick={() => handleEditClick(produit)}
                                                        className="font-medium text-green-500 hover:text-green-700 cursor-pointer">
                                                        Modifier
                                                    </button>
                                                    <button type="button" onClick={() => supprimerProduit(produit.id)}
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
                        <h2 className="text-xl font-semibold mb-4">Ajouter Produit</h2>
                        <select className="w-full p-2 mb-2 border rounded" onChange={(e) => setProduitInfo({...produitInfo, categorie: e.target.value})}>
                            <option selected disabled>Nom de categorie</option>
                            {categories.map((categorie: any) => (
                                <option key={categorie.id} value={categorie.name}>{categorie.name}</option>
                            ))}
                        </select>
                        <input type="text" value={produitInfo.nom} onChange={(e) => setProduitInfo({...produitInfo, nom: e.target.value})}
                            placeholder="Nom" className="w-full p-2 mb-2 border rounded"/>
                        <input type="number" value={produitInfo.quantite} onChange={(e) => setProduitInfo({...produitInfo, quantite: e.target.value})}
                            placeholder="0" className="w-full p-2 mb-2 border rounded"/>
                        <input type="text" value={produitInfo.prix} onChange={(e) => setProduitInfo({...produitInfo, prix: e.target.value})}
                            placeholder="Prix" className="w-full p-2 mb-2 border rounded"/>
                        <input type="text" value={produitInfo.description} onChange={(e) => setProduitInfo({...produitInfo, description: e.target.value})}
                            placeholder="Description" className="w-full p-2 mb-2 border rounded"/>
                        <div className="flex justify-end gap-2">
                            <button
                                onClick={closeAjoutModal}
                                className="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700 cursor-pointer">Annuler
                            </button>
                            <button
                                onClick={ajouterProduit}
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
                        <select className="w-full p-2 mb-2 border rounded" onChange={(e) => setProduitInfo({...produitInfo, categorie: e.target.value})}>
                            <option selected disabled>Nom de categorie</option>
                            {categories.map((categorie: any) => (
                                <option key={categorie.id} value={categorie.name}>{categorie.name}</option>
                            ))}
                        </select>
                        <input type="text" value={produitInfo.nom} onChange={(e) => setProduitInfo({...produitInfo, nom: e.target.value})}
                            placeholder="Nom" className="w-full p-2 mb-2 border rounded"/>
                        <input type="number" value={produitInfo.quantite} onChange={(e) => setProduitInfo({...produitInfo, quantite: e.target.value})}
                            placeholder="0" className="w-full p-2 mb-2 border rounded"/>
                        <input type="text" value={produitInfo.prix} onChange={(e) => setProduitInfo({...produitInfo, prix: e.target.value})}
                            placeholder="Prix" className="w-full p-2 mb-2 border rounded"/>
                        <input type="text" value={produitInfo.description} onChange={(e) => setProduitInfo({...produitInfo, description: e.target.value})}
                            placeholder="Description" className="w-full p-2 mb-2 border rounded"/>
                            <div className="flex justify-end gap-2">
                            <button
                                onClick={closeEditionModal}
                                className="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700 cursor-pointer">Annuler
                            </button>
                            <button
                                onClick={() => produitInfo.id && modifierProduit(produitInfo.id)}
                                className="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-700 cursor-pointer">Modifier
                            </button>
                        </div>
                        </div>
                    </div>
                )}
        </>
    )
}