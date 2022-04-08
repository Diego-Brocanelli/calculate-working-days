<?php

declare(strict_types=1);

namespace DiegoBrocanelli\Checker;

/**
 * @author Diego Brocanelli <diegod2@msn.com>
 */
class Holiday
{
    /**
     * Responsible for  holiday list check
     *
     * @param string $day
     * @param array $holidays
     * @return boolean
     */
    public function checkHolidayList(string $day, array $holidays): bool
    {
        return in_array($day, $holidays);
    }

    /**
     * Responsible for  check the start day is holiday
     *
     * @return boolean
     */
    public function checkStartDayIsHoliDay(int $dayDiff, string $dateStart, array $holidays): bool
    {
        if ($dayDiff == 0 && $this->checkHolidayList($dateStart, $holidays)) {
            return true;
        }

        return false;
    }
}
