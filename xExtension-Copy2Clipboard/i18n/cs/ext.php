<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'Kopírovat do schránky',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Zvolit',
                'desc' => '',
                'options' => array(
                    'default' => 'Výchozí',
                    'color' => 'Barva',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'Jeden odkaz byl zkopírován',
            'good_more' => '#count# odkazy byly zkopírovány',
            'bad' => 'Došlo k chybě při kopírování!',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Clipboard`vyžaduje alespoň FreshRSS verzi `%s`. (Používáš FreshRSS verzi `%s`)',
        ),
    ),
);
