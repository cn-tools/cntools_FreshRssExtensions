<?php

return array(
    'filter_title' => array(
        'blacklist' => 'Mustalista',
        'keywords_desc' => 'Jokainen rivi tarkistetaan erikseen. Tavallisten tekstien lisäksi myös RegEx-lausekkeet voidaan määritellä. Nämä on voitava arvioida käyttäen PHP funktio preg_match.',
        'mark_as_read' => 'Merkitse luetuksi',
        'mark_as_read_hint' => 'Jos tämä valintaruutu on valittuna, luvattomat merkinnät syötetään edelleen tietokantaan, mutta ne merkitään luetuiksi',
        'priority_hint' => 'Huomaa, että mustalla listalla on suurempi prioriteetti kuin valkoisella listalla!',
        'warning' => [
            'not_allowed_msg' => '"%s" on estetty avainsana. Ota yhteyttä järjestelmänvalvojaan saadaksesi lisätietoja.',
        ],
        'whitelist' => 'Sallitut',
    ),
);
