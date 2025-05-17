"use client";

import { useEffect, useState } from 'react';
import Sidenav from "@/app/Components/sidenav/sidenav";
import { comma } from 'postcss/lib/list';

export default function Commandes() {

    const [commandes, setCommandes] = useState<any>([]);

    async function getCommandes() {
        const res = await fetch('http://localhost:8000/api/commandes');
        const data = await res.json();
        setCommandes(data);
    }

    useEffect(() => {
            getCommandes();
        }, []);

    return(
        <>
            <div className="min-h-screen bg-gray-100">
                <div className="flex">
                    <Sidenav />
                    <div className="flex-1 lg:ml-64">
                        <main className="p-6">
                            <div className="my-5">
                                <h1 className="mx-7 text-4xl text-blue-500">Commandes</h1>
                            </div>
                            <div className="relative overflow-x-auto my-5">
                                {commandes.length === 0 ? (
                                    <div className="col-span-2 text-center p-8">
                                        <div className="rounded-lg p-6">
                                            <h2 className="text-2xl font-semibold text-red-500">Aucun commande trouvé</h2>
                                        </div>
                                    </div>
                                ) : (
                                <table className="w-full text-sm text-left rtl:text-right text-gray-500">
                                    <thead className="text-xs text-gray-700 uppercase bg-gray-50">
                                        <tr>
                                            <th scope="col" className="px-6 py-3">
                                                Nom cliet
                                            </th>
                                            <th scope="col" className="px-6 py-3">
                                                Email cliet
                                            </th>
                                            <th scope="col" className="px-6 py-3">
                                                Telephone client
                                            </th>
                                            <th scope="col" className="px-6 py-3">
                                                Produit
                                            </th>
                                            <th scope="col" className="px-6 py-3">
                                                Quantité
                                            </th>
                                            <th scope="col" className="px-6 py-3">
                                                Total
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {commandes.map((commande: any) => (
                                        <tr key={commande.id} className="bg-white border-b border-gray-200">
                                            <th scope="row" className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                {commande.nom}
                                            </th>
                                            <td className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                {commande.email}
                                            </td>
                                            <td className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                {commande.telephone}
                                            </td>
                                            <td className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                {commande.produits[0]?.nom}
                                            </td>
                                            <td className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                {commande.quantite}
                                            </td>
                                            <td className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                {commande.total}
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
        </>
    )
}