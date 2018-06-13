<?php

namespace Controller;

use Core\Controller;
use DateTime;

class BookController extends Controller
{
    const YEARS_PERIOD = 30;

    /**
     * Default controller route.
     */
    public function index()
    {
        $view = VIEWS_PATH . 'book.php';

        $data['months'] = $this->getMonths();
        $data['days'] = $this->generateDays();
        $data['years'] = $this->generateYears();
        $data['time'] = $this->generateTime();

        $this->view($view, $data);
    }

    private function generateYears()
    {
        $currentYear = (int)(new DateTime())->format('Y');
        $years = [];

        for ($year = $currentYear; $year <= $currentYear + self::YEARS_PERIOD; $year++) {
            $years[] = $year;
        }

        return $years;
    }

    private function generateDays()
    {
        $days = [];

        for ($day = 1; $day < 31; $day++) {
            $days[] = $day;
        }

        return $days;
    }

    private function getMonths()
    {
        return [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec'
        ];
    }

    private function generateTime()
    {
        $time = [];

        for ($hour = 0; $hour <= 24; $hour++) {
            $time['hours'][] = $hour;
        }

        for ($minute = 0; $minute <= 60; $minute++) {
            $time['minutes'][] = $minute;
        }

        return $time;
    }
}
