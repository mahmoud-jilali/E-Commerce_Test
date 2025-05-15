<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ProduitController extends AbstractController
{
    #[Route('/produits', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManger): JsonResponse
    {
        $categorie = $entityManger->getRepository(Categorie::class)->findAll();
        $produit = $entityManger->getRepository(Produit::class)->findAll();
        $data = [];
        foreach ($produit as $prod) {
            $data[] = [
                'id' => $prod->getId(),
                'categorie' => $prod->getCategorie()->getNom(),
                'nom' => $prod->getNom(),
                'image1' => base64_encode($prod->getImage1()),
                'image2' => base64_encode($prod->getImage2()),
                'image3' => base64_encode($prod->getImage3()),
                'quantite' => $prod->getQuantite(),
                'prix' => $prod->getPrix(),
                'description' => $prod->getDescription(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/produits/{id}', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManger, int $id): JsonResponse
    {
        $categorie = $entityManger->getRepository(Categorie::class)->findAll();
        $produit = $entityManger->getRepository(Produit::class)->find($id);
        if (!$produit) {
            return $this->json('Aucune produit trouvée', 404);
        }
        return $this->json([
            'id' => $produit->getId(),
            'categorie' => $produit->getCategorie()->getNom(),
            'nom' => $produit->getNom(),
            'image1' => base64_encode($produit->getImage1()),
            'image2' => base64_encode($produit->getImage2()),
            'image3' => base64_encode($produit->getImage3()),
            'quantite' => $produit->getQuantite(),
            'prix' => $produit->getPrix(),
            'description' => $produit->getDescription(),
        ]);
    }

    #[Route('/produits', methods: ['POST'])]
    public function create(EntityManagerInterface $entityManger, Request $request): JsonResponse
    {
        $request = json_decode($request->getContent(), true);

        $produit = new Produit();
        $categorie = $entityManger->getRepository(Categorie::class)->find($request['categorie']);

        if (!$categorie) {
            return $this->json("Le catégorie " . $request['categorie'] . " n'existe pas", 404);
        }

        $produit->setCategorie($categorie);
        $produit->setNom($request['nom']);
        $produit->setImage1(base64_decode($request['image1']));
        $produit->setImage2(base64_decode($request['image2']));
        $produit->setImage3(base64_decode($request['image3']));
        $produit->setQuantite($request['quantite']);
        $produit->setPrix($request['prix']);
        $produit->setDescription($request['description']);
        $entityManger->persist($produit);
        $entityManger->flush();

        return $this->json([
            'categorie' => $produit->getCategorie()->getNom(),
            'nom' => $produit->getNom(),
            'image1' => base64_encode($produit->getImage1()),
            'image2' => base64_encode($produit->getImage2()),
            'image3' => base64_encode($produit->getImage3()),
            'quantite' => $produit->getQuantite(),
            'prix' => $produit->getPrix(),
            'description' => $produit->getDescription(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/produits/{id}', methods:['put'] )]
    public function update(EntityManagerInterface $entityManager, Request $request, int $id): JsonResponse
    {
        $request = json_decode($request->getContent(), true);

        $produit = $entityManager->getRepository(Produit::class)->find($id);
        if (!$produit) {
            return $this->json('Aucun produit trouvé', Response::HTTP_NOT_FOUND);
        }

        if (isset($request['categorie'])) {
            $categorie = $entityManager->getRepository(Categorie::class)->find($request['categorie']);
            if (!$categorie) {
                return $this->json("La catégorie " . $request['categorie'] . " n'existe pas", Response::HTTP_NOT_FOUND);
            }
            $produit->setCategorie($categorie);
        }

        if (isset($request['nom'])) $produit->setNom($request['nom']);
        if (isset($request['description'])) $produit->setDescription($request['description']);
        if (isset($request['image1'])) $produit->setImage1(base64_decode($request['image1']));
        if (isset($request['image2'])) $produit->setImage2(base64_decode($request['image2']));
        if (isset($request['image3'])) $produit->setImage3(base64_decode($request['image3']));
        if (isset($request['quantite'])) $produit->setQuantite($request['quantite']);
        if (isset($request['prix'])) $produit->setPrix($request['prix']);

        $entityManager->flush();
        
        return $this->json([
            'id' => $produit->getId(),
            'categorie' => $produit->getCategorie()->getNom(),
            'nom' => $produit->getNom(),
            'image1' => base64_encode($produit->getImage1()),
            'image2' => base64_encode($produit->getImage2()),
            'image3' => base64_encode($produit->getImage3()),
            'quantite' => $produit->getQuantite(),
            'prix' => $produit->getPrix(),
            'description' => $produit->getDescription(),
        ], Response::HTTP_OK);
    }

    #[Route('/produits/{id}', methods:['delete'] )]
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $produit = $entityManager->getRepository(Produit::class)->find($id);
    
        if (!$produit) {
            return $this->json('Aucune catégorie trouvée', 404);
        }
    
        $entityManager->remove($produit);
        $entityManager->flush();
    
        return $this->json('Le produit ' .$id. ' a été supprimée avec succès', 200);
    }
}
