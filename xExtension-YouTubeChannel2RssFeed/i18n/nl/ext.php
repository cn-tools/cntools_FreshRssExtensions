<?php

return array(
    'YouTubeChannel2RssFeed' => array(
        '3rd_party' => array(
            'help' => array(
                'lnk_txt' => 'Documentatie',
                'text' => 'Details over de configuratie en functionaliteit kan je hier vinden:',
            ),
            'hint' => 'Met behulp van de op te geven URL YouTube Shorts kunnen aan de ene kant worden geïdentificeerd en YouTube handels kunnen worden omgezet in feeds aan de andere kant',
            'instance' => 'Instantie van:',
            'url' => 'URL',
        ),
        'install' => array(
            'bad_freshrss' => '`YouTubeChannel2RssFeed`vereist op zijn minst FreshRSS versie `%s`. (U gebruikt FreshRSS versie `%s`)',
        ),
        'shorts' => array(
            'duration' => array(
                'label' => 'Duur in seconden',
                'hint' => 'Als u een duur groter dan 0 instelt, worden alle kortere video\'s geïdentificeerd als kort, zelfs als YouTube zegt dat het niet kort is.',
            ),
            'label' =>  'YouTube Korten',
            'hint' => 'Deze instelling wordt alleen gebruikt als de URL van een installatie is opgeslagen. Als er geen storting is, is korter toegestaan.',
            'options' => array(
                'default' => array(
                    'text' => 'Standaard',
                    'title' => 'Er is geen controle op YouTube Shorts en de feed is als normaal aan de database toegevoegd.',
                ),
                'block' => array(
                    'text' => 'Blokkeren',
                    'title' => 'Als de nieuwe feed herkend wordt als een Youtube Kort, wordt deze niet toegevoegd aan de database.',
                ),
                'mark_as_read' => array(
                    'text' => 'Markeren als gelezen',
                    'title' => 'Als de nieuwe feed wordt herkend als een Youtube Kort, wordt deze gemarkeerd als gelezen.',
                ),
            ),
        ),
    ),
);
