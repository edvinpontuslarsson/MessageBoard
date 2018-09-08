<?php

class DateTimeView {


	/**
	 * My method of retrieving servertime has been inspired by Shef's answer here:
	 * https://stackoverflow.com/questions/6621572/how-to-get-date-and-time-from-server
	 */
	public function show() {

		$time = new DateTime();
		$timezone = $time->getTimezone()->getName();
		date_default_timezone_set($timezone);
		// $currentTime = getdate(); // more info in SO-post.

		$serverTime = date('H:i:s - d/m/Y');

		$timeString = "TODO: make server time more than this: $serverTime";

		return '<p>' . $timeString . '</p>';
	}
}