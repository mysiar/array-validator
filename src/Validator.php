<?php
declare(strict_types=1);

namespace Mysiar\ArrayValidator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\RecursiveValidator;

class Validator
{
    /**
     * @var RecursiveValidator
     */
    protected $validator;

    /**
     * @var Constraint[]
     */
    protected $arrayElementConstraints = [];

    /**
     * @var Constraint[]
     */
    protected $arrayConstraints = [];

    /**
     * @var string[]
     */
    protected $errors = [];

    public function __construct(RecursiveValidator $validator = null)
    {
        if (null === $validator) {
            $validator = Validation::createValidator();
        }

        $this->validator = $validator;
    }

    public function addArrayElementConstraint(int $index, Constraint $constraint): Validator
    {
        if (!array_key_exists($index, $this->arrayElementConstraints)) {
            $this->arrayElementConstraints[$index] = [];
        }

        $this->arrayElementConstraints[$index][] = $constraint;

        return $this;
    }

    public function addArrayConstraint(Constraint $constraint): Validator
    {
        $this->arrayConstraints[] = $constraint;

        return $this;
    }

    public function validate(array $array): bool
    {
        $this->validateArray($array);
        $this->validateArrayElements($array);

        return 0 === count($this->errors) ? true : false;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function validateArray(array $array): void
    {
        foreach ($this->arrayConstraints as $constraint) {
            $violations = $this->validator->validate($array, $constraint);
            $this->addValidationErrors($violations);
        }
    }

    private function validateArrayElements(array $array): void
    {
        foreach ($this->arrayElementConstraints as $key => $constraints) {
            if (!isset($array[$key])) {
                $this->errors[] = sprintf('Element "%s" does not exist.', $key);
            } else {
                foreach ($constraints as $constraint) {
                    $violations = $this->validator->validate($array[$key], $constraint);
                    $this->addValidationErrors($violations);
                }
            }
        }
    }

    private function addValidationErrors(ConstraintViolationListInterface $violations): void
    {
        foreach ($violations as $violation) {
            $this->errors[] = $violation->getMessage();
        }
    }
}
