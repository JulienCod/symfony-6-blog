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
            ->setslug('name')
            ->setCategoryOrder(1);
    }
    public function getCategory1(): Category
    {
        return (new Category())
            ->setName('name1')
            ->setslug('name1')
            ->setCategoryOrder(2);
    }

    public function testIsTrueCategory()
    {
        $category1 = $this->getCategory1();
        $category = $this->getCategory();
        $category->setParent($category1);

        $this->assertTrue($category->getCategoryOrder() === 1);
        $this->assertTrue($category->getName() === 'name');
        $this->assertTrue($category->getSlug() === 'name');
        $this->assertTrue($category->getParent() === $category1);
        
    }
    
    public function testIsFalseCategory()
    {
        $category = $this->getCategory();
        $category->setCreatedAt(new \DateTimeImmutable());
        $category->setUpdatedAt(new \DateTimeImmutable());
    
        $this->assertFalse($category->getCategoryOrder() === 2);
        $this->assertFalse($category->getslug() === 'false');
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

    public function testAddRemoveCategory()
    {
        $category1 = $this->getCategory1();
        $category = $this->getCategory();

        $this->assertEmpty($category->getCategories());
        $category->addCategory($category1);
        $this->assertContains($category1, $category->getCategories());
        $category->removeCategory($category1);
        $this->assertEmpty($category->getCategories());
    }
}