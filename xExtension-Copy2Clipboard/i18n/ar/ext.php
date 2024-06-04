<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'نسخ إلى الحافظة',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'اختر',
                'desc' => '',
                'options' => array(
                    'default' => 'الافتراضي',
                    'color' => 'اللون',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'تم نسخ رابط واحد',
            'good_more' => 'تم نسخ الروابط #count#',
            'bad' => 'حدث خطأ أثناء النسخ!',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Clipboard`مطلوب على الأقل إصدار FreshRSS `%s`. (تستخدم FreshRSS version `%s`)',
        ),
    ),
);
