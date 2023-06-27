<?php

namespace App\Trait;

use App\Repository\HoursRepository;

trait traitHours
{
    public static function getHours(HoursRepository $hoursRepository)
    {
        return $hoursRepository->findAll();
    }
}
