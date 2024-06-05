<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'Copiază în clipboard',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Alege',
                'desc' => '',
                'options' => array(
                    'default' => 'Implicit',
                    'color' => 'Culoare',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'Un link a fost copiat',
            'good_more' => '#count# link-uri au fost copiate',
            'bad' => 'A aparut o eroare la copiere!',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Clipboard`a necesitat cel puţin versiunea FreshRSS `%s`. (Utilizaţi versiunea FreshRSS `%s`)',
        ),
    ),
);
