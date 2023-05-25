<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class CategoryFixtures extends Fixture
{
    private $counter = 1;
    public function load(ObjectManager $manager): void
    {
        $this->createCategory('ordinateur portable', 'tous ce qui se rapporte aux ordinateurs portables', $manager);
        $this->createCategory('écran', 'tous ce qui se rapporte aux écrans', $manager);
        $this->createCategory('souris', 'tous ce qui se rapporte aux souris', $manager);
        $this->createCategory('clavier portable', 'tous ce qui se rapporte auxclavier', $manager);
        $manager->flush();
    }
    
    public function createCategory(string $name, string $description,ObjectManager $manager)
    {
        $category = new Category();
        $category->setName($name);
        $category->setDescription($description);
        $manager->persist($category);
    
        $this->addReference('cat-'.$this->counter, $category);
        $this->counter++;
    
        return $category;
    }
}
