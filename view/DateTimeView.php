<?php

require_once('model/DateTimeModel.php');

class DateTimeView {

	public function show() {
		$dateTimeModel = new DateTimeModel();
		$timeString = $dateTimeModel->getDateTimeString();

		return '<p>' . $timeString . '</p>';
	}
}