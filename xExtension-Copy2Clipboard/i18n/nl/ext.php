<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'Kopiëren naar klembord',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Kiezen',
                'desc' => '',
                'options' => array(
                    'default' => 'Standaard',
                    'color' => 'Kleur',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'Eén link is gekopieerd',
            'good_more' => '#count# links zijn gekopieerd',
            'bad' => 'Er is een fout opgetreden tijdens het kopiëren!',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Clipboard`vereist op zijn minst FreshRSS versie `%s`. (Je gebruikt FreshRSS versie `%s`)',
        ),
    ),
);
