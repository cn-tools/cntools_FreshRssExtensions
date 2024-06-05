<?php

return array(
    'filter_title' => array(
        'blacklist' => 'Blacklist',
        'keywords_desc' => 'Ogni riga è selezionata singolarmente. Oltre ai testi normali, possono essere definite anche le espressioni RegEx. Questi devono essere in grado di essere valutati utilizzando la funzione PHP preg_match.',
        'mark_as_read' => 'Segna come letto',
        'mark_as_read_hint' => 'Se questa casella è selezionata, le voci non autorizzate saranno ancora inserite nel database ma saranno contrassegnate come lette',
        'priority_hint' => 'Nota che la blacklist ha priorità maggiore rispetto alla whitelist!',
        'warning' => [
            'not_allowed_msg' => '"%s" è una parola chiave vietata. Contatta l\'amministratore per ulteriori informazioni.',
        ],
        'whitelist' => 'Whitelist',
    ),
);
