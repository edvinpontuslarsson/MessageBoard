<?php

class DateTimeModel {
	
    public function getDateTimeString() : string {

		$this->setDefaultTimezone();

		$dateArray = getdate();
		$weekday = $dateArray['weekday'];
		$monthDay = $dateArray['mday'];
		$monthSuffix = date("S");
		$month = $dateArray['month'];
		$year = $dateArray['year'];

		$time = date('H:i:s');

		return "$weekday, the {$monthDay}$monthSuffix of $month $year, The time is $time";
	}

	private function setDefaultTimezone() {
		date_default_timezone_set("Europe/Stockholm");
	}
}