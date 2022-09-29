<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;
    // Demander à symfony d'injecter une instance de articleRepository
    // a la création du controleur (instance de ArticleController)
    public function __construct(ArticleRepository $articleRepository){
        $this->articleRepository = $articleRepository;
    }

    #[Route('/articles', name: 'app_articles')]
    // a l'appel de la methode getArticle, symfony va créer un objet de la classe ArticleRepository et le passer en parametre de la methode
    // ce mécanisme s'appelle l'injection de dépendances

    public function getArticles(): Response
    {
        // Récuperer les infos dans la bdd
        // le controller fait appel au modele (une classe du modele) afin de recup la liste des articles
        // $repository = new ArticleRepository();
        $articles = $this->articleRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('article/index.html.twig',[
            "articles" => $articles
        ]);
    }
    #[Route('article/{slug}', name:'app_article_slug')]
    public function getArticleBySlug($slug): Response
    {
        $article = $this->articleRepository->findOneBy(["slug"=>$slug]);
        return $this->render('article/article.html.twig',[
            "article" => $article
        ]);
    }
}
