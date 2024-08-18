<?php

return array(
    'filter_title' => array(
        'blacklist' => 'Lista Negra',
        'keywords_desc' => 'Cada linha é verificada individualmente. Além dos textos normais, expressões RegEx também podem ser definidas. Estes devem ser avaliados utilizando a função preg_match PHP.',
        'mark_as_read' => 'Marcar Tudo como Lido',
        'mark_as_read_hint' => 'Se esta opção estiver marcada, as entradas não autorizadas ainda serão inseridas na base de dados mas serão marcadas como lidas',
        'priority_hint' => 'Observe que a lista negra tem maior prioridade que a lista branca!',
        'warning' => [
            'not_allowed_msg' => '%s" é uma palavra-chave banida. Entre em contato com seu administrador para obter mais informações.',
        ],
        'whitelist' => 'Lista Branca',
    ),
);
