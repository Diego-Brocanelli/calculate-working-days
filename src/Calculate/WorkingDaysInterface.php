<?php declare(strict_types=1);

namespace DiegoBrocanelli\Calculate;

/**
 * @author Diego Brocanelli <diegod2@msn.com>
 */
interface WorkingDaysInterface
{
    public function calculate() : WorkingDays;
    public function getNumber() : int;
    public function getDayList() : array;
}