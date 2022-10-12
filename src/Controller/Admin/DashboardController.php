<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categorie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        $url = $adminUrlGenerator->setController(ArticleCrudController::class);
        $url = $adminUrlGenerator->generateUrl();
        // Rediriger vers cette URL
        return $this->redirect($url);

        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('My blog');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToUrl('Blog','fa fa-leaf',$this->generateUrl("app_articles"));
        yield MenuItem::section("Articles");
        // Créer un sous menu pour les articles
        yield MenuItem::subMenu("Action", "fa fa-bars")
                ->setSubItems([
                    MenuItem::linkToCrud("Lister article","fa fa-eye",Article::class)
                        ->setAction(Crud::PAGE_INDEX)
                        ->setDefaultSort(['createdAt' => 'DESC']),
                    MenuItem::linkToCrud("Ajouter article","fa fa-plus",Article::class)
                            ->setAction(Crud::PAGE_NEW)
                ]);
        yield MenuItem::section("Catégories");
        yield MenuItem::subMenu("Action", "fa fa-bars")
            ->setSubItems([
                MenuItem::linkToCrud("Lister catégorie","fa fa-eye",Categorie::class)
                    ->setAction(Crud::PAGE_INDEX),
                MenuItem::linkToCrud("Ajouter catégorie","fa fa-plus",Categorie::class)
                    ->setAction(Crud::PAGE_NEW)
            ]);
    }
}
