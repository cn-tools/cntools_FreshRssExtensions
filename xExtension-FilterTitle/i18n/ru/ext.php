<?php

return array(
    'filter_title' => array(
        'blacklist' => 'Черный список',
        'keywords_desc' => 'Каждая строка проверяется отдельно. В дополнение к обычным текстам могут быть также определены регулярные выражения. Они должны быть оценены с помощью функции PHP preg_match.',
        'mark_as_read' => 'Отметить как прочитанное',
        'mark_as_read_hint' => 'Если этот флажок установлен, неавторизованные записи будут введены в базу данных, но будут помечены как прочитанные',
        'priority_hint' => 'Обратите внимание, что черный список имеет более высокий приоритет, чем белый список!',
        'warning' => [
            'not_allowed_msg' => '"%s" это запрещенное ключевое слово. Свяжитесь с вашим администратором для получения дополнительной информации.',
        ],
        'whitelist' => 'Белый список',
    ),
);