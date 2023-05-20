<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterPageTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Le blog du Dévellopeur');

        $button = $crawler->filter('.bg-blue-500');
        $this->assertEquals(1, count($button));
    }
}
