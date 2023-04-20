<?php

namespace App\Serializer;

use App\Entity\Group;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class GroupNormalizer
 *
 * @package App\Serializer
 */
class GroupNormalizer implements NormalizerInterface
{

    /**
     * @param Group $object
     * @param string|null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|string|void|null
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'users' => array_map(
                function ($user) {
                    return [
                        'id' => $user->getId(),
                        'name' => $user->getName(),
                    ];
                },
                $object->getUsers()->toArray(),
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
        return $data instanceof Group;
    }
}
