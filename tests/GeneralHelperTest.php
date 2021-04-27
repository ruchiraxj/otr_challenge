<?php

use PHPUnit\Framework\TestCase;

class GeneralHelperTest extends TestCase
{

    public function test_generating_csv(){
        require_once('./configs/configs.php');
        require_once(ROOT_PATH . '/src/helper/General.php');

        $folder_path = ROOT_PATH.'/reports';
        $file = 'test_report_'.time().'.csv';
        $csv_data = [[1,2,3],[4,5,6],[7,8,9]];
        $res = generateCsv($csv_data, $folder_path, $file);
        $expectedResponse = BASE_URL.'/reports/'.$file;
        $this->assertEquals($expectedResponse, $res);
    }

   
}
