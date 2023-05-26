<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Article;
use App\DataFixtures\UsersFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * @codeCoverageIgnore
 */
class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 1; $i <= 50; $i++){
            $article = (new Article())
                ->setTitle($faker->name())
                ->setContent($faker->text(500))
                ->setAuthor($faker->name())
                ->setImage($faker->image())
                ->setStatus('Actif');
            $article->setslug($this->slugger->slug($article->getTitle())->lower());
            $user = $this->getReference('user-'.rand(1,8));
            $article->setUser($user);
    
            $category = $this->getReference('cat-'.rand(1, 9));
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
