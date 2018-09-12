<?php

// require_once('../model/DateTime.php');

class DateTimeModel {
	
    public function getDateTimeString() {

		$this->setDefaultTimezone();

		$dateArray = getdate();
		$weekday = $dateArray['weekday'];
		$mday = $dateArray['mday'];
		$month = $dateArray['month'];
		$year = $dateArray['year'];

		$time = date('H:i:s');

		return "$weekday, the {$mday}th of $month $year, The time is $time";
	}

	private function setDefaultTimezone() {
		$time = new DateTime();
		$timezone = $time->getTimezone()->getName();
		date_default_timezone_set($timezone);
	}
}

class DateTimeView {

	public function show() {
		$dateTimeModel = new DateTimeModel();

		$timeString = $dateTimeModel->getDateTimeString();

		return '<p>' . $timeString . '</p>';
	}
}