<?php

return array(
    'YouTubeChannel2RssFeed' => array(
        '3rd_party' => array(
            'help' => array(
                'lnk_txt' => 'Dokumentation',
                'text' => 'Detaljer om konfigurationen og funktionaliteten kan findes her:',
            ),
            'hint' => 'Med hjælp fra den URL, der skal angives, YouTube Shorts kan identificeres på den ene side og YouTube handels kan konverteres til feeds på den anden side',
            'instance' => 'Forekomst af:',
            'url' => 'URL',
        ),
        'install' => array(
            'bad_freshrss' => '`YouTubeChannel2RssFeed`kræves mindst FreshRSS version `%s`. (Du bruger FreshRSS version `%s`)',
        ),
        'shorts' => array(
            'duration' => array(
                'label' => 'Varighed i sekunder',
                'hint' => 'Hvis du indstiller en varighed større end 0, vil alle kortere videoer blive identificeret som korte, selvom YouTube siger, at det ikke er kort.',
            ),
            'label' =>  'YouTube Shorts',
            'hint' => 'Denne indstilling er kun taget hvis en instans URL er gemt. Hvis ingen er indbetalt, er shorts tilladt.',
            'options' => array(
                'default' => array(
                    'text' => 'Standard',
                    'title' => 'Ingen kontrol for YouTube Shorts og feed-indgangen er tilføjet til databasen som normalt.',
                ),
                'block' => array(
                    'text' => 'Blokér',
                    'title' => 'Hvis den nye feed post er anerkendt som en Youtube Short, vil det ikke blive tilføjet i databasen.',
                ),
                'mark_as_read' => array(
                    'text' => 'Markér som læst',
                    'title' => 'Hvis den nye feed post er anerkendt som en Youtube Short, vil det blive markeret som læst.',
                ),
            ),
        ),
    ),
);
