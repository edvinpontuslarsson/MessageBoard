<?php

class DateTimeView {

	public function show() {

		$timeString = $this->getTimeString();

		return '<p>' . $timeString . '</p>';
	}

	/**
	 * My method of retrieving servertime has been inspired by Shef's answer here:
	 * https://stackoverflow.com/questions/6621572/how-to-get-date-and-time-from-server
	 */
	private function getTimeString() {

		$this->setDefaultTimezone();

		// $currentTime = getdate(); // more info in SO-post.

		$serverTime = date('H:i:s - d/m/Y');

		return "TODO: make server time more than this: $serverTime";
	}

	private function setDefaultTimezone() {
		$time = new DateTime();
		$timezone = $time->getTimezone()->getName();
		date_default_timezone_set($timezone);
	}
}