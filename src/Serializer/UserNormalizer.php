<?php

namespace App\Serializer;

use App\Entity\User;
use ArrayObject;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class UserNormalizer
 *
 * @package App\Serializer
 */
class UserNormalizer implements NormalizerInterface
{
    /**
     * @param User $object
     * @param string|null $format
     * @param array $context
     * @return array|ArrayObject|bool|float|int|string|void|null
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'groups' => array_map(
                function ($group) {
                    return [
                        'id' => $group->getId(),
                        'name' => $group->getName(),
                    ];
                },
                $object->getGroups()->toArray(),
            ),
        ];
    }

    /**
     * @param mixed $data
     * @param string|null $format
     * @return bool
     */
    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof User;
    }
}
