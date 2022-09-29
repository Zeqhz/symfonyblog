<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    private CategorieRepository $categorieRepository;

    public function __construct(CategorieRepository $categorieRepository){
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/categorie', name: 'app_categorie')]
    public function getCategorie(): Response
    {
        $categories = $this->categorieRepository->findBy([], ['id']);
        return $this->render('categorie/categorie.html.twig', [
            "categories" => $categories
        ]);
    }
    #[Route('/categories', name: 'app_categories')]
    public function getCategories() : Response
    {
        $categories = $this->categorieRepository->findBy([], ['titre' => 'ASC']);
        return $this->render('categorie/categorie.html.twig', [
            "categories" => $categories
        ]);
    }
    #[Route('categorie/{slug}', name: 'app_categorie_slug')]
    public function getCategorieBySlug($slug) : Response
    {
        $categorie = $this->categorieRepository->findOneBy(["slug"=>$slug]);
        return $this->render('categorie/categories.html.twig', [
            "categorie" => $categorie
        ]);
    }
}
