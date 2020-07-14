<?php

namespace App\Validator;

use App\Service\ClientHttp;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AccesibleUrlValidator extends ConstraintValidator
{
    private $clientHttp;

    public function __construct(ClientHttp $clientHttp)
    {
        $this->clientHttp = $clientHttp;
    }
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }
        // Get code status from getHttpCodeStatus of our ClientHttp Service
        if (200 === $statusCode = $this->clientHttp->getHttpCodeStatus($value))
            return;

        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            // Parameters will rendered in message of our AccesibleUrl constraint
            ->setParameter('{{ value }}', $value)
            ->setParameter('{{ statusCode }}', $statusCode)
            ->addViolation();
    }
}
