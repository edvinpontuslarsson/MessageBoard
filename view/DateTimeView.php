<?php

class DateTimeView {

	public function show() {

		$timeString = $this->getDateTimeString();

		return '<p>' . $timeString . '</p>';
	}

	private function getDateTimeString() {

		$this->setDefaultTimezone();

		$time = date('H:i:s');

		$dateArray = getdate();
		$weekday = $dateArray['weekday'];
		$mday = $dateArray['mday'];
		$month = $dateArray['month'];
		$year = $dateArray['year'];

		return "Server time is $time on $weekday the {$mday}th of $month $year";
	}

	private function setDefaultTimezone() {
		$time = new DateTime();
		$timezone = $time->getTimezone()->getName();
		date_default_timezone_set($timezone);
	}
}