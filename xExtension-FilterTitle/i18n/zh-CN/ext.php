<?php

return array(
    'filter_title' => array(
        'blacklist' => '黑名单',
        'keywords_desc' => '单独检查每一行。除了普通文本，RegEx 表达式也可以定义。 这些必须能够使用 PHP 函数 preg_match 进行评估。',
        'mark_as_read' => '标记为已读',
        'mark_as_read_hint' => '如果选中此复选框，则未授权的条目仍将被输入数据库，但将被标记为已读',
        'priority_hint' => '注意黑名单比白名单优先级更高！',
        'warning' => [
            'not_allowed_msg' => '%s是一个被禁止的关键词。请联系您的管理员了解更多信息。',
        ],
        'whitelist' => '白名单',
    ),
);
