import Link from "next/link";


export default function Navbar() {
    return (
        <nav className='bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200'>
            <div className="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="/" className="flex items-center space-x-3 rtl:space-x-reverse">
                    <p className="self-center text-blue-400 text-2xl font-semibold whitespace-nowrap">E-Shop</p>
                </a>
                <div className="items-center justify-between hidden w-full md:flex md:w-auto md:order-1">
                    <ul className="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white">
                        <li>
                            <Link className="block py-2 px-3 text-white bg-blue-700 rounded-sm md:bg-transparent md:text-blue-700 md:p-0" href="/">Produits</Link>
                        </li>
                        <li>
                            <Link className="block py-2 px-3 text-white bg-blue-700 rounded-sm md:bg-transparent md:text-blue-700 md:p-0" href="/Dashboard/categories">
                                Back-office
                            </Link>
                        </li>
                        <li>
                            <Link className="block py-2 px-3 text-white bg-blue-700 rounded-sm md:bg-transparent md:text-blue-700 md:p-0" href="/panier">
                                Panier
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    );
}