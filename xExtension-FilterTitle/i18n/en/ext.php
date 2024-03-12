<?php

return array(
    'filter_title' => array(
        'blacklist' => 'Blacklist',
        'keywords_desc' => 'Each line is checked individually. In addition to normal texts, RegEx expressions can also be defined. These must be able to be evaluated using the PHP function preg_match.',
        'mark_as_read' => 'Mark as read',
        'mark_as_read_hint' => 'If this checkbox is checked, the unauthorized entries will still be entered into the database but will be marked as read',
        'priority_hint' => 'Note that the blacklist has higher priority than the whitelist!',
        'warning' => [
            'not_allowed_msg' => '"%s" is a banned keyword. Contact your administrator for more information.',
        ],
        'whitelist' => 'Whitelist',
    ),
);
