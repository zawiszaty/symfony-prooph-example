<?php

declare(strict_types=1);

namespace App\Infrastructure\Common\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueValueInEntity.
 */
class UniqueValueInEntity extends Constraint
{
    /**
     * @var string
     */
    public $message;
    public $entityClass;
    public $field;

    /**
     * @return array
     */
    public function getRequiredOptions(): array
    {
        return ['entityClass', 'field'];
    }

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return \get_class($this).'Validator';
    }
}
