<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'Copy to clipboard',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Choose',
                'desc' => '',
                'options' => array(
                    'default' => 'Standard',
                    'color' => 'Color',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'One link was copied',
            'good_more' => 'There have been copied #count# Links',
            'bad' => 'An error occurred while copying!',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Clipboard`required at least FreshRSS version `%s`. (You use FreshRSS version `%s`)',
        ),
    ),
);
