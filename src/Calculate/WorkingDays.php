<?php declare(strict_types=1);

namespace DiegoBrocanelli\Calculate;

use DiegoBrocanelli\Calculate\WorkingDaysInterface;
use DiegoBrocanelli\Checker\Holiday as HolidayChecker;
use DateTime;
use DateInterval;
use Exception;
use InvalidArgumentException;

/**
 * @author Diego Brocanelli <diegod2@msn.com>
 */
class WorkingDays implements WorkingDaysInterface
{
    const SATURDAY           = 6;
    const SUNDAY             = 0;
    const INVALID_ARGUMENT   = 'the argument entered is not valid';
    const INVALID_DATE_ORDER = 'start date can not exceed end date';

    private DateTime $dateStart;
    private DateTime $dateEnd;
    private array $holidays;
    private DateInterval $diff;
    private int $number = 0;
    private array $daysList = [];

    /**
     * @param string $dateStart format Y-m-d
     * @param string $dateEnd format Y-m-d
     * @param array $holidays default empty
     */
    public function __construct( string $dateStart, string $dateEnd, array $holidays = [])
    {
        if( empty($dateStart) || empty($dateEnd) ){
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
     * @return WorkingDays
     */
    public function calculate() : WorkingDays
    {
        $startDayIsHoliDay = ( new HolidayChecker )->checkStartDayIsHoliDay(
            $this->diff->days, 
            $this->dateStart->format('Y-m-d'), 
            $this->holidays 
        );

        if( $startDayIsHoliDay ){
            return $this;
        }

        $this->analyzeTheDay();

        $interval = 1;
        for($interval; $interval <= $this->diff->days; $interval++){

            $this->dateStart->modify('+1 day');

            $weekDay   = $this->dateStart->format('w');
            $dtAnalize = $this->dateStart->format('Y-m-d');

            if (
                $weekDay == self::SUNDAY || 
                $weekDay == self::SATURDAY || 
                ( new HolidayChecker )->checkHolidayList($dtAnalize, $this->holidays) 
            ) {
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
    public function getNumber() : int
    {
        return $this->number;
    }

    /**
     * Return the working days list
     *
     * @return array
     */
    public function getDayList() : array
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
            $this->daysList[] = $this->dateStart->format('Y-m-d');
            $this->number += 1;
        }
    }
}
