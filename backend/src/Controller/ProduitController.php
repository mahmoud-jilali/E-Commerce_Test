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
        $categorieNom = $request['categorie'];
        $categorie = $entityManger->getRepository(Categorie::class)->findOneBy(['nom' => $categorieNom]);

        if (!$categorie) {
            return $this->json("Le catégorie " . $categorieNom . " n'existe pas", 404);
        }

        $produit->setCategorie($categorie);
        $produit->setNom($request['nom']);
        $produit->setQuantite($request['quantite']);
        $produit->setPrix($request['prix']);
        $produit->setDescription($request['description']);
        $entityManger->persist($produit);
        $entityManger->flush();

        return $this->json([
            'categorie' => $categorie->getNom(),
            'nom' => $produit->getNom(),
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
        $categorieNom = $request['categorie'];
        if (!$produit) {
            return $this->json('Aucun produit trouvé', Response::HTTP_NOT_FOUND);
        }

        if (isset($request['categorie'])) {
            $categorie = $entityManager->getRepository(Categorie::class)->findOneBy(['nom' => $categorieNom]);
            if (!$categorie) {
                return $this->json("La catégorie " . $categorieNom . " n'existe pas", Response::HTTP_NOT_FOUND);
            }
            $produit->setCategorie($categorie);
        }

        if (isset($request['nom'])) $produit->setNom($request['nom']);
        if (isset($request['description'])) $produit->setDescription($request['description']);
        if (isset($request['quantite'])) $produit->setQuantite($request['quantite']);
        if (isset($request['prix'])) $produit->setPrix($request['prix']);

        $entityManager->flush();
        
        return $this->json([
            'id' => $produit->getId(),
            'categorie' => $categorie->getNom(),
            'nom' => $produit->getNom(),
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
