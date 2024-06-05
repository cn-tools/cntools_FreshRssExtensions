<?php

return array(
    'filter_title' => array(
        'blacklist' => 'Czarna lista',
        'keywords_desc' => 'Każda linia jest zaznaczona indywidualnie. Oprócz zwykłych tekstów, wyrażenia RegEx mogą być również zdefiniowane. Muszą być możliwe do oceny przy użyciu funkcji PHP preg_match.',
        'mark_as_read' => 'Oznacz jako przeczytane',
        'mark_as_read_hint' => 'Jeśli to pole wyboru jest zaznaczone, nieautoryzowane wpisy będą nadal wprowadzane do bazy danych, ale zostaną oznaczone jako przeczytane',
        'priority_hint' => 'Pamiętaj, że czarna lista ma wyższy priorytet niż biała lista!',
        'warning' => [
            'not_allowed_msg' => '"%s" to zbanowane słowo kluczowe. Aby uzyskać więcej informacji, skontaktuj się z administratorem.',
        ],
        'whitelist' => 'Biała lista',
    ),
);
