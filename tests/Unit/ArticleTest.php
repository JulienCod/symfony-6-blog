<?php

namespace App\Tests\Unit;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArticleTest extends KernelTestCase
{
    public function getArticle(): Article 
    {
        return (new Article())
            ->setTitle('titre de l\'article')
            ->setAuthor('Auteur')
            ->setContent('Aenean vulputate eleifend tellus. Fusce risus nisl, viverra et, tempor et, pretium in, sapien. In auctor lobortis lacus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Phasellus dolor.

            Aenean massa. In auctor lobortis lacus. Vivamus aliquet elit ac nisl. Suspendisse pulvinar, augue ac venenatis condimentum, sem libero volutpat nibh, nec pellentesque velit pede quis nunc. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum.
            
            Ut non enim eleifend felis pretium feugiat. Morbi vestibulum volutpat enim. Cras varius. Aenean imperdiet. Fusce risus nisl, viverra et, tempor et, pretium in, sapien.
            
            Nullam accumsan lorem in dui. Praesent venenatis metus at tortor pulvinar varius. Vestibulum suscipit nulla quis orci. Sed in libero ut nibh placerat accumsan. Curabitur at lacus ac velit ornare lobortis.
            
            Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce ac felis sit amet ligula pharetra condimentum. Etiam sit amet orci eget eros faucibus tincidunt. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Donec orci lectus, aliquam ut, faucibus non, euismod id, nulla.')
            ->setImage('image')
            ->setSlug('titre de l\'article')
            ->setStatus('Actif');
    }

    public function testIsTrueArticle()
    {
        $article = $this->getArticle();
        $article->setContent('contenu');

        $this->assertTrue($article->getTitle() === 'titre de l\'article'); 
        $this->assertTrue($article->getAuthor() === 'Auteur'); 
        $this->assertTrue($article->getContent() === 'contenu'); 
        $this->assertTrue($article->getImage() === 'image'); 
        $this->assertTrue($article->getStatus() === 'Actif');
        $this->assertTrue($article->getSlug() === 'titre de l\'article');
    }

    public function testIsFalseArticle()
    {

        $article = $this->getArticle();
        $article->setContent('contenu');
        $article->setCreatedAt(new \DateTimeImmutable());
        $article->setUpdatedAt(new \DateTimeImmutable());

        $this->assertFalse($article->getTitle() === 'false'); 
        $this->assertFalse($article->getAuthor() === 'false'); 
        $this->assertFalse($article->getContent() === 'false'); 
        $this->assertFalse($article->getImage() === 'false'); 
        $this->assertFalse($article->getStatus() === 'false');
        $this->assertFalse($article->getSlug() === 'false');
        $this->assertFalse($article->getCreatedAt() === new \DateTimeImmutable());
        $this->assertFalse($article->getUpdatedAt() === new \DateTimeImmutable());
    }

    public function testEmptyArticle()
    {
        $article = new Article();

        $this->assertEmpty($article->getId());
        $this->assertEmpty($article->getCategory());
        $this->assertEmpty($article->getComments());
        $this->assertEmpty($article->getImage());
        $this->assertEmpty($article->getUser());
    }

    public function assertHasErrors(Article $article, int $number = 0 )
    {
        self::bootKernel();
        $errors = static::getContainer()->get('validator')->validate($article);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error){
            $messages[] = $error->getPropertyPath().' -> '.$error->getMessage();
        }
        $this->assertCount($number, $errors, implode(',', $messages));
    }

    public function testEntityArticleValide(): void
    {
        $this->assertHasErrors($this->getArticle(), 0);
    }
    
    public function testBlankTitle()
    {
        $this->assertHasErrors($this->getArticle()->setTitle(''), 2);
    }

    public function testMinLengthTitleInvalid()
    {
        $this->assertHasErrors($this->getArticle()->setTitle('azer'), 1);
    }
    
    public function testMaxLengthTitleInvalid()
    {
        $this->assertHasErrors($this->getArticle()->setTitle('Nullam dictum felis eu pede mollis pretium. Sed aliquam ultrices mauris. Praesent vestibulum dapibus nibh. Duis vel nibh at velit scelerisque suscipit. Vestibulum eu odio.
        
        Praesent ut ligula non mi varius sagittis. Sed in libero ut nibh placerat accumsan. Donec orci lectus, aliquam ut, faucibus non, euismod id, nulla. Curabitur ligula sapien, tincidunt non, euismod vitae, posuere imperdiet, leo. Nulla neque dolor, sagittis eget, iaculis quis, molestie non, velit.'), 1);
        
    }
    
    public function testBlankContent()
    {
        $this->assertHasErrors($this->getArticle()->setContent(''), 2);
    }
    
    public function testMinContentInvalid()
    {
        $this->assertHasErrors($this->getArticle()->setContent('azertyuiopoiuytreza arteyrufi ruejfkdl ofieurfj d'), 1);
    }

    public function testBlankAuthor()
    {
        $this->assertHasErrors($this->getArticle()->setAuthor(''), 2);
    }

    public function testMinLengthAuthor()
    {
        $this->assertHasErrors($this->getArticle()->setAuthor('a'), 1);
    }

    public function testMaxLengthAuthor()
    {
        $this->assertHasErrors($this->getArticle()->setAuthor('azertyuiopoiuytrezaazertyuiopoiuytrezaazertyuiopoiuytrezaazertyuiopoiuytreza'), 1);
    }

    public function testBlankStatus()
    {
        $this->assertHasErrors($this->getArticle()->setStatus(''), 2);
    }
    public function testStatusValid()
    {
        $choices =["Actif", "Brouillon", "Archive", "Inactif"];
        foreach($choices as $choice)
        {
            $this->assertHasErrors($this->getArticle()->setStatus($choice), 0);
        }
    }
    public function testStatusInvalid()
    {
        $choices =["actif", "brouillon", "archive", "inactif"];
        foreach($choices as $choice)
        {
            $this->assertHasErrors($this->getArticle()->setStatus($choice), 1);
        }
    }

    public function testAddRemoveComment()
    {
        $article = $this->getArticle();
        $comment = new Comment();

        $this->assertEmpty($article->getComments());
        $article->addComment($comment);
        $this->assertContains($comment, $article->getComments());
        $article->removeComment($comment);
        $this->assertEmpty($article->getComments());
    }

    public function testAddRemoveCategory()
    {
        $article = $this->getArticle();
        $category = new Category();

        $this->assertEmpty($article->getCategory());
        $article->addCategory($category);
        $this->assertContains($category, $article->getCategory());
        $article->removeCategory($category);
        $this->assertEmpty($article->getCategory());
    }
}