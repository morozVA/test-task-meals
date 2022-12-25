<?php

declare(strict_types=1);

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\PollResultIsNotAvailableException;
use Meals\Application\Component\Validator\PollResultAvailableValidator;
use PHPUnit\Framework\TestCase;

class PollResiltAvailableValidatorTest extends TestCase
{

    /**
     * @dataProvider successDataProvider
     */
    public function testSuccessful($dateTime)
    {
        $validator = new PollResultAvailableValidator();

        verify($validator->validate($dateTime))->null();
    }

    /**
     * @dataProvider failDataProvider
     */
    public function testFail($dateTime)
    {
        $this->expectException(PollResultIsNotAvailableException::class);

        $validator = new PollResultAvailableValidator();

        verify($validator->validate($dateTime));
    }

    public function successDataProvider()
    {
        return array(
            ['05.12.2022 06:00:00'],
            ['05.12.2022 07:00:00'],
            ['05.12.2022 08:00:00'],
            ['05.12.2022 09:00:00'],
            ['05.12.2022 10:00:00'],
            ['05.12.2022 20:00:00'],
            ['05.12.2022 21:59:00'],
            ['12.12.2022 12:00:00'],
            ['19.12.2022 20:00:00'],
        );
    }

    public function failDataProvider()
    {
        return array(
            ['05.12.2022 03:00:00'],
            ['05.12.2022 22:00:00'],
            ['05.12.2022 23:00:00'],
            ['06.12.2022 12:00:00'],
            ['07.12.2022 12:00:00'],
            ['08.12.2022 12:00:00'],
            ['09.12.2022 12:00:00'],
            ['10.12.2022 12:00:00'],
            ['11.12.2022 12:00:00'],
        );
    }

}
