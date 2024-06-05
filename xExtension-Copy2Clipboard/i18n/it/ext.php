<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'Copia negli appunti',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Scegli',
                'desc' => '',
                'options' => array(
                    'default' => 'Predefinito',
                    'color' => 'Colore',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'Un link è stato copiato',
            'good_more' => '#count# i link sono stati copiati',
            'bad' => 'Si è verificato un errore durante la copia!',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Clipboard`required least FreshRSS version `%s`. (You use FreshRSS version `%s`)',
        ),
    ),
);
