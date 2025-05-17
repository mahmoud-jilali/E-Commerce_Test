"use client";

import { useEffect, useState } from 'react';
import Navbar from '../Components/navbar/navbar';
import CheckoutPage from '../Components/CheckoutPage';
import convertToSubcurrency from '@/lib/convertToSubcurrency';
import { Elements } from '@stripe/react-stripe-js';
import { loadStripe } from '@stripe/stripe-js';

if(process.env.NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY === undefined) {
    throw new Error('NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY is not defined');
}

const stripePromise = loadStripe(process.env.NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY);

export default function Panier() {

    const [paniers, setPanier] = useState([]);
    const [editingId, setEditingId] = useState<number | null>(null);
    const [editQuantite, setEditQuantite] = useState<{ [key: number]: number }>({});
    
    const getPanier = async () => {
        const res = await fetch('http://localhost:8000/api/panier')
        const data = await res.json()
        setPanier(data.paniers)
    }

    useEffect(() => {
        getPanier()
    }, [])

    const handleEditClick = (panier: any) => {
        setEditingId(panier.id);
        setEditQuantite({ ...editQuantite, [panier.id]: panier.quantite });
    };

    const updatePanier = async (panier: any) => {
        await fetch(`http://localhost:8000/api/panier/${panier.id}/update`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                quantite: editQuantite[panier.id],
                produits: panier.produits.map((p: any) => ({ id: p.id }))
            }),
        });
        setEditingId(null);
        await getPanier();
    };

    const supprimerPanier = async (panierId: number) => {
        const response = await fetch(`http://localhost:8000/api/panier/${panierId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        await getPanier();
    }

    // ######## Commande ########
    
    const [clientInfo, setClientInfo] = useState({
        nom: '',
        telephone: '',
        email: ''
    });

    const amount = paniers.reduce((acc: number, panier: any) => {
        return acc + panier.total;
    }
    , 0);

    const [showCommandeForm, setShowCommandeForm] = useState(false);

    const createCommande = async (panier: any) => {
        const res = await fetch('http://localhost:8000/api/commandes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nom: clientInfo.nom,
                telephone: clientInfo.telephone,
                email: clientInfo.email,
                quantite: panier.quantite,
                produits: panier.produits.map((p: any) => p.id)
            }),
        })
        setShowCommandeForm(false);
        await supprimerPanier(panier.id);
    }

    return (
        <>
            <Navbar />
            <div className="mt-16">
                <h1 className="text-4xl font-bold py-7 text-center">Panier</h1>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 p-4 my-5">
               {(!paniers || paniers.length === 0) ? (
                <div className="col-span-2 text-center p-8">
                    <div className="rounded-lg p-6">
                        <h2 className="text-2xl font-semibold text-red-500">Votre panier est vide</h2>
                    </div>
                </div>
                ) : (
                Array.isArray(paniers) && paniers.map((panier: any) => (
                <div key={panier.id} className="bg-white shadow-md rounded-lg mx-auto w-lg">
                    <div className="flex flex-col items-center p-4">
                        <div className="text-lg font-bold text-blue-500 my-2">
                            {panier.produits && panier.produits.map((produit: any) => (
                                <div key={produit.id}>
                                    <p>{produit.nom} - {produit.prix} MAD</p>
                                </div>
                            ))}
                        </div>
                        {editingId === panier.id ? (
                            <input
                                type="number"
                                min="1"
                                value={editQuantite[panier.id]}
                                onChange={e => setEditQuantite({ ...editQuantite, [panier.id]: parseInt(e.target.value) })}
                                className="border rounded p-1 w-20 my-2"
                            />
                        ) : (
                            <h2 className="text-xl font-light my-2">Quantité: {panier.quantite}</h2>
                        )}
                        <p className="text-xl font-light my-2">Total: {panier.total}</p>
                    </div>
                    <div className='flex flex-col-3'>
                        <button 
                            onClick={() => setShowCommandeForm(true)}
                            className='bg-gray-500 mx-auto my-5 cursor-pointer text-white rounded-lg p-2 hover:bg-gray-700'>Commander</button>
                        {editingId === panier.id ? (
                            <button
                                onClick={() => updatePanier(panier)}
                                className='bg-green-500 mx-auto my-5 cursor-pointer text-white rounded-lg p-2 hover:bg-green-700'>Sauvegarder
                            </button>
                        ) : (
                            <button
                                onClick={() => handleEditClick(panier)}
                                className='bg-blue-500 mx-auto my-5 cursor-pointer text-white rounded-lg p-2 hover:bg-blue-700'>Modifier
                            </button>
                        )}
                        <button onClick={() => supprimerPanier(panier.id)}
                            className='bg-red-500 mx-auto my-5 cursor-pointer text-white rounded-lg p-2 hover:bg-red-700'>Supprimer</button> 
                    </div>
                </div>
                )))}
                </div>
            </div>
            {showCommandeForm && (
                <div className="absolute top-40 left-0 right-0 z-50 flex items-center justify-center">
                    <div className="bg-gray-200 p-6 rounded-lg w-[35%] mx-auto">
                        <h2 className="text-xl font-bold mb-4">Informations de commande</h2>
                        <p className="text-lg font-light mb-4">Total: {amount} MAD</p>
                        <input
                            type="text"
                            placeholder="Nom"
                            className="w-full p-2 mb-2 border rounded"
                            value={clientInfo.nom}
                            onChange={(e) => setClientInfo({...clientInfo, nom: e.target.value})}
                        />
                        <input
                            type="tel"
                            placeholder="Téléphone"
                            className="w-full p-2 mb-2 border rounded"
                            value={clientInfo.telephone}
                            onChange={(e) => setClientInfo({...clientInfo, telephone: e.target.value})}
                        />
                        <input
                            type="email"
                            placeholder="Email"
                            className="w-full p-2 mb-4 border rounded"
                            value={clientInfo.email}
                            onChange={(e) => setClientInfo({...clientInfo, email: e.target.value})}
                        />
                        <div className="bg-gray-200 p-6 rounded-lg w-[35%] mx-auto">
                        <Elements stripe={stripePromise} 
                            options={{
                                mode: 'payment',
                                amount: convertToSubcurrency(amount),
                                currency: 'aed',
                            }}>
                            <CheckoutPage amount={amount} />
                        </Elements>
                        </div>
                        <div className="flex justify-end gap-2">
                            <button
                                onClick={() => setShowCommandeForm(false)}
                                className="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700 cursor-pointer">Annuler
                            </button>
                            <button
                                onClick={() => createCommande(paniers[0])}
                                className="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-700 cursor-pointer">Confirmer
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </>
    );
}