<?php

declare(strict_types=1);

namespace tests\Meals\Functional\Interactor;

use DateTime;
use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\Exception\MenuDoesNotContainDishException;
use Meals\Application\Component\Validator\Exception\PollIsNotActiveException;
use Meals\Application\Component\Validator\Exception\PollResultIsNotAvailableException;
use Meals\Application\Feature\PollResult\UseCase\EmployeeMakesPollResult\Interactor;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use tests\Meals\Functional\Fake\Provider\FakeDishProvider;
use tests\Meals\Functional\Fake\Provider\FakeEmployeeProvider;
use tests\Meals\Functional\Fake\Provider\FakePollProvider;
use tests\Meals\Functional\FunctionalTestCase;

class EmployeeMakesPollResultTest extends FunctionalTestCase
{
    private function performTestMethod(Employee $employee, Poll $poll, Dish $dish, string $dateTime): PollResult
    {
        $this->getContainer()->get(FakeEmployeeProvider::class)->setEmployee($employee);
        $this->getContainer()->get(FakePollProvider::class)->setPoll($poll);
        $this->getContainer()->get(FakeDishProvider::class)->setDish($dish);

        return $this->getContainer()->get(Interactor::class)->fixPollResult(1, $employee->getId(), $poll->getId(), $dish->getId(), $dateTime);
    }

    public function testSuccessful()
    {
        $dateTime = (new DateTime('26.12.2022 12:00:00'))->format('d.m.Y H:i:s');
        $pollResult = $this->performTestMethod($this->getEmployeeWithPermissions(), $this->getPoll(true), $this->getDish(), $dateTime);
        verify($pollResult)->equals($pollResult);
    }

    public function testPollIsNotActive()
    {
        $this->expectException(PollIsNotActiveException::class);

        $dateTime = (new DateTime('26.12.2022 12:00:00'))->format('d.m.Y H:i:s');
        $poll = $this->performTestMethod($this->getEmployeeWithPermissions(), $this->getPoll(false), $this->getDish(), $dateTime);
        verify($poll)->equals($poll);
    }

    public function testUserHasNotPermissions()
    {
        $this->expectException(AccessDeniedException::class);

        $dateTime = (new DateTime('26.12.2022 12:00:00'))->format('d.m.Y H:i:s');
        $pollResult = $this->performTestMethod($this->getEmployeeWithNoPermissions(), $this->getPoll(true), $this->getDish(), $dateTime);
        verify($pollResult)->equals($pollResult);
    }

    public function testMenuDoesNotContainDish()
    {
        $this->expectException(MenuDoesNotContainDishException::class);

        $dateTime = (new DateTime('26.12.2022 12:00:00'))->format('d.m.Y H:i:s');
        $pollResult = $this->performTestMethod($this->getEmployeeWithPermissions(), $this->getPoll(true), $this->getWrongDish(), $dateTime);
        verify($pollResult)->equals($pollResult);
    }

    public function testPollResultNotAvailable()
    {
        $this->expectException(PollResultIsNotAvailableException::class);

        $dateTime = (new DateTime('25.12.2022 12:00:00'))->format('d.m.Y H:i:s');
        $pollResult = $this->performTestMethod($this->getEmployeeWithPermissions(), $this->getPoll(true), $this->getDish(), $dateTime);
        verify($pollResult)->equals($pollResult);
    }

    private function getEmployeeWithPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithPermissions(): User
    {
        return new User(
            1,
            new PermissionList(
                [
                    new Permission(Permission::PARTICIPATION_IN_POLLS),
                ]
            ),
        );
    }

    private function getEmployeeWithNoPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithNoPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithNoPermissions(): User
    {
        return new User(
            1,
            new PermissionList([]),
        );
    }

    private function getPoll(bool $active): Poll
    {
        return new Poll(
            1,
            $active,
            new Menu(
                1,
                'title',
                new DishList([
                    new Dish(
                        1,
                        'title',
                        'description',
                    ),
                ]),
            )
        );
    }

    private function getDish(): Dish
    {
        return new Dish(
            1,
            'title',
            'description'
        );
    }

    private function getWrongDish(): Dish
    {
        return new Dish(
            10,
            'title',
            'description'
        );
    }
}
