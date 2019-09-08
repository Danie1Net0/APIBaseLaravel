<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxFileSize implements Rule
{
    private $maxSize;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($maxSize)
    {
        $this->maxSize = $maxSize;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value->getClientSize() < ($this->maxSize * 1000 * 1024);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "O arquivo deve ter no máximo {$this->maxSize}MB";
    }
}
