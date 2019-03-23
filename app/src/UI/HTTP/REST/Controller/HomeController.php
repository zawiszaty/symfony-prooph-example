<?php

declare(strict_types=1);

namespace App\UI\HTTP\REST\Controller;

use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Application\Command\Category\Delete\DeleteCategoryCommand;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\UI\HTTP\REST\Common\RestController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends RestController
{
    public function homeAction()
    {
//        $command = new CreateCategoryCommand('test');
//        $this->system->handle($command);
//        return new Response('success');
        /** @var CategoryView $category */
        $category = $this->getDoctrine()->getRepository(CategoryView::class)->findOneBy(['name' => 'test']);
        $command = new DeleteCategoryCommand($category->getId());
        $this->system->handle($command);

        return new Response('success');
    }
}
