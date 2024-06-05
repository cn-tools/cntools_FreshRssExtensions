<?php

return array(
    'YouTubeChannel2RssFeed' => array(
        '3rd_party' => array(
            'help' => array(
                'lnk_txt' => 'Dokumentacja',
                'text' => 'Szczegółowe informacje na temat konfiguracji i funkcjonalności można znaleźć na stronie:',
            ),
            'hint' => 'Z pomocą adresu URL, który ma zostać podany, Skróty YouTube można zidentyfikować z jednej strony, a handele YouTube można przekształcić w kanały z drugiej strony',
            'instance' => 'Instancja:',
            'url' => 'Adres URL',
        ),
        'install' => array(
            'bad_freshrss' => '`YouTubeChannel2RssFeed`wymaga co najmniej wersji FreshRSS `%s`. (Używasz wersji FreshRSS `%s`)',
        ),
        'shorts' => array(
            'duration' => array(
                'label' => 'Czas trwania w sekundach',
                'hint' => 'Jeśli ustawisz czas trwania większy niż 0, wszystkie krótsze filmy zostaną zidentyfikowane jako krótkie, nawet jeśli YouTube mówi, że nie jest to krótkie.',
            ),
            'label' =>  'Skróty YouTube',
            'hint' => 'To ustawienie jest wykonywane tylko wtedy, gdy adres URL instancji jest przechowywany. Jeśli żaden nie jest zdeponowany, skróty są dozwolone.',
            'options' => array(
                'default' => array(
                    'text' => 'Domyślny',
                    'title' => 'Nie ma sprawdzania Skrótów YouTube i wpis kanału jest dodawany do bazy danych jak zwykle.',
                ),
                'block' => array(
                    'text' => 'Blok',
                    'title' => 'Jeśli nowy wpis kanału zostanie rozpoznany jako YouTube Short, nie zostanie dodany do bazy danych.',
                ),
                'mark_as_read' => array(
                    'text' => 'Oznacz jako przeczytane',
                    'title' => 'Jeśli nowy wpis kanału zostanie rozpoznany jako YouTube Short, zostanie oznaczony jako przeczytany.',
                ),
            ),
        ),
    ),
);
