import Navbar from "./Components/navbar/navbar";
import Produits  from "./produits/page";

export default function Home() {
  return (
    <>
      <Navbar />
      <div className="mt-16">
        <Produits />
      </div> 
    </>
  );
}
