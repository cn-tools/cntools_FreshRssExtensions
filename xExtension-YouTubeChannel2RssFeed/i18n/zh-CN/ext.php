<?php

return array(
    'YouTubeChannel2RssFeed' => array(
        '3rd_party' => array(
            'help' => array(
                'lnk_txt' => '文件',
                'text' => '关于配置和功能的详细信息可以在这里找到：',
            ),
            'hint' => '使用指定的 URL 帮助， YouTube Shorts 可以一方面识别，YouTube handels 可以转换为feeds',
            'instance' => '实例：',
            'url' => '网址',
        ),
        'install' => array(
            'bad_freshrss' => '`YouTubeChannel2RssFeed`至少需要FreshRSS 版本`%s`。(您使用FreshRSS 版本`%s`)',
        ),
        'shorts' => array(
            'duration' => array(
                'label' => '持续时间（秒）',
                'hint' => '如果您设置的持续时间大于0，即使YouTube说它不短，所有较短的视频都会被识别为短暂。',
            ),
            'label' =>  'YouTube 短处',
            'hint' => '此设置仅在一个实例 URL 存储时才使用。如果没有存放，则允许短暂。',
            'options' => array(
                'default' => array(
                    'text' => '默认设置',
                    'title' => '不检查YouTube Shorts和订阅条目作为正常内容添加到数据库中。',
                ),
                'block' => array(
                    'text' => '封禁',
                    'title' => '如果新的 Feed 条目被识别为 Youtube 短，它将不会被添加到数据库中。',
                ),
                'mark_as_read' => array(
                    'text' => '标记为已读',
                    'title' => '如果新的 Feed 条目被识别为 Youtube 短，它将被标记为已读。',
                ),
            ),
        ),
    ),
);
