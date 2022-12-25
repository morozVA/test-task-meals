<?php

declare(strict_types=1);

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\Provider\PollResultCreatorProviderInterface;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;

class FakePollResultCreatorProvider implements PollResultCreatorProviderInterface
{
    public function createPollResult(int $poleResultId, Poll $poll, Employee $employee, Dish $dish, int $employeeFloor): PollResult
    {
        return new PollResult(
            $poleResultId,
            $poll,
            $employee,
            $dish,
            3
        );
    }


}
