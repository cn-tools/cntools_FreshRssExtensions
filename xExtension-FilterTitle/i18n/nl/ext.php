<?php

return array(
    'filter_title' => array(
        'blacklist' => 'Geblokkeerde',
        'keywords_desc' => 'Elke regel wordt afzonderlijk gecontroleerd. Naast normale teksten, kunnen RegEx expressies ook worden gedefinieerd. Deze moeten geëvalueerd kunnen worden met behulp van de PHP function preg_match.',
        'mark_as_read' => 'Markeren als gelezen',
        'mark_as_read_hint' => 'Als dit selectievakje is aangevinkt, worden de ongeautoriseerde items nog steeds in de database opgeslagen, maar worden gemarkeerd als gelezen',
        'priority_hint' => 'Merk op dat de blacklist hogere prioriteit heeft dan de whitelist!',
        'warning' => [
            'not_allowed_msg' => '"%s" is een verbannen sleutelwoord. Neem contact op met uw beheerder voor meer informatie.',
        ],
        'whitelist' => 'Witte',
    ),
);
