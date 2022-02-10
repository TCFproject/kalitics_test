<?php

namespace App\Validator;

use DateTimeInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class Time extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Le temps choisie dépasse les 35h.';
    // in the base Symfony\Component\Validator\Constraint class

}
