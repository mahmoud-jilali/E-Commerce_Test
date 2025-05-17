import Link from 'next/link';

export default function Sidenav() {
    return(
        <>
            <aside className="hidden lg:flex w-64 flex-col fixed h-screen bg-blue-400 shadow-lg">
                <nav className="flex-1 overflow-y-auto">
                    <ul className="py-4">
                        <li className="px-6 py-3 hover:bg-gray-100">
                            <Link href="/Dashboard/categories" className="flex items-center space-x-2">
                                Categories
                            </Link>
                        </li>
                        <li className="px-6 py-3 hover:bg-gray-100">
                            <Link href="/Dashboard/produits" className="flex items-center space-x-2">
                                Produits
                            </Link>
                        </li>
                        <li className="px-6 py-3 hover:bg-gray-100">
                            <Link href="/Dashboard/commandes" className="flex items-center space-x-2">
                                Commandes
                            </Link>
                        </li>
                        <li className="px-6 py-3 hover:bg-gray-100">
                            <Link href="/" className="flex items-center space-x-2">
                                Frontend
                            </Link>
                        </li>
                    </ul>
                </nav>
            </aside>
        </>
    );
}