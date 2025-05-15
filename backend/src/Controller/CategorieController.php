<?php

namespace App\Controller;

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]

class CategorieController extends AbstractController
{
    #[Route('/categories', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManger): JsonResponse
    {
        $categorie = $entityManger->getRepository(Categorie::class)->findAll();
        $data = [];
        foreach ($categorie as $cat) {
            $data[] = [
                'id' => $cat->getId(),
                'name' => $cat->getNom(),
                'description' => $cat->getDescription(),
            ];
        }
        return $this->json($data);
    }

    #[Route('/categories/{id}', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManger, int $id): JsonResponse
    {
        $categorie = $entityManger->getRepository(Categorie::class)->find($id);
        if (!$categorie) {
            return $this->json('Aucune catégorie trouvée', 404);
        }
        return $this->json([
            'id' => $categorie->getId(),
            'name' => $categorie->getNom(),
            'description' => $categorie->getDescription(),
        ]);
    }

    #[Route('/categories', methods: ['POST'])]
    public function create(EntityManagerInterface $entityManger, Request $request): JsonResponse
    {
        $request = json_decode($request->getContent(), true);

        $categorie = new Categorie();
        $categorie->setNom($request['nom']);
        $categorie->setDescription($request['description']);
        $entityManger->persist($categorie);
        $entityManger->flush();

        return $this->json([
            'nom' => $categorie->getNom(),
            'description' => $categorie->getDescription(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/categories/{id}', methods:['put'] )]
    public function update(EntityManagerInterface $entityManager, Request $request, int $id): JsonResponse
    {
        $request = json_decode($request->getContent(), true);

        $categorie = $entityManager->getRepository(Categorie::class)->find($id);
    
        if (!$categorie) {
            return $this->json('Aucune catégorie trouvée', 404);
        }
    
        $categorie->setNom($request['nom']);
        $categorie->setDescription($request['description']);
        $entityManager->flush();
            
        return $this->json([
            'nom' => $categorie->getNom(),
            'description' => $categorie->getDescription(),
        ]);
    }

    #[Route('/categories/{id}', methods:['delete'] )]
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $categorie = $entityManager->getRepository(Categorie::class)->find($id);
    
        if (!$categorie) {
            return $this->json('Aucune catégorie trouvée', 404);
        }
    
        $entityManager->remove($categorie);
        $entityManager->flush();
    
        return $this->json('La catégorie ' .$id. ' a été supprimée avec succès', 200);
    }
}
