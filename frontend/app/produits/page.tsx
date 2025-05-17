"use client";

import Link from 'next/link';
import { useEffect, useState } from 'react';

export default function Produits() {
    const [produits, setProduits] = useState([]);
    const [quantites, setQuantite] = useState<{ [key: number]: number }>({});
    useEffect(() => {
        async function getProduits() {
            const res = await fetch('http://localhost:8000/api/produits')
            const data = await res.json()
            setProduits(data)
        }
        getProduits()
    }, [])

    const quantiteChange = (produitId: number, value: number) => {
        setQuantite(prev => ({
            ...prev,
            [produitId]: value
        }));
    };

    const ajouterAuPanier = async (produitId: number, quantite: number) => {
        const qte = quantites[produitId] || 0;
        const response = await fetch('http://localhost:8000/api/panier', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    produit_id: produitId,
                    quantite: qte
                })
            });
        const data = await response.json();
    }
    return (
        <>
            <h1 className="text-4xl font-bold py-7 text-center">Produits</h1>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 p-4 my-5">
            {(!produits || produits.length === 0) ? (
                <div className="col-span-2 text-center p-8">
                    <div className="rounded-lg p-6">
                        <h2 className="text-2xl font-semibold text-red-500">Aucun produit trouvé</h2>
                    </div>
                </div>
            ) : (
                Array.isArray(produits) && produits.length > 0
            )}
            {produits.map((produit: any) => (
            <div key={produit.id} className="bg-white shadow-md rounded-lg mx-auto w-lg">
                <div className="flex flex-col items-center p-4">
                    <h2 className="text-xl font-semibold my-2">{produit.nom}</h2>
                    <p className="text-gray-600 my-2">{produit.description}</p>
                    <p className="text-lg font-bold text-blue-500 my-2">{produit.prix} MAD</p>
                    <div className='flex flex-col-2 my-2'>
                        <p className='text-black my-auto mx-2'>Quantité : </p>
                        <input type="number" className='bg-gray-50 border border-gray-300 text-black text-sm rounded-lg p-2' placeholder='0'
                            min="1"
                            value={quantites[produit.id] || ''}
                            onChange={(e) => quantiteChange(produit.id, parseInt(e.target.value))}/>
                    </div>
                </div>
                <div className='flex flex-col-3'>
                    <Link href={`/produits/${produit.id}`} className='mx-auto my-5'>
                        <button className='bg-gray-500 cursor-pointer text-white rounded-lg p-2 hover:bg-gray-700'>Détails</button>
                    </Link>
                    <button onClick={() => ajouterAuPanier(produit.id, produit.quantite)}
                    className='bg-blue-500 mx-auto my-5 cursor-pointer text-white rounded-lg p-2 hover:bg-blue-700'>Ajouter au panier</button> 
                </div>
            </div>
            ))}
        </div>
        </>
    );
}