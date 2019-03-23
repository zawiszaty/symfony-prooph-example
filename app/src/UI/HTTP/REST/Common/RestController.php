<?php

declare(strict_types=1);

namespace App\UI\HTTP\REST\Common;

use App\Infrastructure\Common\System\System;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}
