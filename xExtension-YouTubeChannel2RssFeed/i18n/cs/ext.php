<?php

return array(
    'YouTubeChannel2RssFeed' => array(
        '3rd_party' => array(
            'help' => array(
                'lnk_txt' => 'Dokumentace',
                'text' => 'Podrobnosti o konfiguraci a funkčnosti naleznete zde:',
            ),
            'hint' => 'S pomocí URL adresy, která má být zadána, YouTube Krátky lze identifikovat na jedné straně a YouTube handely lze převést na kanály na straně druhé.',
            'instance' => 'Instance pro:',
            'url' => 'URL',
        ),
        'install' => array(
            'bad_freshrss' => '`YouTubeChannel2RssFeed`vyžaduje alespoň FreshRSS verzi `%s`. (Používáte FreshRSS verzi `%s`)',
        ),
        'shorts' => array(
            'duration' => array(
                'label' => 'Doba trvání v sekundách',
                'hint' => 'Pokud nastavíte dobu trvání delší než 0, všechna kratší videa budou identifikována jako krátká, i když YouTube říká, že to není krátké.',
            ),
            'label' =>  'Krátky na YouTube',
            'hint' => 'Toto nastavení je použito pouze v případě, že je uložena URL instance. Pokud není uloženo, jsou povoleny zkratky.',
            'options' => array(
                'default' => array(
                    'text' => 'Výchozí',
                    'title' => 'Žádná kontrola pro YouTube Krátky a položka kanálu je přidána do databáze jako normální.',
                ),
                'block' => array(
                    'text' => 'Blokovat',
                    'title' => 'Pokud je nová položka kanálu rozpoznána jako YouTube krátká, nebude přidána do databáze.',
                ),
                'mark_as_read' => array(
                    'text' => 'Označit jako přečtené',
                    'title' => 'Pokud je nová položka kanálu rozpoznána jako YouTube Short, bude označena jako přečtená.',
                ),
            ),
        ),
    ),
);
