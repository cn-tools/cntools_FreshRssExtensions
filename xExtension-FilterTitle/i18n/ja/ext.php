<?php

return array(
    'filter_title' => array(
        'blacklist' => 'ブラック リスト',
        'keywords_desc' => '各行は個別にチェックされます。正規表現に加えて正規表現を定義することもできます。 これらはPHP関数preg_matchを使用して評価できる必要があります。',
        'mark_as_read' => '既読にする',
        'mark_as_read_hint' => 'このチェックボックスにチェックが入っている場合、許可されていないエントリはまだデータベースに入力されますが、既読としてマークされます',
        'priority_hint' => 'ブラックリストはホワイトリストよりも優先度が高いことに注意してください!',
        'warning' => [
            'not_allowed_msg' => '"%s" はBANされたキーワードです。詳細については管理者にお問い合わせください。',
        ],
        'whitelist' => 'ホワイトリスト',
    ),
);
