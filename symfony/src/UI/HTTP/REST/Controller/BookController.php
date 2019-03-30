<?php

declare(strict_types=1);

namespace App\UI\HTTP\REST\Controller;

use App\Application\Command\Book\Create\CreateBookCommand;
use App\Application\Command\Book\Delete\DeleteBookCommand;
use App\Infrastructure\Author\Query\Projections\AuthorView;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\UI\HTTP\REST\Common\Form\BookTypeForm;
use App\UI\HTTP\REST\Common\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BookController extends RestController
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createBookAction(Request $request): JsonResponse
    {
        $form = $this->createForm(BookTypeForm::class);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            /** @var CategoryView $category */
            $category = $data['category'];
            /** @var AuthorView $author */
            $author = $data['author'];
            $command = new CreateBookCommand(
                $data['name'],
                $data['description'],
                $category->getId(),
                $author->getId()
            );
            $this->system->handle($command);

            return new JsonResponse('ok', 200);
        }
        $erros = $this->getErrorMessages($form);

        return new JsonResponse($erros, 400);
    }

    public function deleteBookAction(Request $request, string $book): JsonResponse
    {
        $command = new DeleteBookCommand($book);
        $this->system->handle($command);

        return new JsonResponse('ok', 200);
    }
}
