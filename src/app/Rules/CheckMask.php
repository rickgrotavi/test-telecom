<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;


class CheckMask implements Rule
{
    private string $serialNumberMask;

    const MASK_PARTS = [
        'N' => '[0-9]',
        'A' => '[A-Z]',
        'a' => '[a-z]',
        'X' => '[A-Z0-9]',
        'Z' => '[-|_|@]'
    ];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($serialNumberMask)
    {
        $this->serialNumberMask = $serialNumberMask;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $regex = "/^";
        foreach (str_split($this->serialNumberMask) as $item) {
            $regex .= self::MASK_PARTS[$item];
        }
        $regex .= "/";

        return preg_match($regex, $value) > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The serial number does not match the mask.';
    }

}

