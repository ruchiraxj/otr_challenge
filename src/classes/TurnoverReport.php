<?php

require_once(ROOT_PATH.'/src/classes/Database.php');
require_once(ROOT_PATH.'/src/helper/General.php');

/**
 * TurnoverReport
 * 
 * @author  Ruchira Jayasuriya <ruchiraxj@gmail.com>
 * @version 1.0.0
 * 
 */


class TurnoverReport
{
    /**
     * 
     * Start date of the report
     * 
     * @var Date
     * 
     */
    private $start_date = null;

    /**
     * 
     * End date of the report
     * 
     * @var Date
     * 
     */
    private $end_date = null;

    /**
     * 
     * Vat Amount
     * 
     * @var Float
     * 
     */
    private $tax_amount = 0;

    /**
     * 
     * Path to save the report
     * 
     * @var String
     * 
     */
    private $report_path = "";

    /**
     * Database Object
     * 
     * @var Object
     * 
     */
    private $db = null;

    function __construct($start, $end, $tax = 0, $report_path = "reports/")
    {
        $this->start_date = $start;
        $this->end_date = $end;
        $this->tax_amount = $tax;
        $this->report_path = $report_path;
        $this->db = new Database();
        
        $this->validateInputs();

    }

    private function validateInputs(){
        
        if($this->start_date == "" || $this->end_date == "" || $this->tax_amount == ""){
            throw new Exception('Invalid data set');
        }

        $dt = explode("-", $this->start_date);
        if(!checkdate($dt[1], $dt[2], $dt[0])){
            throw new Exception('Invalid Start Date');
        }

        $dt = explode("-", $this->end_date);
        if(!checkdate($dt[1], $dt[2], $dt[0])){
            throw new Exception('Invalid End Date');
        }

        if(strtotime((string) $this->end_date) < strtotime((string) $this->start_date)){
            throw new Exception('End Date Should Be Greater Than Start Date');
        }

        if(!is_numeric($this->tax_amount)){
            throw new Exception('Invalid Tax Amount');
        }

        return true;
    }
    
    /**
     * Generates the CSV report - Turnover Per Brand
     */
    public function generatePerBrandReport()
    {
        $data = [];
        $head = $this->generateHeadersForPerBrandReport();
        $body = $this->getCsvBodyForPerBrand();

        $data = array_merge([$head], $body);

        $file_path = $this->report_path.'/';
        $file = "Turnover-per-brand-" . $this->start_date . '-to-' . $this->end_date . '-' . time() . '.csv';
        return generateCsv($data, $file_path, $file);
    }

    /**
     * Generates the CSV report - Turnover Per Day
     */
    public function generateDailyTurnoverReport()
    {
        $data = [];

        $head = ['Date', 'Total Inclusive Vat', 'Total Exclusive Vat'];
        $body = $this->getCsvBodyForDailyTurnover();

        $data = array_merge([$head], $body);

        $file_path = $this->report_path.'/';
        $file = "Daily-Turnover-" . $this->start_date . '-to-' . $this->end_date . '-' . time() . '.csv';
        return generateCsv($data, $file_path, $file);
    }

    /**
     * Generates the body data for daily turnover totals report
     */
    private function getCsvBodyForDailyTurnover(){
        $data = [];
        $turnover_data = $this->getTurnoverTotals();
        $dates_list = $this->getDatesWithin();
        foreach($dates_list as $dkey => $dval){
            $temp = [];
            $temp[] = $dval;
            foreach($turnover_data as $k){
               if($k['ndate'] == $dval){
                   $temp[] = $k['total'];
                   $temp[] = round(($k['total'] / (1 + $this->tax_amount)), 2);
               } 
            }

            $data[] = $temp;
        }

        return $data;
    }


    /**
     * Generates the body data for turnover per brand report
     */
    private function getCsvBodyForPerBrand()
    {
        $data = [];
        $turnover_data = $this->getTurnovers();
        $dates_list = $this->getDatesWithin();

        foreach ($turnover_data as $k) {
            $id = $k['id'];

            if (!isset($data[$id])) {
                $data[$id] = ["name" => $k['name']];

                //This will prevent issues that may occur if data is missing for a day
                foreach ($dates_list as $d => $dval) {
                    $data[$id][$dval] = 0;
                }
            }
            $data[$id][$k['date']] = $k['turnover'];
        }

        $final = [];
        foreach ($data as $k) {
            $arr = [];
            $arr[] = $k['name'];
            $total = 0;
            foreach ($dates_list as $d => $dval) {
                $arr[] = $k[$dval];
                $total += $k[$dval];
            }
            $arr[] = $total;
            $arr[] =  round(($total / (1 + $this->tax_amount)), 2);

            $final[] = $arr;
        }


        return $final;
    }

    /**
     * Generate the headers for the CSV report
     */
    private function generateHeadersForPerBrandReport()
    {
        $dates_list = $this->getDatesWithin();
        $headers = array_merge(["Brand"], $dates_list, ["Total Inclusive Vat", "Total Exclusive Vat"]);
        return $headers;
    }

    /**
     * Fetch the records from Database that will be necessary to the report
     */
    private function getTurnovers()
    {
        $this->db->query("SELECT brands.id, brands.name, gmv.turnover, DATE_FORMAT(gmv.date,'%Y-%m-%d') as `date`  FROM brands JOIN gmv ON brands.id = gmv.brand_id WHERE gmv.date BETWEEN :sdate AND :edate");
        $this->db->bind(":sdate", $this->start_date);
        $this->db->bind(":edate", $this->end_date);
        return $this->db->resultset();
    }

  
    /**
     * Daily Turnover Totals within a date range
     */
    private function getTurnoverTotals()
    {
        $this->db->query("SELECT SUM(gmv.turnover) as total, DATE_FORMAT(gmv.date,'%Y-%m-%d') as `ndate` FROM brands JOIN gmv ON brands.id = gmv.brand_id WHERE gmv.date BETWEEN :sdate AND :edate GROUP BY ndate");
        $this->db->bind(":sdate", $this->start_date);
        $this->db->bind(":edate", $this->end_date);
        return $this->db->resultset();
    }

    /**
     * Return an array of dates within a date range
     */

    private function getDatesWithin()
    {
        $start = new DateTime($this->start_date);
        $end = new DateTime($this->end_date);
        $end = $end->modify('+1 day'); //This will include the end date in the array. Otherwise it will return a list without the end date.
        $interval = new DateInterval('P1D');

        $period = new DatePeriod($start, $interval, $end);
        $dates = [];
        foreach ($period as $key => $value) {
            $dates[] = $value->format('Y-m-d');
        }
        return $dates;
    }

}
