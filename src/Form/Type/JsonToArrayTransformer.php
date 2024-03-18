<?php

namespace App\Form\Type;

use Symfony\Component\Form\DataTransformerInterface;

class JsonToArrayTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (null === $value) {
            return [];
        }

        return json_decode($value, true);
    }

    public function reverseTransform($value)
    {
        if (null === $value) {
            return null;
        }

        return json_encode($value);
    }
}
