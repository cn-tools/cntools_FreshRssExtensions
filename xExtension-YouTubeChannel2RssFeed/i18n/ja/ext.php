<?php

return array(
    'YouTubeChannel2RssFeed' => array(
        '3rd_party' => array(
            'help' => array(
                'lnk_txt' => 'ドキュメント',
                'text' => '設定と機能についての詳細はこちらをご覧ください:',
            ),
            'hint' => '指定するURLの助けを借りて YouTube Shortsは片手で識別でき、YouTubeのハンドルはもう一方のフィードに変換できます。',
            'instance' => '次のインスタンス:',
            'url' => 'URL',
        ),
        'install' => array(
            'bad_freshrss' => '`YouTubeチャンネル2RssFeed`required at least FreshRSS version `%s`. (You use FreshRSS version `%s`)',
        ),
        'shorts' => array(
            'duration' => array(
                'label' => 'Duration in seconds',
                'hint' => '長さを 0 より大きく設定すると、短い動画はすべて短くないとYouTubeが言っても短いと判断されます。',
            ),
            'label' =>  'YouTubeショート',
            'hint' => 'この設定はインスタンスURLが格納されている場合にのみ行われます。デポジットされていない場合は短絡を許可します。',
            'options' => array(
                'default' => array(
                    'text' => 'デフォルト',
                    'title' => 'YouTubeショーツのチェックはなく、フィードエントリは通常通りデータベースに追加されます。',
                ),
                'block' => array(
                    'text' => 'ブロック',
                    'title' => '新しいフィードエントリが Youtube Short として認識されている場合は、データベースに追加されません。',
                ),
                'mark_as_read' => array(
                    'text' => '既読にする',
                    'title' => '新しいフィードエントリが Youtube Short として認識されると、既読としてマークされます。',
                ),
            ),
        ),
    ),
);
