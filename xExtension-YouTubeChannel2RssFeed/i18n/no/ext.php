<?php

return array(
    'YouTubeChannel2RssFeed' => array(
        '3rd_party' => array(
            'help' => array(
                'lnk_txt' => 'Dokumentasjon',
                'text' => 'Detaljer om konfigurasjonen og funksjonaliteten finner du her:',
            ),
            'hint' => 'Ved hjelp av nettadressen som skal angis, YouTube Shorts kan identifiseres på den ene siden, og YouTube-hendene kan konverteres til feeds på den andre',
            'instance' => 'Forekomst av:',
            'url' => 'Nettadresse',
        ),
        'install' => array(
            'bad_freshrss' => '`YouTubeChannel2RssFeed`som kreves minst FreshRSS versjon `%s`. (Du bruker FreshRSS versjon `%s`)',
        ),
        'shorts' => array(
            'duration' => array(
                'label' => 'Varighet i sekunder',
                'hint' => 'Hvis du setter en varighet større enn 0, vil alle kortere videoer bli identifisert som kort, selv om YouTube sier at det ikke er kort.',
            ),
            'label' =>  'YouTube Shorts',
            'hint' => 'Denne innstillingen er bare tatt hvis en instans URL er lagret. Hvis ingen er deponert, er forkortet tillatt.',
            'options' => array(
                'default' => array(
                    'text' => 'Standard',
                    'title' => 'Ingen sjekk for YouTube Shorts og feed oppføring er lagt til databasen som normalt.',
                ),
                'block' => array(
                    'text' => 'Blokker',
                    'title' => 'Hvis den nye feed-oppføringen er gjenkjent som et Youtube Short, blir den ikke lagt til i databasen.',
                ),
                'mark_as_read' => array(
                    'text' => 'Merk som lest',
                    'title' => 'Hvis den nye innføringen i matingen er gjenkjent som et YouTube Short, blir den merket som lest.',
                ),
            ),
        ),
    ),
);
