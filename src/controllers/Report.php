<?php

require_once(ROOT_PATH . '/src/classes/TurnoverReport.php');

/**
 * Reports Controller
 * 
 * @author  Ruchira Jayasuriya <ruchiraxj@gmail.com>
 * @version 1.0.0
 * 
 */

class Report
{

	public function index()
	{
		return "Invalid Request";
	}

	/**
	 * Validate the header request using Authentication parameter
	 */
	private function validateAuth(){

		$headers = getallheaders();
		if($headers['Authorization'] != AUTH_TOKEN){
			return false;
		}

		return true;
	}

	/**
	 * Generate daily turnover per brand
	 */
	public function turnoverPerBrand()
	{	

		//simple auth validator
		if($this->validateAuth() == false){
			return ['success' => false, 'data' => [], 'message' => "Authentication Failed"];
		}

		if($_SERVER['REQUEST_METHOD'] != "GET"){
			return ['success' => false, 'data' => [], 'message' => "Invalid Request"];
		}

		if(!isset($_GET['start_date']) || !isset($_GET['end_date'])){
			return ['success' => false, 'data' => [], 'message' => "Missing Parameters"];
		}

		try {
			$report = new TurnoverReport($_GET['start_date'], $_GET['end_date'], VAT_RATE, ROOT_PATH . '/reports');
			$data = $report->generatePerBrandReport();
			return ['success' => true, 'data' => $data, 'message' => ""];

		} catch (Exception $e) {
			return ['success' => false, 'data' => [], 'message' => $e->getMessage()];
		}
	}


	/**
	 * Generates daily turnover totals of all brands
	 */
	public function dailyTurnover()
	{
		
		//simple auth validator
		if($this->validateAuth() == false){
			return ['success' => false, 'data' => [], 'message' => "Authentication Failed"];
		}

		if($_SERVER['REQUEST_METHOD'] != "GET"){
			return ['success' => false, 'data' => [], 'message' => "Invalid Request"];
		}

		if(!isset($_GET['start_date']) || !isset($_GET['end_date'])){
			return ['success' => false, 'data' => [], 'message' => "Missing parameters"];
		}

		try {
			$report = new TurnoverReport($_GET['start_date'], $_GET['end_date'], VAT_RATE, ROOT_PATH . '/reports');
			$data = $report->generateDailyTurnoverReport();
			return ['success' => true, 'data' => $data, 'message' => ""];
			
		} catch (Exception $e) {
			return ['success' => false, 'data' => [], 'message' => $e->getMessage()];
		}
	}
}
