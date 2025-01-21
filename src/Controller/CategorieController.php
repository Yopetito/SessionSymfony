<?php

namespace App\Controller;

use App\Repository\ModuleRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/modules/{id}', name: 'app_modules')]
    public function modulesInCategorie(int $id, ModuleRepository $moduleRepository): Response
    {
        $modules = $moduleRepository->findBy(['categorie' => $id]);

        return $this->render('module/index.html.twig', [
            'modules' => $modules
        ]);
    }

}
