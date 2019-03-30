<?php

declare(strict_types=1);

namespace Tests\UI\HTTP\REST\Controller;

use App\Infrastructure\Author\Query\Projections\AuthorView;
use Tests\TestCase;

class AuthorControllerTest extends TestCase
{
    public function test_it_create_author()
    {
        $this->client->request('POST', '/api/author', ['name' => 'test']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        /** @var AuthorView $author */
        $author = $this->manager->getRepository(AuthorView::class)->findOneBy(['name' => 'test']);
        $this->assertNotNull($author);
    }

    public function test_it_create_validatate_author()
    {
        $this->client->request('POST', '/api/author', []);
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->client->request('POST', '/api/author', ['name' => 'test']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->client->request('POST', '/api/author', ['name' => 'test']);
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function test_it_change_author_name()
    {
        $this->client->request('POST', '/api/author', ['name' => 'test']);
        $id = $this->manager->getRepository(AuthorView::class)->findOneBy(['name' => 'test'])->getId();
        $this->client->request('PATCH', "/api/author/$id", ['name' => 'test2']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        /** @var AuthorView $author */
        $author = $this->manager->getRepository(AuthorView::class)->findOneBy(['name' => 'test2']);
        $this->assertSame($author->getName(), 'test2');
    }

    public function test_it_validate_change_author_name()
    {
        $this->client->request('POST', '/api/author', ['name' => 'test']);
        $id = $this->manager->getRepository(AuthorView::class)->findOneBy(['name' => 'test'])->getId();
        $this->client->request('PATCH', "/api/author/$id", ['name' => 'test']);
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function test_it_delete_author()
    {
        $this->client->request('POST', '/api/author', ['name' => 'test']);
        $id = $this->manager->getRepository(AuthorView::class)->findOneBy(['name' => 'test'])->getId();
        $this->client->request('DELETE', "/api/author/$id");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
