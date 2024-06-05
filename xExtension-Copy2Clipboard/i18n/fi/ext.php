<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'Kopioi leikepöydälle',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Valitse',
                'desc' => '',
                'options' => array(
                    'default' => 'Oletus',
                    'color' => 'Väri',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'Yksi linkki kopioitu',
            'good_more' => '#count# linkkejä kopioitiin',
            'bad' => 'Kopioinnin aikana tapahtui virhe!',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Leiketaulu`Tarvitaan ainakin FreshRSS versio `%s`. (Käytät FreshRSS versio `%s`)',
        ),
    ),
);
