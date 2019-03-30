<?php

declare(strict_types=1);

namespace Tests\UI\HTTP\REST\Controller;

use App\Infrastructure\Author\Query\Projections\AuthorView;
use App\Infrastructure\Book\Query\Projections\BookView;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    public function test_it_create_book()
    {
        $this->client->request('POST', '/api/author', ['name' => 'test']);
        $author = $this->manager->getRepository(AuthorView::class)->findOneBy(['name' => 'test'])->getId();
        $this->client->request('POST', '/api/category', ['name' => 'test']);
        $category = $this->manager->getRepository(CategoryView::class)->findOneBy(['name' => 'test'])->getId();
        $this->client->request('POST', '/api/book', ['name' => 'test', 'description' => 'test', 'author' => $author, 'category' => $category]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function test_it_validate_create_book()
    {
        $this->client->request('POST', '/api/author', ['name' => 'test']);
        $author = $this->manager->getRepository(AuthorView::class)->findOneBy(['name' => 'test'])->getId();
        $this->client->request('POST', '/api/category', ['name' => 'test']);
        $category = $this->manager->getRepository(CategoryView::class)->findOneBy(['name' => 'test'])->getId();
        $this->client->request('POST', '/api/book', ['description' => 'test', 'author' => $author, 'category' => $category]);
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->client->request('POST', '/api/book', ['name' => 'test', 'author' => $author, 'category' => $category]);
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->client->request('POST', '/api/book', ['name' => 'test', 'description' => 'test', 'category' => $category]);
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->client->request('POST', '/api/book', ['name' => 'test', 'description' => 'test', 'author' => $author]);
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function test_it_delete_book()
    {
        $this->client->request('POST', '/api/author', ['name' => 'test']);
        $author = $this->manager->getRepository(AuthorView::class)->findOneBy(['name' => 'test'])->getId();
        $this->client->request('POST', '/api/category', ['name' => 'test']);
        $category = $this->manager->getRepository(CategoryView::class)->findOneBy(['name' => 'test'])->getId();
        $this->client->request('POST', '/api/book', ['name' => 'test', 'description' => 'test', 'author' => $author, 'category' => $category]);
        $id = $this->manager->getRepository(BookView::class)->findOneBy(['name' => 'test'])->getId();
        $this->client->request('DELETE', "/api/book/$id");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
