<?php

declare(strict_types=1);

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\MenuDoesNotContainDishException;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Menu\Menu;

class MenuContainsDishValidator
{
    public function validate(Menu $menu, Dish $dish): void
    {
        if (!$menu->getDishes()->hasDish($dish)) {
            throw new MenuDoesNotContainDishException();
        }
    }
}
