<?php

return array(
    'filter_title' => array(
        'blacklist' => 'Sortliste',
        'keywords_desc' => 'Hver linje kontrolleres individuelt. Ud over normale tekster, kan RegEx udtryk også defineres. Disse skal kunne evalueres ved hjælp af PHP-funktionen preg_match.',
        'mark_as_read' => 'Markér som læst',
        'mark_as_read_hint' => 'Hvis dette afkrydsningsfelt er markeret, vil uautoriserede indgange stadig blive indtastet i databasen, men vil blive markeret som læst',
        'priority_hint' => 'Bemærk, at sortlisten har højere prioritet end hvidlisten!',
        'warning' => [
            'not_allowed_msg' => '"%s" er et bandlyst søgeord. Kontakt din administrator for mere information.',
        ],
        'whitelist' => 'Hvidliste',
    ),
);
