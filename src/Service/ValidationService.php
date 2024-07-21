<?php

namespace App\Service;

use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validate an entity and return a json response with the errors if any or null if no errors
     * @param object $entity - the entity to validate
     * @param array $groups - the serializer groups to validate
     * @return JsonResponse|null - the json response with the errors or null if no errors
     */
    public function validate($entity, array $groups = null): ?JsonResponse
    {
        $errors = $this->validator->validate($entity, null, $groups);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        return null;
    }
}
