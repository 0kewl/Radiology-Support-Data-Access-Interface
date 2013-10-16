<?php

class SearchFieldEntity {

	// Blueprint of a radiology report
	public static function getFields() {
		
		return array(
			'department'		=> 'Department',
			'category'		=> 'Category',
			'pid'			=> 'PID',
			'sex'			=> 'Sex',
			'id'			=> 'ID',
			'did'			=> 'DID',
			'modality'		=> 'Modality',
			'title'			=> 'Title',
			'date'			=> 'Date',
			'year'			=> 'Year',
			'month'			=> 'Month',
			'day'			=> 'Day',
			'hour'			=> 'Hour',
			'history'		=> 'History',
			'site'			=> 'Site',
			'physician'		=> 'Physician',
			'body'			=> 'Body',
			'impression'		=> 'Impression',
			'positive'		=> 'Positive'
		);
	}
}
