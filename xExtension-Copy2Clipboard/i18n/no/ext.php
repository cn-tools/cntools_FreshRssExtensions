<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'Kopier til utklippstavlen',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Velg',
                'desc' => '',
                'options' => array(
                    'default' => 'Standard',
                    'color' => 'Farge',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'En lenke ble kopiert',
            'good_more' => '#count# lenker ble kopiert',
            'bad' => 'Det oppstod en feil under kopiering!',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Clipboard`som kreves minimum FreshRSS versjon `%s`. (Du bruker FreshRSS versjon `%s`)',
        ),
    ),
);
