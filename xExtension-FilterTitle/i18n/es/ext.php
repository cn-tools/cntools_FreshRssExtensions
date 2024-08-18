<?php

return array(
    'filter_title' => array(
        'blacklist' => 'Lista negra',
        'keywords_desc' => 'Cada línea es comprobada individualmente. Además de los textos normales, las expresiones RegEx también pueden ser definidas. Estos deben ser capaces de ser evaluados usando la función PHP preg_match.',
        'mark_as_read' => 'Marcar como leído',
        'mark_as_read_hint' => 'Si esta casilla está marcada, las entradas no autorizadas serán introducidas en la base de datos pero serán marcadas como leídas',
        'priority_hint' => 'Tenga en cuenta que la lista negra tiene mayor prioridad que la lista blanca!',
        'warning' => [
            'not_allowed_msg' => '"%s" es una palabra clave prohibida. Póngase en contacto con su administrador para obtener más información.',
        ],
        'whitelist' => 'Lista blanca',
    ),
);
