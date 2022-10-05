<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use Faker\Factory;

class CommentaireFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        for ($i=0;$i<200;$i++) {
            $commentaire = new Commentaire();
            $commentaire->setContenu($faker->paragraph(2, true));
            $commentaire->setCreatedAt($faker->dateTimeBetween('-6 months'));
            $numArticle = $faker->numberBetween(0,50);
            $commentaire->setArticle($this->getReference("article".$numArticle));
            $manager->persist($commentaire);
        }
        $manager->flush();
    }
}
