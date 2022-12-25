<?php

declare(strict_types=1);

namespace Meals\Application\Feature\PollResult\UseCase\EmployeeMakesPollResult;

use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Application\Component\Provider\EmployeeProviderInterface;
use Meals\Application\Component\Provider\PollProviderInterface;
use Meals\Application\Component\Provider\PollResultCreatorProviderInterface;
use Meals\Application\Component\Validator\Exception\UserHasAccessToParticipateInPollsValidator;
use Meals\Application\Component\Validator\MenuContainsDishValidator;
use Meals\Application\Component\Validator\PollIsActiveValidator;
use Meals\Application\Component\Validator\PollResultAvailableValidator;
use Meals\Domain\Poll\PollResult;

class Interactor
{
    public function __construct(
        private PollResultCreatorProviderInterface         $pollResultCreatorProvider,
        private EmployeeProviderInterface                  $employeeProvider,
        private DishProviderInterface                      $dishProvider,
        private PollProviderInterface                      $pollProvider,
        private PollResultAvailableValidator               $pollResultAvailableValidator,
        private MenuContainsDishValidator                  $menuContainsDishValidator,
        private UserHasAccessToParticipateInPollsValidator $userHasAccessToParticipateInPollsValidator,
        private PollIsActiveValidator                      $pollIsActiveValidator,
    )
    {
    }

    public function fixPollResult(int $poleResultId, int $employeeId, int $pollId, int $dishId, string $dateTime): PollResult
    {
        $employee = $this->employeeProvider->getEmployee($employeeId);
        $poll = $this->pollProvider->getPoll($pollId);
        $dish = $this->dishProvider->getDish($dishId);

        $this->pollIsActiveValidator->validate($poll);
        $this->userHasAccessToParticipateInPollsValidator->validate($employee->getUser());
        $this->menuContainsDishValidator->validate($poll->getMenu(), $dish);
        $this->pollResultAvailableValidator->validate($dateTime);

        return $this->pollResultCreatorProvider->createPollResult($poleResultId, $poll, $employee, $dish, $employee->getFloor());
    }
}
