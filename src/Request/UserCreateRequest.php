<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class UserCreateRequest
 *
 * @package App\Request
 */
class UserCreateRequest extends BaseRequest
{
    #[NotBlank([])]
    #[Length(
        min: 3,
        max: 255,
        minMessage: 'Name must be at least {{ limit }} characters long',
        maxMessage: 'Name cannot be longer than {{ limit }} characters',
    )]
    public ?string $name = null;
}
