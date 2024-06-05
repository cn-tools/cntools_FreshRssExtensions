<?php

return array(
    'filter_title' => array(
        'blacklist' => 'Lista neagră',
        'keywords_desc' => 'Fiecare linie este verificată individual. În plus față de textele normale, expresiile RegEx pot fi, de asemenea, definite. Acestea trebuie să poată fi evaluate folosind funcţia PHP preg_match.',
        'mark_as_read' => 'Marchează ca citit',
        'mark_as_read_hint' => 'Dacă această casetă este bifată, intrările neautorizate vor fi introduse în baza de date, dar vor fi marcate ca citite',
        'priority_hint' => 'Țineți cont că lista neagră are prioritate mai mare decât lista albă!',
        'warning' => [
            'not_allowed_msg' => '"%s" este un cuvânt cheie interzis. Contactați administratorul pentru mai multe informații.',
        ],
        'whitelist' => 'Lista albă',
    ),
);
