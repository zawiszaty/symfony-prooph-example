<?php

declare(strict_types=1);

namespace App\UI\HTTP\REST\Controller;

use App\Application\Command\Category\ChangeName\ChangeCategoryNameCommand;
use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Application\Command\Category\Delete\DeleteCategoryCommand;
use App\Infrastructure\Category\Query\Repository\MysqlCategoryRepository;
use App\Infrastructure\Common\System\System;
use App\UI\HTTP\REST\Common\Form\CategoryTypeForm;
use App\UI\HTTP\REST\Common\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends RestController
{
    /**
     * @var MysqlCategoryRepository
     */
    private $categoryRepository;

    public function __construct(System $system)
    {
        parent::__construct($system);
    }

    public function createCategoryAction(Request $request): JsonResponse
    {
        $form = $this->createForm(CategoryTypeForm::class);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $command = new CreateCategoryCommand($data['name']);
            $this->system->handle($command);

            return new JsonResponse('ok', 200);
        }
        $erros = $this->getErrorMessages($form);

        return new JsonResponse($erros, 400);
    }

    public function changeCategoryNameAction(Request $request, string $category): JsonResponse
    {
        $form = $this->createForm(CategoryTypeForm::class);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $command = new ChangeCategoryNameCommand($category, $data['name']);
            $this->system->handle($command);

            return new JsonResponse('ok', 200);
        }
        $erros = $this->getErrorMessages($form);

        return new JsonResponse($erros, 400);
    }

    public function deleteCategoryAction(Request $request, string $category): JsonResponse
    {
        $command = new DeleteCategoryCommand($category);
        $this->system->handle($command);

        return new JsonResponse('ok', 200);
    }
}
