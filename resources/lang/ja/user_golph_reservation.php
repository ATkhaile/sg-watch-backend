<?php

return [
	'validation' => [
		'shop_id' => [
			'required' => 'ショップIDは必須です。',
			'integer'  => 'ショップIDは整数で入力してください。',
			'exists'   => 'ショップIDが存在しないか、削除されています。',
		],
		'start_date' => [
			'required'    => '開始日は必須です。',
			'date_format' => '開始日はYYYY-MM-DD形式で入力してください。',
		],
		'end_date' => [
			'required'         => '終了日は必須です。',
			'date_format'      => '終了日はYYYY-MM-DD形式で入力してください。',
			'after_or_equal'   => '終了日は開始日以降の日付を入力してください。',
		],
	]
];
