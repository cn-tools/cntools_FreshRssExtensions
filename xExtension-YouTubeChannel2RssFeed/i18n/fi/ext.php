<?php

return array(
    'YouTubeChannel2RssFeed' => array(
        '3rd_party' => array(
            'help' => array(
                'lnk_txt' => 'Dokumentaatio',
                'text' => 'Yksityiskohdat kokoonpanosta ja toiminnallisuudesta löytyvät täältä:',
            ),
            'hint' => 'URL:n avulla on tarkoitus määrittää, YouTube Shorts voidaan tunnistaa toisaalta ja YouTube-handelit voidaan muuntaa syötteiksi toisella puolella',
            'instance' => 'Seuraavien syyt:',
            'url' => 'URL-osoite',
        ),
        'install' => array(
            'bad_freshrss' => '`YouTubeChannel2RssFeed`vaaditaan ainakin FreshRSS versio `%s`. (Käytät FreshRSS versio `%s`)',
        ),
        'shorts' => array(
            'duration' => array(
                'label' => 'Kesto sekunteina',
                'hint' => 'Jos asetat keston yli 0, kaikki lyhyemmät videot tunnistetaan lyhyiksi, vaikka YouTube sanoo, että se ei ole lyhyt.',
            ),
            'label' =>  'YouTube Shortsit',
            'hint' => 'Tämä asetus on käytössä vain, jos instanssin URL-osoite on tallennettu. Jos mitään ei ole talletettu, shortsit ovat sallittuja.',
            'options' => array(
                'default' => array(
                    'text' => 'Oletus',
                    'title' => 'YouTube-Shortsien ja syötteen syöttö ei tarkista normaalina tietokantaan.',
                ),
                'block' => array(
                    'text' => 'Estä',
                    'title' => 'Jos uusi syöte on tunnistettu Youtube Short, sitä ei lisätä tietokantaan.',
                ),
                'mark_as_read' => array(
                    'text' => 'Merkitse luetuksi',
                    'title' => 'Jos uusi syöte on tunnustettu Youtube Short, se merkitään luetuksi.',
                ),
            ),
        ),
    ),
);
