<?php

return array(
    'filter_title' => array(
        'blacklist' => 'Blacklist',
        'keywords_desc' => 'Jede Zeile wird einzeln geprüft. Zusätzlich zu normalen Texten können auch RegEx-Expressions definieren werden. Diese müssen mit der PHP-Funktion `preg_match` ausgewertet werden können.',
        'priority_hint' => 'Beachten Sie, dass die Blacklist höhere Priorität hat als die Whitelist!',
        'warning' => [
            'not_allowed_msg' => '"%s" ist ein gesperrtes Schlüsselwort. Kontaktieren sie ihren Administrator für mehr Informationen.',
        ],
        'whitelist' => 'Whitelist',
    ),
);
