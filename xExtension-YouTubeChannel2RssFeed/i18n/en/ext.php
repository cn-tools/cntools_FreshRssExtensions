<?php

return array(
    'YouTubeChannel2RssFeed' => array(
        '3rd_party' => array(
            'hint' => 'With the help of the URL to be specified, YouTube Shorts can be identified on the one hand and YouTube handels can be converted into feeds on the other',
            'instance' => 'Instance of:',
            'url' => 'URL',
        ),
        'install' => array(
            'bad_freshrss' => '`YouTubeChannel2RssFeed`required at least FreshRSS version `%s`. (You use FreshRSS version `%s`)',
        ),
        'shorts' => array(
            'label' =>  'YouTube Shorts',
            'hint' => 'This setting is only taken if an instance URL is stored. If none is deposited, shorts are permitted',
            'options' => array(
                'default' => array(
                    'text' => 'Default',
                    'title' => 'No check for YouTube Shorts and the feed entry is added to the database as normal',
                ),
                'block' => array(
                    'text' => 'Block',
                    'title' => 'If the new feed entry is recognized as a Youtube Short, it will not be added into the database',
                ),
                'mark_as_read' => array(
                    'text' => 'Mark as read',
                    'title' => 'If the new feed entry is recognized as a Youtube Short, it will be marked as read',
                ),
            ),
        ),
    ),
);
