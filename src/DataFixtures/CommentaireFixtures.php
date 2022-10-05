<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Commentaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentaireFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        for ($i=0;$i<50;$i++) {
            $commentaire = new Commentaire();
            $commentaire->setContenu($faker->paragraph(4, true));
            $commentaire->setCreatedAt($faker->dateTimeBetween('-6 months'));
            $numArticle = $faker->numberBetween(1,10);
            $commentaire->setArticle($this->getReference("article".$numArticle));
            $numUtilisateur = $faker->numberBetween(1,8);
            $commentaire->setUtilisateurs($this->getReference("utilisateur".$numUtilisateur));
            $manager->persist($commentaire);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            ArticleFixtures::class,
            UtilisateurFixtures::class
        ];
    }
}
