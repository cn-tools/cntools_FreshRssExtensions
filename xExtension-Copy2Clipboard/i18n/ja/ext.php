<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'クリップボードにコピー',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => '選択',
                'desc' => '',
                'options' => array(
                    'default' => 'デフォルト',
                    'color' => '色',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => '1つのリンクがコピーされました',
            'good_more' => '#count#リンクがコピーされました',
            'bad' => 'コピー中にエラーが発生しました！',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Clipboard`required at least FreshRSS version `%s` (You use FreshRSS version `%s`)',
        ),
    ),
);
