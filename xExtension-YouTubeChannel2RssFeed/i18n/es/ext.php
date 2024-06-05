<?php

return array(
    'YouTubeChannel2RssFeed' => array(
        '3rd_party' => array(
            'help' => array(
                'lnk_txt' => 'Documentación',
                'text' => 'Los detalles sobre la configuración y la funcionalidad se pueden encontrar aquí:',
            ),
            'hint' => 'Con la ayuda de la URL a especificar, Los breves de YouTube se pueden identificar por un lado y los handels de YouTube se pueden convertir en feeds por el otro',
            'instance' => 'Instancia de:',
            'url' => 'URL',
        ),
        'install' => array(
            'bad_freshrss' => '`YouTubeChannel2RssFeed`requiere al menos la versión FreshRSS `%s`. (Ud usa la versión FreshRSS `%s`)',
        ),
        'shorts' => array(
            'duration' => array(
                'label' => 'Duración en segundos',
                'hint' => 'Si estableces una duración mayor a 0, todos los vídeos más cortos se identificarán como cortos, incluso si YouTube dice que no es corto.',
            ),
            'label' =>  'Acortes de YouTube',
            'hint' => 'Esta opción sólo se toma si se almacena una URL de la instancia. Si no se deposita ninguna, se permiten cortos.',
            'options' => array(
                'default' => array(
                    'text' => 'Por defecto',
                    'title' => 'No hay cheques de YouTube cortos y la entrada del feed se añade a la base de datos de forma normal.',
                ),
                'block' => array(
                    'text' => 'Bloque',
                    'title' => 'Si la nueva entrada del feed es reconocida como un YouTube corto, no se añadirá a la base de datos.',
                ),
                'mark_as_read' => array(
                    'text' => 'Marcar como leído',
                    'title' => 'Si la nueva entrada del feed es reconocida como un corto de Youtube, se marcará como leída.',
                ),
            ),
        ),
    ),
);
