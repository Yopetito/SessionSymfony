<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Entity\Categorie;
use App\Repository\ModuleRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    
    #[Route('/module/add', name: 'add_module')]
    public function addModule(Module $module = null, ModuleRepository $moduleRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $module = $form->getData();
            $entityManager->persist($module);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie');
        }

        return $this->render('module/addmodule.html.twig', [
            'formAddModule' => $form
        ]);
    }

    #[Route('/detailsCategorie/{id}', name: 'app_modules')]
    public function modulesInCategorie(CategorieRepository $categorieRepository,int $id, ModuleRepository $moduleRepository): Response
    {

        $categorie = $categorieRepository->find($id);

        $modules = $moduleRepository->findBy(['categorie' => $id]);
        return $this->render('module/index.html.twig', [
            'modules' => $modules,
            'categorie' => $categorie
        ]);
    }

    #[Route('/categorie/{id}/delete', name: 'delete_categorie')]
    public function deleteCategorie(Categorie $categorie, EntityManagerInterface $em)
    {
        $em->remove($categorie);
        $em->flush();

        return $this->redirectToRoute('app_categorie');
    }


    #[Route('/module/{id}/delete', name: 'delete_module')]
    public function deleteModule(Module $module, EntityManagerInterface $em)
    {
        $em->remove($module);
        $em->flush();

        return $this->redirectToRoute('app_categorie');
    }

}
