<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Article;
use App\DataFixtures\UsersFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 1; $i <= 10; $i++){
            $article = (new Article())
                ->setTitle($faker->title())
                ->setContent($faker->text(500))
                ->setAuthor($faker->name())
                ->setImage($faker->image())
                ->setStatus('Actif');
            $user = $this->getReference('user-'.rand(1,8));
            $article->setUser($user);
    
            $category = $this->getReference('cat-'.rand(1, 3));
            $article->addCategory($category);
            $manager->persist($article);
            $this->setReference('art-'.$i, $article);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UsersFixtures::class,
            CategoryFixtures::class,
        );
    }
}
