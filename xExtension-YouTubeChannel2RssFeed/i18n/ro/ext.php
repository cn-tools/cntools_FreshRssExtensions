<?php

return array(
    'YouTubeChannel2RssFeed' => array(
        '3rd_party' => array(
            'help' => array(
                'lnk_txt' => 'Documentație',
                'text' => 'Detalii despre configurare și funcționalitate pot fi găsite aici:',
            ),
            'hint' => 'Cu ajutorul URL-ului care urmează să fie specificat, Short-urile YouTube pot fi identificate pe de o parte, iar handelurile YouTube pot fi convertite în feed-uri pe de altă parte',
            'instance' => 'Instanța din:',
            'url' => 'URL',
        ),
        'install' => array(
            'bad_freshrss' => '`YouTubeChannel2RsFeed`a necesitat cel puţin versiunea FreshRSS `%s`. (Utilizaţi versiunea FreshRSS `%s`)',
        ),
        'shorts' => array(
            'duration' => array(
                'label' => 'Durata în secunde',
                'hint' => 'Dacă setați o durată mai mare de 0, toate videoclipurile mai scurte vor fi identificate ca scurte, chiar dacă YouTube spune că nu sunt scurte.',
            ),
            'label' =>  'Short-uri YouTube',
            'hint' => 'Această setare este luată numai dacă este stocată o adresă URL de instanță. Dacă nu este depozitată, sunt permise scurtături.',
            'options' => array(
                'default' => array(
                    'text' => 'Implicit',
                    'title' => 'Nici o verificare pentru YouTube Shorts şi intrarea feed este adăugată în baza de date ca de obicei.',
                ),
                'block' => array(
                    'text' => 'Blochează',
                    'title' => 'Dacă noua intrare de feed este recunoscută ca un Youtube Short, aceasta nu va fi adăugată în baza de date.',
                ),
                'mark_as_read' => array(
                    'text' => 'Marchează ca citit',
                    'title' => 'Dacă noua intrare de feed este recunoscută ca fiind un scurt YouTube acesta va fi marcat ca fiind citit.',
                ),
            ),
        ),
    ),
);
