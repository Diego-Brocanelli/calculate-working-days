<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use DiegoBrocanelli\Calculate\WorkingDays;

/**
 * @author Diego Brocanelli <contato@diegobrocanelli.com.br>
 */
final class WorkingDaysTest extends TestCase
{
    public function testToCalculateBetweenDateReturningOnlyTheQuantity() : void
    {
        //Total: 06 days
        //Work days: 04
        $this->assertEquals(
            4,
            ( new WorkingDays(
                '2019-06-06',
                '2019-06-11'
            ) )->calculate()->getNumber()
        );

        //Total: 30 days
        //Work days: 20
        $this->assertEquals(
            20,
            ( new WorkingDays(
                '2019-06-01',
                '2019-06-30'
            ) )->calculate()->getNumber()
        );

        //Total: 05 days
        //Work days: 05
        $this->assertEquals(
            5,
            ( new WorkingDays(
                '2019-06-24',
                '2019-06-28'
            ) )->calculate()->getNumber()
        );

        //Total: 16 days
        //Work days: 10
        $this->assertEquals(
            10,
            ( new WorkingDays(
                '2019-06-08',
                '2019-06-23'
            ) )->calculate()->getNumber()
        );
    }

    public function testToCalculateBetweenDateReturningListDays() : void
    {
        //Total: 06 days
        //Work days: 04
        $daysList =  (new WorkingDays(
            '2019-06-06',
            '2019-06-11'
        ) )->calculate()->getDayList();
        $this->assertEquals(
            4,
            count($daysList)
        );

        $this->assertEquals(
            '2019-06-06',
            $daysList[0]
        );

        $this->assertEquals(
            '2019-06-07',
            $daysList[1]
        );

        $this->assertEquals(
            '2019-06-10',
            $daysList[2]
        );

        $this->assertEquals(
            '2019-06-11',
            $daysList[3]
        );
    }

    public function testToCalculateBetweenDateFullInfo() : void
    {
        //Total: 06 days
        //Work days: 04
        $days =  (new WorkingDays(
            '2019-06-06',
            '2019-06-11'
        ) )->calculate();

        $daysList = $days->getDayList();

        $this->assertEquals(
            4,
            $days->getNumber()
        );

        $this->assertEquals(
            '2019-06-06',
            $daysList[0]
        );

        $this->assertEquals(
            '2019-06-07',
            $daysList[1]
        );

        $this->assertEquals(
            '2019-06-10',
            $daysList[2]
        );

        $this->assertEquals(
            '2019-06-11',
            $daysList[3]
        );
    }

    public function testToCalculateBetweenDateContentHoliDays() : void
    {
        //Total: 07 days
        //Work days: 04
        $days =  (new WorkingDays(
            '2019-06-05',
            '2019-06-11',
            ['2019-06-06']
        ) )->calculate();

        $daysList = $days->getDayList();

        $this->assertEquals(
            4,
            $days->getNumber()
        );

        $this->assertEquals(
            '2019-06-05',
            $daysList[0]
        );

        $this->assertEquals(
            '2019-06-07',
            $daysList[1]
        );

        $this->assertEquals(
            '2019-06-10',
            $daysList[2]
        );

        $this->assertEquals(
            '2019-06-11',
            $daysList[3]
        );
    }

    public function testToCalculateSameDay() : void
    {
        //Total: 01 days
        //Work days: 01
        $day =  (new WorkingDays(
            '2019-06-11',
            '2019-06-11',
            ['2019-06-06']
        ) )->calculate();

        $this->assertEquals(
            1,
            $day->getNumber()
        );

        $daysList = $day->getDayList();

        $this->assertEquals(
            '2019-06-11',
            $daysList[0]
        );
    }

    public function testToCalculateSameDayButNotWorkingDay() : void
    {
        //Total: 01 days
        //Work days: 01
        $day =  (new WorkingDays(
            '2019-06-08',
            '2019-06-08'
        ) )->calculate();

        $this->assertEquals(
            0,
            $day->getNumber()
        );

        $daysList = $day->getDayList();

        $this->assertTrue(empty($daysList));

        //Total: 01 days
        //Work days: 01
        $day =  (new WorkingDays(
            '2019-06-06',
            '2019-06-06',
            ['2019-06-06']
        ) )->calculate();

        $this->assertEquals(
            0,
            $day->getNumber()
        );

        $daysList = $day->getDayList();

        $this->assertTrue(empty($daysList));
    }

    public function testToCalculateInvalidArgumentsDtStart() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        new WorkingDays('', '2019-06-11');
    }

    public function testToCalculateInvalidArgumentsDtEnd() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        new WorkingDays('2019-06-11', '');
    }

    public function testToCalculateInvalidArgumentsHolidays() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        new WorkingDays('2019-06-11', '2019-06-11', '');
    }

    public function testToCalculateInvalidArgumentsDtStartDtEnd() : void
    {
        $this->expectException(\Exception::class);

        (new WorkingDays('2020-06-11', '2019-06-11'))->calculate();
    }
}
