<?php

declare(strict_types=1);

namespace App\UI\HTTP\REST\Controller;

use App\Application\Command\Author\ChangeName\ChangeAuthorNameCommand;
use App\Application\Command\Author\Create\CreateAuthorCommand;
use App\Application\Command\Author\Delete\DeleteAuthorCommand;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Author\Query\Projections\AuthorView;
use App\Infrastructure\Author\Query\Repository\MysqlAuthorRepository;
use App\Infrastructure\Common\System\System;
use App\UI\HTTP\REST\Common\Form\AuthorTypeForm;
use App\UI\HTTP\REST\Common\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends RestController
{
    /**
     * @var MysqlAuthorRepository
     */
    private $authorRepository;

    public function __construct(System $system, MysqlAuthorRepository $authorRepository)
    {
        parent::__construct($system);
        $this->authorRepository = $authorRepository;
    }

    public function createAuthorAction(Request $request): JsonResponse
    {
        $form = $this->createForm(AuthorTypeForm::class);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $command = new CreateAuthorCommand($data['name']);
            $this->system->handle($command);

            return new JsonResponse('ok', 200);
        }
        $erros = $this->getErrorMessages($form);

        return new JsonResponse($erros);
    }

    public function changeAuthorNameAction(Request $request, string $author): JsonResponse
    {
        $form = $this->createForm(AuthorTypeForm::class);
        $form->submit($request->request->all());
        /** @var AuthorView $author */
        $author = $this->authorRepository->oneByUuid(AggregateRootId::fromString($author));

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $command = new ChangeAuthorNameCommand($author->getId(), $data['name']);
            $this->system->handle($command);

            return new JsonResponse('ok', 200);
        }
        $erros = $this->getErrorMessages($form);

        return new JsonResponse($erros);
    }

    public function deleteAuthorAction(Request $request, string $author): JsonResponse
    {
        /** @var AuthorView $author */
        $author = $this->authorRepository->oneByUuid(AggregateRootId::fromString($author));
        $command = new DeleteAuthorCommand($author->getId());
        $this->system->handle($command);

        return new JsonResponse('ok', 200);
    }
}
