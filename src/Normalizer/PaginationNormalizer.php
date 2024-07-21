<?php

namespace App\Normalizer;

use App\Entity\User;
use ArrayObject;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PaginationNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer
    )
    {
        
    }
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|ArrayObject|null
    {
        if (!($object instanceof PaginationInterface)) {
            throw new \RuntimeException();
        }

        return [
            'items' => array_map(fn($item) => $this->normalizer->normalize($item, $format, $context), $object->getItems()),
            'total' => $object->getTotalItemCount(),
            'current_page' => $object->getCurrentPageNumber(),
            'total_pages' => ceil($object->getTotalItemCount() / $object->getItemNumberPerPage()),
            'last_page' => ceil($object->getTotalItemCount() / $object->getItemNumberPerPage()),
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof PaginationInterface;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            PaginationInterface::class => true
        ];
    }
}