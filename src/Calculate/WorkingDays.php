<?php
declare(strict_types=1);

namespace DiegoBrocanelli\Calculate;

use InvalidArgumentException;
use DateTime;
use Exception;

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

        $this->dateStart = $dateStart;
        $this->dateEnd   = $dateEnd;
        $this->holidays  = $holidays;
    }

    public function calculate()
    {
        $dtStart = new DateTime($this->dateStart);
        $dtEnd   = new DateTime($this->dateEnd);

        $diff = $dtStart->diff($dtEnd);

        if($diff->invert == 1){
            throw new Exception( self::INVALID_DATE_ORDER );
        }

        $weekDay = $dtStart->format('w');
        if($weekDay != self::SUNDAY && $weekDay != self::SATURDAY){
            $this->daysList[] =  $dtStart->format('Y-m-d');
            $this->number += 1;
        }

        if($diff->days == 0 ){
            return $this;
        }

        $interval = 1;
        for($interval; $interval <= $diff->days; $interval++){

            $dtStart->modify('+1 day');

            $weekDay   = $dtStart->format('w');
            $dtAnalize = $dtStart->format('Y-m-d');
            if($weekDay == self::SUNDAY || $weekDay == self::SATURDAY || in_array( $dtAnalize, $this->holidays )){
                continue;
            }

            $this->daysList[] =  $dtStart->format('Y-m-d');
            $this->number += 1;
        }

        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getDayList()
    {
        return $this->daysList;
    }
}