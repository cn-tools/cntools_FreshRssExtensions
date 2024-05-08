<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'In Zwischenablage kopieren',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Auswählen',
                'desc' => '',
                'options' => array(
                    'default' => 'Standard',
                    'color' => 'Farbe',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'Es wurde ein Link kopiert',
            'good_more' => 'Es wurden #count# Links kopiert',
            'bad' => 'Beim Kopieren trat ein Fehler auf!',
        ),
        'install' => array(
            'bad_freshrss' => 'Für "Copy2Clipboard" ist mindestens die FreshRSS-Version `%s` erforderlich. (Du verwendest die FreshRSS-Version `%s`)',
        ),
    ),
);
