<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'Копировать в буфер обмена',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Выбрать',
                'desc' => '',
                'options' => array(
                    'default' => 'По умолчанию',
                    'color' => 'Цвет',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'Одна ссылка скопирована',
            'good_more' => 'Ссылки #count# скопированы',
            'bad' => 'Произошла ошибка при копировании!',
        ),
        'install' => array(
            'bad_freshrss' => '`Копировать2Буфер обмена`требуется, по крайней мере FreshRSS версия`%s`. (Вы используете FreshRSS версию`%s`)',
        ),
    ),
);
