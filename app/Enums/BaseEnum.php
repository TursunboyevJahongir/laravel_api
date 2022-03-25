<?php


namespace App\Enums;


class BaseEnum
{
    /**
     * Returns class constant values
     * @return array
     */
    public static function toArray(): array
    {
        $class = new \ReflectionClass(static::class);

        return array_values($class->getConstants());
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return implode(',', static::toArray());
    }
}
