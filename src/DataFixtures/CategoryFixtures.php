<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @codeCoverageIgnore
 */
class CategoryFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger){}

    private $counter = 1;
    public function load(ObjectManager $manager): void
    {

        $parent = $this->createCategory('Informatique',null , $manager);
        $this->createCategory('ordinateur portable',$parent , $manager);
        $this->createCategory('écran',$parent , $manager);
        $this->createCategory('souris',$parent , $manager);
        $this->createCategory('clavier',$parent , $manager);

        $parent = $this->createCategory('Mode',null , $manager);
        $this->createCategory('femme',$parent , $manager);
        $this->createCategory('Homme',$parent , $manager);
        $this->createCategory('Enfant',$parent , $manager);



        $manager->flush();
    }
    
    public function createCategory(string $name,Category $parent = null, ObjectManager $manager)
    {
        $category = new Category();
        $category->setName($name);
        $category->setParent($parent);
        $category->setslug($this->slugger->slug($category->getName())->lower());
        $category->setCategoryOrder($this->counter);
        $manager->persist($category);
        
    
        $this->addReference('cat-'.$this->counter, $category);
        $this->counter++;
    
        return $category;
    }
}
