<?php

declare(strict_types=1);

namespace App\UI\HTTP\REST\Common;

use App\Infrastructure\Common\System\System;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;

class RestController extends AbstractController
{
    /**
     * @var System
     */
    protected $system;

    public function __construct(System $system)
    {
        $this->system = $system;
    }

    /**
     * @return array
     */
    protected function getErrorMessages(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }
        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
}
