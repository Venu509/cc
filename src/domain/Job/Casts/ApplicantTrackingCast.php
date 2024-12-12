<?php

namespace Domain\Job\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use JsonException;

class ApplicantTrackingCast implements CastsAttributes
{
    /**
     * @throws JsonException
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (empty($value)) {
            return [];
        }

        try {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return [];
        }
    }

    /**
     * @throws JsonException
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return json_encode($value, JSON_THROW_ON_ERROR);
    }
}