<?php

namespace Domain\Global\Actions;

class GenerateOTPAction
{
    public function execute(): string
    {
        return str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}