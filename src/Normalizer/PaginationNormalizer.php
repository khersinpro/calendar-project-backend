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

    /**
     * Normalize a pagination object
     * @param mixed $object - the object to normalize
     * @param string|null $format - the format to normalize the object to
     * @param array $context - the context to normalize the object with
     * @return array|string|int|float|bool|ArrayObject|null - the normalized object
     */
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

    /**
     * Check if the normalization is supported
     * @param mixed $data - the data to check
     * @param string|null $format - the format to check
     * @param array $context - the context to check
     * @return bool - true if the normalization is supported, false otherwise
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof PaginationInterface;
    }

    /**
     * The supported types for this normalizer
     * @param string|null $format - the format to get the supported types for
     * @return array - the supported types
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            PaginationInterface::class => true
        ];
    }
}