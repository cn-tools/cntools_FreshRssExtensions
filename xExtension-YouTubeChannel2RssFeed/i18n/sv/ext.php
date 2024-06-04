<?php

return array(
    'YouTubeChannel2RssFeed' => array(
        '3rd_party' => array(
            'help' => array(
                'lnk_txt' => 'Dokumentation',
                'text' => 'Detaljer om konfigurationen och funktionaliteten hittar du här:',
            ),
            'hint' => 'Med hjälp av den URL som ska anges, YouTube Shorts kan identifieras å ena sidan och YouTube handel kan omvandlas till feeds å andra sidan',
            'instance' => 'Instans av:',
            'url' => 'URL',
        ),
        'install' => array(
            'bad_freshrss' => '`YouTubeChannel2RssFeed`krävs åtminstone FreshRSS version `%s`. (Du använder FreshRSS version `%s`)',
        ),
        'shorts' => array(
            'duration' => array(
                'label' => 'Varaktighet i sekunder',
                'hint' => 'Om du anger en varaktighet större än 0, kommer alla kortare videor att identifieras som korta, även om YouTube säger att det inte är kort.',
            ),
            'label' =>  'YouTube Shorts',
            'hint' => 'Denna inställning tas endast om en instans-URL lagras. Om ingen är deponerad är shorts tillåtna.',
            'options' => array(
                'default' => array(
                    'text' => 'Standard',
                    'title' => 'Ingen kontroll för YouTube Shorts och foder posten läggs till i databasen som vanligt.',
                ),
                'block' => array(
                    'text' => 'Blockera',
                    'title' => 'Om det nya flödet erkänns som en Youtube Short, kommer det inte att läggas till i databasen.',
                ),
                'mark_as_read' => array(
                    'text' => 'Markera som läst',
                    'title' => 'Om den nya matarposten känns igen som en Youtube Short, kommer den att markeras som läst.',
                ),
            ),
        ),
    ),
);
