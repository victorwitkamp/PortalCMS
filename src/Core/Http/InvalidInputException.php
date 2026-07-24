<?php

declare(strict_types=1);

namespace PortalCMS\Core\Http;

use InvalidArgumentException;
use Throwable;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class InvalidInputException extends InvalidArgumentException
{
    /**
     * @param array<string, string[]> $errors
     */
    public function __construct(private readonly array $errors, string $message = 'Invalid input', ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function fromViolations(ConstraintViolationListInterface $violations): self
    {
        $errors = [];
        foreach ($violations as $violation) {
            if (!$violation instanceof ConstraintViolationInterface) {
                continue;
            }
            $path = $violation->getPropertyPath() !== '' ? $violation->getPropertyPath() : '_global';
            $errors[$path][] = $violation->getMessage();
        }

        return new self($errors);
    }

    /**
     * @return array<string, string[]>
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
