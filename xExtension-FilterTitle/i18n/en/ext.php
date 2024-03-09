<?php

return array(
	'filter_title' => array(
		'keywords' => 'Keywords',
		'keywords_desc' => 'Each line is checked individually. In addition to normal texts, RegEx expressions can also be defined. These must be able to be evaluated using the PHP function preg_match.',
        'check_type' => 'Use as',
        'check_type_desc' => 'Here you specify whether the specified keywords should be used as a blacklist or as a whitelist.',
        'block' => 'Blacklist',
        'free' => 'Whitelist',
        'warning' => [
            'not_allowed_msg' => '"%s" is a banned keyword. Contact your administrator for more information.',
        ],
	),
);