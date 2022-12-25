<?php

declare(strict_types=1);

namespace Meals\Application\Component\Provider;

use Meals\Domain\Dish\Dish;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;

interface PollResultCreatorProviderInterface
{
    public function createPollResult(int $poleResultId, Poll $poll, Employee $employee, Dish $dish, int $employeeFloor): PollResult;
}
