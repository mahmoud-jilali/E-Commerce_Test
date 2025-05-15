<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class PanierController extends AbstractController
{
    #[Route('/panier', methods: ['POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $produit = $entityManager->getRepository(Produit::class)->find($data['produit_id']);
        if (!$produit) {
            return $this->json('Produit non trouvé', Response::HTTP_NOT_FOUND);
        }

        $panier = new Panier();
        $panier->setQuantite($data['quantite']);
        $panier->setTotal(($produit->getPrix() * $data['quantite']));

        if (isset($data['produit_id'])) {
            $produit = $entityManager->getRepository(Produit::class)->find($data['produit_id']);
            if (!$produit) {
                return $this->json('Produit non trouvé', Response::HTTP_NOT_FOUND);
            }
            $panier->addProduit($produit);
        }

        $entityManager->persist($panier);
        $entityManager->flush();

        return $this->json([
            'id' => $panier->getId(),
            'quantite' => $panier->getQuantite(),
            'total' => $panier->getTotal(),
            'produits' => array_map(function($produit) {
                return [
                    'id' => $produit->getId(),
                    'nom' => $produit->getNom(),
                    'prix' => $produit->getPrix(),
                ];
            }, $panier->getProduit()->toArray())
        ], Response::HTTP_CREATED);
    }

    #[Route('/panier/{id}', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $panier = $entityManager->getRepository(Panier::class)->find($id);
        
        if (!$panier) {
            return $this->json('Panier non trouvé', Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id' => $panier->getId(),
            'quantite' => $panier->getQuantite(),
            'total' => $panier->getTotal(),
            'produits' => array_map(function($produit) {
                return [
                    'id' => $produit->getId(),
                    'nom' => $produit->getNom(),
                    'prix' => $produit->getPrix(),
                    'description' => $produit->getDescription(),
                ];
            }, $panier->getProduit()->toArray())
        ]);
    }

    #[Route('/panier/{id}/ajt-produit', methods: ['POST'])]
    public function addProduct(EntityManagerInterface $entityManager, Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $panier = $entityManager->getRepository(Panier::class)->find($id);
        if (!$panier) {
            return $this->json('Panier non trouvé', Response::HTTP_NOT_FOUND);
        }

        $produit = $entityManager->getRepository(Produit::class)->find($data['produit_id']);
        if (!$produit) {
            return $this->json('Produit non trouvé', Response::HTTP_NOT_FOUND);
        }

        $panier->addProduit($produit);
        $panier->setQuantite($panier->getQuantite() + $data['quantite']);
        $newTotal = $panier->getTotal() + ($produit->getPrix() * $data['quantite']);
        $panier->setTotal($newTotal);

        $entityManager->flush();

        return $this->json([
            'message' => 'Produit ajouté au panier avec succès',
            'panier' => [
                'id' => $panier->getId(),
                'quantite' => $panier->getQuantite(),
                'total' => $panier->getTotal()
            ]
        ]);
    }

    #[Route('/panier/{id}/update', methods: ['PUT'])]
    public function update(EntityManagerInterface $entityManager, Request $request, int $id): JsonResponse
    {
        $panier = $entityManager->getRepository(Panier::class)->find($id);
        if (!$panier) {
            return $this->json('Panier non trouvé', Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['quantite'])) {
            $panier->setQuantite($data['quantite']);
        }

        if (isset($data['produits'])) {
            foreach ($panier->getProduit() as $produit) {
                $panier->removeProduit($produit);
            }

            foreach ($data['produits'] as $produitData) {
                $produit = $entityManager->getRepository(Produit::class)->find($produitData['id']);
                if ($produit) {
                    $panier->addProduit($produit);
                }
            }
        }

        $newTotal = '0.00';
        foreach ($panier->getProduit() as $produit) {
            $newTotal = bcadd($newTotal, bcmul($produit->getPrix(), $panier->getQuantite(), 2), 2);
        }
        $panier->setTotal($newTotal);

        $entityManager->flush();

        return $this->json([
            'message' => 'Panier mis à jour avec succès',
            'panier' => [
                'id' => $panier->getId(),
                'quantite' => $panier->getQuantite(),
                'total' => $panier->getTotal(),
                'produits' => array_map(function($p) {
                    return [
                        'id' => $p->getId(),
                        'nom' => $p->getNom(),
                        'prix' => $p->getPrix()
                    ];
                }, $panier->getProduit()->toArray())
            ]
        ]);
    }

    #[Route('/panier/{id}/sprm-produit/{produitId}', methods: ['delete'])]
    public function removeProduct(EntityManagerInterface $entityManager, int $id, int $produitId): JsonResponse
    {
        $panier = $entityManager->getRepository(Panier::class)->find($id);
        if (!$panier) {
            return $this->json('Panier non trouvé', Response::HTTP_NOT_FOUND);
        }

        $produit = $entityManager->getRepository(Produit::class)->find($produitId);
        if (!$produit) {
            return $this->json('Produit non trouvé', Response::HTTP_NOT_FOUND);
        }

        $panier->removeProduit($produit);

        if ($panier->getProduit()->isEmpty()) {
            $panier->setQuantite(0);
            $panier->setTotal('0.00');
        } else {
            $newQuantity = count($panier->getProduit());
            $panier->setQuantite($newQuantity);
            
            $newTotal = '0.00';
            foreach ($panier->getProduit() as $p) {
                $newTotal = bcadd($newTotal, $p->getPrix(), 2);
            }
            $panier->setTotal($newTotal);
        }

        $entityManager->flush();

        return $this->json([
            'message' => 'Produit supprimé du panier avec succès',
            'panier' => [
                'id' => $panier->getId(),
                'quantite' => $panier->getQuantite(),
                'total' => $panier->getTotal(),
                'produits' => array_map(function($p) {
                    return [
                        'id' => $p->getId(),
                        'nom' => $p->getNom(),
                        'prix' => $p->getPrix()
                    ];
                }, $panier->getProduit()->toArray())
            ]
        ]);
    }

    #[Route('/panier/{id}', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $panier = $entityManager->getRepository(Panier::class)->find($id);
        
        if (!$panier) {
            return $this->json('Panier non trouvé', Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($panier);
        $entityManager->flush();

        return $this->json('Panier supprimé avec succès');
    }
}