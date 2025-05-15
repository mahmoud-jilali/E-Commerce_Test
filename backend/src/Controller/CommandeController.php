<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class CommandeController extends AbstractController
{
    #[Route('/commandes', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManger): JsonResponse
    {
        $commande = $entityManger->getRepository(Commande::class)->findAll();

        $data = [];
        foreach ($commande as $com) {
            $data[] = [
                'id' => $com->getId(),
                'nom' => $com->getNom(),
                'telephone' => $com->getTelephone(),
                'email' => $com->getEmail(),
                'quantite' => $com->getQuantite(),
                'total' => $com->getTotal(),
                'produits' => array_map(function($produit) {
                    return [
                        'id' => $produit->getId(),
                        'nom' => $produit->getNom(),
                        'description' => $produit->getDescription(),
                    ];
                }, $com->getProduit()->toArray())
            ];
        }

        return $this->json($data);
    }

    #[Route('/commandes/{id}',  methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManger): JsonResponse
    {
        $commande = $entityManger->getRepository(Commande::class)->find($id);

        if (!$commande) {
            return $this->json('Aucune produit trouvée', 404);
        }

        return $this->json([
            'id' => $commande->getId(),
            'nom' => $commande->getNom(),
            'telephone' => $commande->getTelephone(),
            'email' => $commande->getEmail(),
            'quantite' => $commande->getQuantite(),
            'total' => $commande->getTotal(),
            'produits' => array_map(function($produit) {
                return [
                    'id' => $produit->getId(),
                    'nom' => $produit->getNom(),
                    'description' => $produit->getDescription(),
                ];
            }, $commande->getProduit()->toArray())
        ]);
    }

    #[Route('/commandes', methods: ['POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
       $data = json_decode($request->getContent(), true);

        $commande = new Commande();
        $commande->setNom($data['nom']);
        $commande->setTelephone($data['telephone']);
        $commande->setEmail($data['email']);
        $commande->setQuantite($data['quantite']);

        $total = 0;
        if (isset($data['produits'])) {
            foreach ($data['produits'] as $produitId) {
                $produit = $entityManager->getRepository(Produit::class)->find($produitId);
                if ($produit) {
                    $commande->addProduit($produit);
                    $total += $produit->getPrix() * $data['quantite'];
                }
            }
        }

        $commande->setTotal((string)$total);
        $entityManager->persist($commande);
        $entityManager->flush();

        return $this->json([
            'id' => $commande->getId(),
            'nom' => $commande->getNom(),
            'telephone' => $commande->getTelephone(),
            'email' => $commande->getEmail(),
            'quantite' => $commande->getQuantite(),
            'total' => $commande->getTotal(),
            'produits' => array_map(function($produit) {
                return [
                    'id' => $produit->getId(),
                    'nom' => $produit->getNom(),
                    'prix' => $produit->getPrix(),
                    'description' => $produit->getDescription(),
                ];
            }, $commande->getProduit()->toArray())
        ], Response::HTTP_CREATED);
    }

    #[Route('/commandes/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $commande = $entityManager->getRepository(Commande::class)->find($id);
        
        if (!$commande) {
            return $this->json('Commande non trouvée', Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['nom'])) {
            $commande->setNom($data['nom']);
        }
        if (isset($data['telephone'])) {
            $commande->setTelephone($data['telephone']);
        }
        if (isset($data['email'])) {
            $commande->setEmail($data['email']);
        }
        if (isset($data['quantite'])) {
            $commande->setQuantite($data['quantite']);
        }

        if (isset($data['produits'])) {
            foreach ($commande->getProduit() as $produit) {
                $commande->removeProduit($produit);
            }

            $total = 0;
            foreach ($data['produits'] as $produitId) {
                $produit = $entityManager->getRepository(Produit::class)->find($produitId);
                if ($produit) {
                    $commande->addProduit($produit);
                    $total += $produit->getPrix() * $commande->getQuantite();
                }
            }
            $commande->setTotal((string)$total);
        }

        $entityManager->flush();

        return $this->json([
            'id' => $commande->getId(),
            'nom' => $commande->getNom(),
            'telephone' => $commande->getTelephone(),
            'email' => $commande->getEmail(),
            'quantite' => $commande->getQuantite(),
            'total' => $commande->getTotal(),
            'produits' => array_map(function($produit) {
                return [
                    'id' => $produit->getId(),
                    'nom' => $produit->getNom(),
                    'prix' => $produit->getPrix(),
                    'description' => $produit->getDescription(),
                ];
            }, $commande->getProduit()->toArray())
        ]);
    }

    #[Route('/commandes/{id}', methods:['delete'] )]
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $commande = $entityManager->getRepository(Commande::class)->find($id);
    
        if (!$commande) {
            return $this->json('Aucune commande trouvée', 404);
        }
    
        $entityManager->remove($commande);
        $entityManager->flush();
    
        return $this->json('La commande ' .$id. ' a été supprimée avec succès', 200);
    }
}
