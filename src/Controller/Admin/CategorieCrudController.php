<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorieCrudController extends AbstractCrudController
{
    private SluggerInterface $slugger;
    //Injection du slugger au niveau du constructeur
    public function __construct(SluggerInterface $slugger){
        $this->slugger = $slugger;
    }
    public static function getEntityFqcn(): string
    {
        return Categorie::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextField::new('slug')->hideOnForm()
        ];
    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Verifier que l'entity instance est bien une instance de la classe article
        if (!$entityInstance instanceof Categorie) return;
        $entityInstance->setSlug($this->slugger->slug($entityInstance->getTitre())->lower());
        // Appel a la methode herite afin de persister l'entite
        parent::persistEntity($entityManager,$entityInstance);
    }

}
