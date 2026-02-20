<?php

return [
	'validation' => [
		'shop_id' => [
			'required' => 'Shop ID is required.',
			'integer'  => 'Shop ID must be an integer.',
			'exists'   => 'Shop ID does not exist or has been deleted.',
		],
		'start_date' => [
			'required'    => 'Start date is required.',
			'date_format' => 'Start date must be in the format YYYY-MM-DD.',
		],
		'end_date' => [
			'required'         => 'End date is required.',
			'date_format'      => 'End date must be in the format YYYY-MM-DD.',
			'after_or_equal'   => 'End date must be after or equal to start date.',
		],
	]
];
