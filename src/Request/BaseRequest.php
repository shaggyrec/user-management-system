<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BaseRequest
 *
 * @package App\Request
 */
class BaseRequest
{
    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(protected ValidatorInterface $validator)
    {
        $this->populate();
        $this->validate();
    }

    /**
     * @return void
     */
    public function validate(): void
    {
        $errors = $this->validator->validate($this);

        $errorMessages = [];
        /** @var ConstraintViolation $errors */
        foreach ($errors as $message) {
            $errorMessages[] = $message->getMessage();
        }

        if (empty($errorMessages)) {
            return;
        }

        throw new BadRequestException(
            join('; ', $errorMessages),
        );
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return Request::createFromGlobals();
    }

    /**
     * @return void
     */
    protected function populate(): void
    {
        foreach ($this->getRequest()->toArray() as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }
}
