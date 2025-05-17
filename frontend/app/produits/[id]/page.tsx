"use client";

import Navbar from '@/app/Components/navbar/navbar';
import { useEffect, useState } from 'react';
import { useParams } from 'next/navigation';

export default function ProduitDetail() {
    const [produit, setProduit] = useState<any>(null);
    const params = useParams();

    useEffect(() => {
        async function getProduitDetail() {
            try {
                const res = await fetch(`http://localhost:8000/api/produits/${params.id}`);
                const data = await res.json();
                setProduit(data);
            } catch (error) {
                console.error('Error fetching product:', error);
            }
        }
        getProduitDetail();
    }, [params.id]);

    if (!produit) {
        return <div className="text-center p-8">Produit introuvable</div>;
    }

    return (
        <>
            <Navbar />
            <div className="container mx-auto p-8 mt-16 w-[75%]">
                <div className="bg-gray-200 shadow-lg rounded-lg p-6">
                    <div className="md:w-1/2">
                        <h1 className="text-2xl font-bold my-4">{produit.categorie}</h1>
                        <h1 className="text-3xl font-light my-4">{produit.nom}</h1>
                        <p className="text-black my-4"><span>Quantité : </span>{produit.quantite}</p>
                        <p className="text-gray-600 my-4">{produit.description}</p>
                        <p className="text-2xl font-bold text-blue-500 my-4">
                            {produit.prix} MAD
                        </p>
                    </div>
                </div>
            </div>
        </>
    );
}