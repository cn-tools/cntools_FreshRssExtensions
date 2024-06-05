<?php

return array(
    'YouTubeChannel2RssFeed' => array(
        '3rd_party' => array(
            'help' => array(
                'lnk_txt' => 'Documentazione',
                'text' => 'I dettagli sulla configurazione e la funzionalità possono essere trovati qui:',
            ),
            'hint' => 'Con l\'aiuto dell\'URL da specificare, YouTube Shorts possono essere identificati da un lato e gli handels di YouTube possono essere convertiti in feed dall\'altro',
            'instance' => 'Istanza di:',
            'url' => 'URL',
        ),
        'install' => array(
            'bad_freshrss' => '`YouTubeChannel2RsFeed`required at least FreshRSS version `%s`. (You use FreshRSS version `%s`)',
        ),
        'shorts' => array(
            'duration' => array(
                'label' => 'Durata in secondi',
                'hint' => 'Se si imposta una durata maggiore di 0, tutti i video più corti saranno identificati come brevi, anche se YouTube dice che non è breve.',
            ),
            'label' =>  'Youtube Shorts',
            'hint' => 'Questa impostazione viene presa solo se viene memorizzato un URL di istanza. Se non viene depositato, i pantaloncini sono consentiti.',
            'options' => array(
                'default' => array(
                    'text' => 'Predefinito',
                    'title' => 'Nessun controllo per YouTube Shorts e la voce feed viene aggiunta al database come normale.',
                ),
                'block' => array(
                    'text' => 'Blocca',
                    'title' => 'Se la nuova voce feed è riconosciuta come Youtube Short, non verrà aggiunta nel database.',
                ),
                'mark_as_read' => array(
                    'text' => 'Segna come letto',
                    'title' => 'Se la nuova voce feed è riconosciuta come Youtube Short, sarà contrassegnata come letta.',
                ),
            ),
        ),
    ),
);
