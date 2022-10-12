<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    public function getArticles(PaginatorInterface $paginator, Request $request): Response
    {
        // Récuperer les infos dans la bdd
        // le controller fait appel au modele (une classe du modele) afin de recup la liste des articles
        // $repository = new ArticleRepository();

        // mise en place de la pagination
        $articles = $paginator->paginate(
            $this->articleRepository->findBy(['publie'=>'true'], ['createdAt' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('article/index.html.twig',[
            "articles" => $articles
        ]);
    }
    #[Route('articles/{slug}', name:'app_article_slug')]
    public function getArticleBySlug($slug): Response
    {
        $article = $this->articleRepository->findOneBy(["slug"=>$slug]);
        return $this->render('article/article.html.twig',[
            "article" => $article
        ]);
    }
    #[Route('articles/nouveau', name:'app_articles_nouveau',methods: ['GET','POST'],priority: 1,)]
    public function insert(SluggerInterface $slugger, Request $request) : Response {
        $article = new Article();
        // Création du formulaire
        $formArticle = $this->createForm(ArticleType::class,$article);

        //Reconnaitre si le formulaire a été soumis ou pas
        $formArticle->handleRequest($request);
        // est-ce que le formulaire a été soumis ?
        if ($formArticle->isSubmitted() && $formArticle->isValid()){
            $article->setSlug($slugger->slug($article->getTitre())->lower());
            $article->setCreatedAt(new \DateTime());
            // Insérer l'article dans la BDD
            $this->articleRepository->add($article,true);
            return $this->redirectToRoute("app_articles");
        }
        // Appel de la vue twig permettant d'afficher le formulaire
        return $this->renderForm('article/nouveau.html.twig',[
            'formArticle'=>$formArticle
        ]);
        /*$article->setTitre('Nouvel article 2');
        $article->setContenu('Contenu du nouvel article 2');
        $article->setSlug($slugger->slug($article->getTitre())->lower());
        $article->setCreatedAt(new \DateTime());
        // Symfony 6
        $this->articleRepository->add($article,true);
        return $this->redirectToRoute('app_articles');
        */
    }
}
