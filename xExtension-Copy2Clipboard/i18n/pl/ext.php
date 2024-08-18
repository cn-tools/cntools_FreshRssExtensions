<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'Kopiuj do schowka',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Wybierz',
                'desc' => '',
                'options' => array(
                    'default' => 'Domyślny',
                    'color' => 'Kolor',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'Jeden link został skopiowany',
            'good_more' => '#count# linki zostały skopiowane',
            'bad' => 'Wystąpił błąd podczas kopiowania!',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Clipboard`wymaga co najmniej wersji FreshRSS `%s`. (Używasz wersji FreshRSS `%s`)',
        ),
    ),
);
