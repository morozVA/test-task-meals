<?php

declare(strict_types=1);

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\MenuDoesNotContainDishException;
use Meals\Application\Component\Validator\MenuContainsDishValidator;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Menu\Menu;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class MenuContainsDishValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful()
    {
        $dishList = $this->getDishList();
        $menu = $this->getMenu($dishList);

        $dish = new Dish(
            1,
            'dish title',
            'dish description'
        );

        $validator = new MenuContainsDishValidator();
        verify($validator->validate($menu, $dish))->null();
    }

    public function testFail()
    {
        $this->expectException(MenuDoesNotContainDishException::class);

        $dishList = $this->getDishList();
        $menu = $this->getMenu($dishList);

        $dish = new Dish(
            4,
            'dish title',
            'dish description'
        );

        $validator = new MenuContainsDishValidator();
        verify($validator->validate($menu, $dish));
    }

    private function getDishList(): DishList
    {
        return new DishList([
            new Dish(
                1,
                'dish title',
                'dish description'
            ),
            new Dish(
                2,
                'dish title 2',
                'dish description 2'
            ),
        ]);
    }

    private function getMenu(DishList $dishList): Menu
    {
        return new Menu(
            1,
            'title',
            $dishList,
        );
    }

}
