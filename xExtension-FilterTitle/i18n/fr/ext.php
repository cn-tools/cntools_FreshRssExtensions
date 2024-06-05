<?php

return array(
    'filter_title' => array(
        'blacklist' => 'Liste noire',
        'keywords_desc' => 'Chaque ligne est vérifiée individuellement. En plus des textes normaux, les expressions RegEx peuvent également être définies. Ils doivent pouvoir être évalués en utilisant la fonction PHP preg_match.',
        'mark_as_read' => 'Marquer comme lu',
        'mark_as_read_hint' => 'Si cette case est cochée, les entrées non autorisées seront toujours entrées dans la base de données mais seront marquées comme lues',
        'priority_hint' => 'Notez que la liste noire a une priorité plus élevée que la liste blanche!',
        'warning' => [
            'not_allowed_msg' => '«%s» est un mot clé banni. Contactez votre administrateur pour plus d\'informations.',
        ],
        'whitelist' => 'Liste blanche',
    ),
);
