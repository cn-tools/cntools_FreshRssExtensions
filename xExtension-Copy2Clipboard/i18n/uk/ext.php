<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'Скопіювати до буферу обміну',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Вибрати',
                'desc' => '',
                'options' => array(
                    'default' => 'Типово',
                    'color' => 'Колір',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'Одне посилання скопійовано',
            'good_more' => '#count# посилання були скопійовані',
            'bad' => 'Сталася помилка під час копіювання!',
        ),
        'install' => array(
            'bad_freshrss' => 'Для роботи `Copy2Clipboard` потрібна версія FreshRSS не нижче `%s`. (Ви використовуєте FreshRSS версії `%s`)',
        ),
    ),
);
