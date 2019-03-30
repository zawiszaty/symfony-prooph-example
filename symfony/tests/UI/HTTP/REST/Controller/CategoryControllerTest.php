<?php

declare(strict_types=1);

namespace Tests\UI\HTTP\REST\Controller;

use App\Infrastructure\Category\Query\Projections\CategoryView;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    public function test_it_create_category()
    {
        $this->client->request('POST', '/api/category', ['name' => 'test']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function test_it_validate_create_category()
    {
        $this->client->request('POST', '/api/category', []);
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->client->request('POST', '/api/category', ['name' => 'test']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->client->request('POST', '/api/category', ['name' => 'test']);
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function test_it_change_category_name()
    {
        $this->client->request('POST', '/api/category', ['name' => 'test']);
        $id = $this->manager->getRepository(CategoryView::class)->findOneBy(['name' => 'test'])->getId();
        $this->client->request('PATCH', "/api/category/$id", ['name' => 'test2']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        /** @var CategoryView $category */
        $category = $this->manager->getRepository(CategoryView::class)->findOneBy(['name' => 'test2']);
        $this->assertSame($category->getName(), 'test2');
    }

    public function test_it_validate_change_category_name()
    {
        $this->client->request('POST', '/api/category', ['name' => 'test']);
        $id = $this->manager->getRepository(CategoryView::class)->findOneBy(['name' => 'test'])->getId();
        $this->client->request('PATCH', "/api/category/$id", ['name' => 'test']);
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function test_it_delete_category()
    {
        $this->client->request('POST', '/api/category', ['name' => 'test']);
        $id = $this->manager->getRepository(CategoryView::class)->findOneBy(['name' => 'test'])->getId();
        $this->client->request('DELETE', "/api/category/$id");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
