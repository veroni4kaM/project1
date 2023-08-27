<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Doctrine\Common\Annotations\Annotation\Attribute;

/**
 * @Annotation
 */
#[\Attribute] class AccountConstraint extends Constraint
{
    /**
     * @return string
     */
    public function validatedBy() : string
    {
        return get_class($this) . "Validator";
    }

    /**
     * @return array|string|string[]
     */
    public function getTargets(): array|string
    {
        return self::CLASS_CONSTRAINT;
    }}