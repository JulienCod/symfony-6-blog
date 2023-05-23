<?php

namespace App\Tests\Unit;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{
    public function getCategory(): Category
    {
        return (new Category())
            ->setName('name')
            ->setDescription('In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Etiam ut purus mattis mauris sodales aliquam. Pellentesque auctor neque nec urna. Phasellus ullamcorper ipsum rutrum nunc. Sed fringilla mauris sit amet nibh.');    
    }

    public function testIsTrueCategory()
    {
        $category = $this->getCategory();
        $category->setDescription('description');

        $this->assertTrue($category->getDescription() === 'description');
        $this->assertTrue($category->getName() === 'name');
        
    }
    
    public function testIsFalseCategory()
    {
        $category = $this->getCategory();
        $category->setDescription('description');
        $category->setCreatedAt(new \DateTimeImmutable());
        $category->setUpdatedAt(new \DateTimeImmutable());
    
        $this->assertFalse($category->getDescription() === 'false');
        $this->assertFalse($category->getName() === 'false');
        $this->assertFalse($category->getCreatedAt() === new \DateTimeImmutable());
        $this->assertFalse($category->getUpdatedAt() === new \DateTimeImmutable());

    }

    public function testIsEmptyCategory()
    {
        $this->assertEmpty($this->getCategory()->getId());
    }

    public function assertHasErrors(Category $category, int $number = 0 )
    {
        self::bootKernel();
        $errors = static::getContainer()->get('validator')->validate($category);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error){
            $messages[] = $error->getPropertyPath().' -> '.$error->getMessage();
        }
        $this->assertCount($number, $errors, implode(',', $messages));
    }

    public function testValidEntityCategory()
    {
        $this->assertHasErrors($this->getCategory(),0);
    }

    public function testBlankNameCategory()
    {
        $this->assertHasErrors($this->getCategory()->setName(''),2);
    }

    public function testMinLengthNameCategory()
    {
        $this->assertHasErrors($this->getCategory()->setName('a'),1);
    }

    public function testMaxLengthNameCategory()
    {
        $this->assertHasErrors($this->getCategory()->setName('aopzghnzogbnzobzgozbzbzzbfzofzfobzfozbfzofbzofbzfozbfzofbzopfbzfpozbfpozfbfobzfozfbzofbzfozebfozbfzofbzpfobzfob'),1);
    }

    public function testBlankDescriptionCategory()
    {
        $this->assertHasErrors($this->getCategory()->setDescription(''),2);
    }

    public function testMinLengthDescriptionCategory()
    {
        $this->assertHasErrors($this->getCategory()->setDescription('a'),1);
    }

    public function testMaxLengthDescriptionCategory()
    {
        $this->assertHasErrors($this->getCategory()->setName('aopzghnzogbnozefbnzepfobzefpozbfpezfbzpfzbfpofbzpfbzpfzbfzbfzfbzpfbzpfbezfpzbfpzebfpzebfpzebfzpofbzpefbzpfbzfbzpbzpfbezpfbzefpzebfpuzebgfpzbfpuozghfpouhzpfzbfpubzfpzbfpzbzobzgozbzbzzbfzofzfobzfozbfzofbzofbzfozbfzofbzopfbzfpozbfpozfbfobzfozfbzofbzfozebfozbfzofbzpfobzfob'),1);
    }

    public function testAddRemoveArticleCategory() 
    {
        $category = $this->getCategory();
        $article = new Article();

        $this->assertEmpty($category->getArticles());
        $category->addArticle($article);
        $this->assertContains($article, $category->getArticles());
        $category->removeArticle($article);
        $this->assertEmpty($category->getArticles());
    }
}