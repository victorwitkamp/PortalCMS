<?php

declare(strict_types=1);

namespace PortalCMS\Core\Http;

use ReflectionClass;
use ReflectionException;
use Throwable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestInputMapper
{
    public function __construct(
        private readonly ?SerializerInterface $serializer = null,
        private readonly ?ValidatorInterface $validator = null
    ) {
    }

    public function map(Request $request, string $class): object
    {
        return $this->mapArray($request->request->all(), $class);
    }

    public function mapQuery(Request $request, string $class): object
    {
        return $this->mapArray($request->query->all(), $class);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function mapArray(array $data, string $class): object
    {
        try {
            $input = $this->serializer()->denormalize($this->normalizeEmptyStrings($data, $class), $class);
        } catch (Throwable $exception) {
            throw new InvalidInputException(
                [ '_global' => [ $exception->getMessage() ] ],
                'Could not map request input.',
                $exception
            );
        }

        if (!is_object($input)) {
            throw new InvalidInputException([ '_global' => [ 'Could not map request input.' ] ]);
        }

        $violations = $this->validator()->validate($input);
        if (count($violations) > 0) {
            throw InvalidInputException::fromViolations($violations);
        }

        return $input;
    }

    private function serializer(): SerializerInterface
    {
        return $this->serializer ?? new Serializer([
            new DateTimeNormalizer(),
            new ArrayDenormalizer(),
            new ObjectNormalizer(),
        ]);
    }

    private function validator(): ValidatorInterface
    {
        return $this->validator ?? Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function normalizeEmptyStrings(array $data, string $class): array
    {
        $nullableFields = $this->nullableFields($class);
        foreach ($data as $key => $value) {
            if ($value === '' && ($nullableFields[$key] ?? false)) {
                $data[$key] = null;
            }
        }

        return $data;
    }

    /**
     * @return array<string, bool>
     */
    private function nullableFields(string $class): array
    {
        try {
            $reflection = new ReflectionClass($class);
        } catch (ReflectionException) {
            return [];
        }

        $fields = [];
        $constructor = $reflection->getConstructor();
        if ($constructor !== null) {
            foreach ($constructor->getParameters() as $parameter) {
                $fields[$parameter->getName()] = $parameter->allowsNull();
            }
        }

        foreach ($reflection->getProperties() as $property) {
            $type = $property->getType();
            if ($type !== null) {
                $fields[$property->getName()] = $type->allowsNull();
            }
        }

        return $fields;
    }
}
