<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i < 50; $i++){
            $comment = (new Comment())
                ->setContent($faker->text(200))
                ->setAuthor($faker->name());
            $article = $this->getReference('art-'.rand(1, 10));
            $comment->setArticle($article);
            $manager->persist($comment);

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ArticleFixtures::class
        );
    }
}
