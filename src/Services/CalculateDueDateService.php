<?php

require_once './config/app.php';

class CalculateDueDateService
{

    protected $config;

    function __construct()
    {
        $this->config = new Config();
    }

    /**
     * Processing of currently reported errors
     *
     * @param string $submit_t
     * @param integer $turnaround_t
     * @return $string
     */
    public function CalculateDueDate($submit_t, $turnaround_t)
    {
        // Validating parameters

        if(!is_string($submit_t) || !is_numeric($turnaround_t)){

            throw new Exception("Invalid parameter passed!", 400);
            
        }

        // Setting default values from config

        $workday_end_day = $this->config->workday_end_day;

        $workday_start_hour = $this->config->workday_start_hour;

        $workday_end_hour = $this->config->workday_end_hour;

        // Define turnaround days and remaining hours

        $turnaround_days = floor($turnaround_t / 8);

        $remaining_hours = $turnaround_t % 8;

        // Create DateTime object

        $due_date = new DateTime($submit_t);

        // Add days to due date

        $due_date->modify('+' . $turnaround_days . ' weekday');

        // Processing of remaining hours

        if ($remaining_hours) {

            for ($i = 0; $i < $remaining_hours; $i++) {
                
                // Add an hour until 5 p.m

                if ($due_date->format("H") + 1 < $workday_end_hour) {

                    $due_date->modify('+1 hour');

                // If it is Friday, it should be set due date to Monday 

                } elseif ($due_date->format("D") == $workday_end_day) {

                    $due_date->modify("+3 day");

                    $due_date->setTime($workday_start_hour, $due_date->format("i"));
                
                // Other cases, Add 1 day

                } else {

                    $due_date->modify("+1 day");

                    $due_date->setTime($workday_start_hour, $due_date->format("i"));
                }
            }
        }


        return $due_date->format('Y-m-d H:i:s');
    }
};
