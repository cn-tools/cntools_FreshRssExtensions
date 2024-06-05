<?php

return array(
    'filter_title' => array(
        'blacklist' => 'Svartlista',
        'keywords_desc' => 'Varje rad kontrolleras individuellt. Förutom vanliga texter kan även RegEx-uttryck definieras. Dessa måste kunna utvärderas med hjälp av PHP funktionen preg_match.',
        'mark_as_read' => 'Markera som läst',
        'mark_as_read_hint' => 'Om denna kryssruta är markerad, kommer obehöriga poster fortfarande att anges i databasen men kommer att markeras som lästa',
        'priority_hint' => 'Observera att svartlistan har högre prioritet än vitlistan!',
        'warning' => [
            'not_allowed_msg' => '"%s" är ett bannlyst sökord. Kontakta administratören för mer information.',
        ],
        'whitelist' => 'Vitlista',
    ),
);
