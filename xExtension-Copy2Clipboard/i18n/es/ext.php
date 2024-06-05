<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'Copiar al portapapeles',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Elegir',
                'desc' => '',
                'options' => array(
                    'default' => 'Por defecto',
                    'color' => 'Color',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'Un enlace fue copiado',
            'good_more' => '#count# enlaces copiados',
            'bad' => '¡Se ha producido un error durante la copia!',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Clipboard`requiere al menos la versión FreshRSS `%s`. (Usted usa la versión FreshRSS `%s`)',
        ),
    ),
);
