<?php

return array(
    'filter_title' => array(
        'blacklist' => 'Svarteliste',
        'keywords_desc' => 'Hver linje sjekkes individuelt. I tillegg til vanlige tekster kan RegEx-uttrykk også defineres. Disse må kunne evalueres ved hjelp av PHP-funksjonen preg_match.',
        'mark_as_read' => 'Merk som lest',
        'mark_as_read_hint' => 'Dersom denne avmerkingsboksen er merket, vil uautoriserte oppføringer fortsatt bli skrevet inn i databasen, men vil bli merket som lest',
        'priority_hint' => 'Merk at svartelisten har høyere prioritet enn hvitelisten!',
        'warning' => [
            'not_allowed_msg' => '%s" er et forbudt søkeord. Kontakt administratoren for mer informasjon.',
        ],
        'whitelist' => 'Hviteliste',
    ),
);
