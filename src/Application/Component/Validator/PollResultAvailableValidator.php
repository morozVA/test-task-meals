<?php

declare(strict_types=1);

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\PollResultIsNotAvailableException;

class PollResultAvailableValidator
{

    private const ONLY_AVAILABLE_WEEK_DAY_MONDAY = 'Monday';
    private const MIN_AVAILABLE_HOUR = 6;
    private const MAX_AVAILABLE_HOUR = 21;

    public function validate(string $dateTime): void
    {
        $dateTime = strtotime($dateTime);
        if (!$this->checkWeekDay($dateTime) || !$this->checkTime($dateTime)) {
            throw new PollResultIsNotAvailableException();
        }
    }

    private function checkWeekDay(int $date): bool
    {
        $weekDay = date('l', $date);
        if ($weekDay !== self::ONLY_AVAILABLE_WEEK_DAY_MONDAY) {
            return false;
        }
        return true;
    }

    private function checkTime(int $date): bool
    {
        $hour = date('G', $date);
        if ($hour < self::MIN_AVAILABLE_HOUR || $hour > self::MAX_AVAILABLE_HOUR) {
            return false;
        }
        return true;
    }

}
