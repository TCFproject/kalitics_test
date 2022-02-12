<?php

namespace App\Validator;

use App\Entity\Pointages;
use App\Repository\PointagesRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\ConstraintValidator;

class TimeValidator extends ConstraintValidator
{
    /**
     * @var PointagesRepository
     */
    private $pointagesRepository;
    public function __construct(PointagesRepository $pointagesRepository)
    {
        $this->pointagesRepository = $pointagesRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\Time */

        if (!$value instanceof Pointages) {
            throw new UnexpectedTypeException($constraint, Pointages::class);
        }
        if (null === $value || '' === $value) {
            return;
        }

        $dateAttr = $value->getDate();
        $weekAttr = intval( $dateAttr->format("W"));
        $SQLHours = $this->pointagesRepository->findCountHours($week=$weekAttr,$id=$value->getIdUtilisateur()->getId());


        $totalH = intval($SQLHours[0]["dureee"]) + intval($value->getDuree()->format("His"));

        $dateDebut = new \DateTime('01:00');
        if ($value->getDuree()->format("H") < $dateDebut->format("H")){

            // TODO: implement the validation here
            $this->context->buildViolation($constraint->message0)
                ->setParameter('{{ value }}', 'Erreur')
                ->addViolation();
        }
//var_dump($totalH);
        if ($totalH > 350000){
            $this->context->buildViolation($constraint->message35)
                ->setParameter('{{ value }}', 'Erreur')
                ->addViolation();
        }

    }
}
