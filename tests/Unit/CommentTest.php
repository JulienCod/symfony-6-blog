<?php

namespace App\Tests\Unit;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommentTest extends KernelTestCase
{
    public function getComment(): Comment
    {
        return (new Comment())
            ->setAuthor('Auteur')
            ->setContent('Aenean vulputate eleifend tellus. Fusce risus nisl, viverra et, tempor et, pretium in, sapien. In auctor lobortis lacus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Phasellus dolor.

                Aenean massa. In auctor lobortis lacus. Vivamus aliquet elit ac nisl. Suspendisse pulvinar, augue ac venenatis condimentum, sem libero volutpat nibh, nec pellentesque velit pede quis nunc. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum.
                
                Ut non enim eleifend felis pretium feugiat. Morbi vestibulum volutpat enim. Cras varius. Aenean imperdiet. Fusce risus nisl, viverra et, tempor et, pretium in, sapien.
                
                Nullam accumsan lorem in dui. Praesent venenatis metus at tortor pulvinar varius. Vestibulum suscipit nulla quis orci. Sed in libero ut nibh placerat accumsan. Curabitur at lacus ac velit ornare lobortis.
                
                Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce ac felis sit amet ligula pharetra condimentum. Etiam sit amet orci eget eros faucibus tincidunt. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Donec orci lectus, aliquam ut, faucibus non, euismod id, nulla.');

    }

    public function testIsTrueComment()
    {
        $comment = $this->getComment();
        $comment->setContent('contenu');

        $this->assertTrue($comment->getAuthor() === 'Auteur');
        $this->assertTrue($comment->getContent() === 'contenu');
    }

    public function testIsFalseComment()
    {
        $comment = $this->getComment();
        $comment->setContent('contenu');
        $comment->setCreatedAt(new \DateTimeImmutable());

        $this->assertFalse($comment->getAuthor() === 'false');
        $this->assertFalse($comment->getContent() === 'false');
        $this->assertFalse($comment->getCreatedAt() === new \DateTimeImmutable());
    }

    public function testIsEmptyComment()
    {
        $comment = new Comment();

        $this->assertEmpty($comment->getId());
    }

    public function assertHasErrors(Comment $comment, int $number = 0 )
    {
        self::bootKernel();
        $errors = static::getContainer()->get('validator')->validate($comment);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error){
            $messages[] = $error->getPropertyPath().' -> '.$error->getMessage();
        }
        $this->assertCount($number, $errors, implode(',', $messages));
    }

    public function testValidEntityComment()
    {
        $this->assertHasErrors($this->getComment(),0);
    }

    public function testBlankAuthorComment()
    {
        $this->assertHasErrors($this->getComment()->setAuthor(''),2);
    }

    public function testMinLengthAuthorComment()
    {
        $this->assertHasErrors($this->getComment()->setAuthor('a'),1);
    }

    public function testMaxLengthAuthorComment()
    {
        $this->assertHasErrors($this->getComment()->setAuthor('poazfhpoazfhezpofhzpfozhfzpfuzpfbzfpzbfpzafbuzpofbhzpfobzfpzbufpzfbzpfbzpfbzpfbzpfbzfpzbfpzfbzifpzfuzofuhpfouzpfbzfpzbfp'),1);
    }

    public function testBlankContentComment()
    {
        $this->assertHasErrors($this->getComment()->setContent(''),2);
    }

    public function testMinLengthContentComment()
    {
        $this->assertHasErrors($this->getComment()->setContent('a'),1);
    }
}