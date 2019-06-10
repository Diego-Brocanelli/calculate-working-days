<?php
declare(strict_types=1);

namespace DiegoBrocanelli\Calculate;

use InvalidArgumentException;
use DateTime;
use Exception;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @author Diego Brocanelli <contato@diegobrocanelli.com.br>
 */
class WorkingDays
{
    const SATURDAY = 6;
    const SUNDAY   = 0;

    const INVALID_ARGUMENT   = 'the argument entered is not valid';
    const INVALID_DATE_ORDER = 'start date can not exceed end date';

    private $dateStart;
    private $dateEnd;
    private $holidays;
    private $diff;

    private $number = 0;
    private $daysList;

    /**
     * @param string $dateStart format Y-m-d
     * @param stirng $dateEnd   format Y-m-d
     * @param array $holidays   default empty
     */
    public function __construct($dateStart, $dateEnd, $holidays = [])
    {
        if(empty($dateStart) || empty($dateEnd) || !is_array($holidays)){
            throw new InvalidArgumentException(self::INVALID_ARGUMENT);
        }

        $this->dateStart = new DateTime($dateStart);
        $this->dateEnd   = new DateTime($dateEnd);
        $this->diff      = $this->dateStart->diff($this->dateEnd);
        $this->holidays  = $holidays;

        if($this->diff->invert == 1){
            throw new Exception( self::INVALID_DATE_ORDER );
        }
    }

    /**
     * Calculate working days
     *
     * @return this
     */
    public function calculate()
    {
        if( $this->checkStartDayIsHoliDay() ){
            return $this;
        }

        $this->analyzeTheDay();

        $interval = 1;
        for($interval; $interval <= $this->diff->days; $interval++){

            $this->dateStart->modify('+1 day');

            $weekDay   = $this->dateStart->format('w');
            $dtAnalize = $this->dateStart->format('Y-m-d');

            if($weekDay == self::SUNDAY || $weekDay == self::SATURDAY || $this->checkHolidayList($dtAnalize) ){
                continue;
            }

            $this->daysList[] =  $this->dateStart->format('Y-m-d');
            $this->number += 1;
        }

        return $this;
    }

    /**
     * Return the quantity working days
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Return the working days list
     *
     * @return array[WorkingDays]
     */
    public function getDayList()
    {
        return $this->daysList;
    }

    /**
     * Responsible for analyzing whether the starting day is Saturday or Sunday
     *
     * @return void
     */
    private function analyzeTheDay() : void
    {
        $weekDay = $this->dateStart->format('w');
        if($weekDay != self::SUNDAY && $weekDay != self::SATURDAY){
            $this->daysList[] =  $this->dateStart->format('Y-m-d');
            $this->number += 1;
        }
    }

    /**
     * Responsible for  holiday list check
     *
     * @param  string $day
     * @return boolean
     */
    private function checkHolidayList($day) : bool
    {
        return in_array( $day, $this->holidays );
    }

    /**
     * Responsible for  check the start day is holiday
     *
     * @return boolean
     */
    private function checkStartDayIsHoliDay() : bool
    {
        if($this->diff->days == 0 && $this->checkHolidayList($this->dateStart->format('Y-m-d') ) ){
            return true;
        }

        return false;
    }
}