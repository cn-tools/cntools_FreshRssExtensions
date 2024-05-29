<?php

return array(
    'YouTubeChannel2RssFeed' => array(
        '3rd_party' => array(
            'help' => array(
                'lnk_txt' => 'Dokumentation',
                'text' => 'Details über die Konfiguration und Funktionsweise finden sie hier:',
            ),
            'hint' => 'Mit Hilfe der anzugebenden URL können einerseits YoutTube Shorts identifiziert werden und andererseits können YouTube Handels in Feeds umgewandelt werden.',
            'instance' => 'Instanz von:',
            'url' => 'URL',
        ),
        'install' => array(
            'bad_freshrss' => 'Für "YouTubeChannel2RssFeed" ist mindestens die FreshRSS-Version `%s` erforderlich. (Sie verwenden die FreshRSS-Version `%s`)',
        ),
        'shorts' => array(
            'duration' => array(
                'label' => 'Dauer in Sekunden',
                'hint' => 'Wenn Sie eine Dauer größer als 0 festlegen, werden alle kürzeren Videos als Short idetifiziert, selbst wenn es laut YouTube kein Short ist.',
            ),
            'label' =>  'YouTube Shorts',
            'hint' => 'Diese Einstellung wird nur berücksichtigt, wenn eine Instanz-URL hinterlegt ist. Ist keine hinterlegt, werden Shorts erlaubt.',
            'options' => array(
                'default' => array(
                    'text' => 'Standard',
                    'title' => 'Keine Prüfung auf Youtube Shorts und der Feed-Eintrag wird ganz normal in die Datenbank aufgenommen.',
                ),
                'block' => array(
                    'text' => 'Blockieren',
                    'title' => 'Wird der neue Feed-Eintrag als Youtube Short erkannt, wird dieser nicht in die Datenbank aufgenommen.',
                ),
                'mark_as_read' => array(
                    'text' => 'Als gelesen markieren',
                    'title' => 'Wird der neue Feed-Eintrag als Youtube Short erkannt, wird dieser als gelesen markiert.',
                ),
            ),
        ),
    ),
);
