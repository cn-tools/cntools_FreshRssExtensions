<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'Kopiér til udklipsholder',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Vælg',
                'desc' => '',
                'options' => array(
                    'default' => 'Standard',
                    'color' => 'Farve',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'Et link blev kopieret',
            'good_more' => '#count# links blev kopieret',
            'bad' => 'Der opstod en fejl under kopiering!',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Clipboard`kræves mindst FreshRSS version `%s`. (Du bruger FreshRSS version `%s`)',
        ),
    ),
);
