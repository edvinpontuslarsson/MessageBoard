<?php

// require_once('../model/DateTime.php');

class DateTime {
	public function getHello() {
		$hello = $this->createHello();
		return $hello;
	}

	private function createHello() {
		return 'Hello';
	}
}

class DateTimeView {

	public function show() {
		// $timeString = $this->getDateTimeString();

		$greeting = new DateTime();

		$timeString = $greeting->getHello();

		return '<p>' . $timeString . '</p>';
	}

	/*
		TODO: remove below, require from model/DateTime.php
	*/

	private function getDateTimeString() {
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