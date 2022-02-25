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
                    'default' => 'Default',
                    'color' => 'Color',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'One link was copied',
            'good_more' => '#count# links were copied',
            'bad' => 'An error occurred during copying!',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Clipboard`required at least FreshRSS version `%s`. (You use FreshRSS version `%s`)',
        ),
    ),
);
