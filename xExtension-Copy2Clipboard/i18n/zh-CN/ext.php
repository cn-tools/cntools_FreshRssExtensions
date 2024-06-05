<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => '复制到剪贴板',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => '选择',
                'desc' => '',
                'options' => array(
                    'default' => '默认设置',
                    'color' => '颜色',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => '一个链接已复制',
            'good_more' => '已复制 #count # 链接',
            'bad' => '复制过程中发生错误！',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Clipboard`至少需要FreshRSS 版本`%s`。(您使用FreshRSS 版本`%s`)',
        ),
    ),
);
