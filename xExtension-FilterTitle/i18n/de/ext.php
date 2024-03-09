<?php

return array(
	'filter_title' => array(
		'keywords' => 'Schlüsselwörter',
		'keywords_desc' => 'Jede Zeile wird einzeln geprüft. Zusätzlich zu normalen Texten können auch RegEx-Expressions definieren werden. Diese müssen mit der PHP-Funktion `preg_match` ausgewertet werden können.',
        'check_type' => 'Nutzen als',
        'check_type_desc' => 'Hier legen sie fest, ob die angegebenen Schlüsselwörter als Blacklist oder als Whitelist genutzt werden sollen.        ',
        'block' => 'Blacklist',
        'free' => 'Whitelist',
        'warning' => [
            'not_allowed_msg' => '"%s" ist ein gesperrtes Schlüsselwort. Kontaktieren sie ihren Administrator für mehr Informationen.',
        ],
	),
);