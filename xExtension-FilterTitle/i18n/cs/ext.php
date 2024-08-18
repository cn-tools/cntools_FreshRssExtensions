<?php

return array(
    'filter_title' => array(
        'blacklist' => 'Černá listina',
        'keywords_desc' => 'Každý řádek je kontrolován jednotlivě. Kromě normálních textů lze také definovat výrazy RegEx. Ty musí být možné vyhodnotit pomocí PHP funkce preg_match.',
        'mark_as_read' => 'Označit jako přečtené',
        'mark_as_read_hint' => 'Pokud je zaškrtnuto toto políčko, neoprávněné položky budou stále zadány do databáze, ale budou označeny jako přečtené',
        'priority_hint' => 'Všimněte si, že černá listina má vyšší prioritu než seznam povolených hráčů!',
        'warning' => [
            'not_allowed_msg' => '"%s" je zabanované klíčové slovo. Pro více informací kontaktujte svého správce.',
        ],
        'whitelist' => 'Seznam povolených',
    ),
);
