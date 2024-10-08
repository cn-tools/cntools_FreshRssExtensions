<?php

return array(
	'FeedTitleBuilder' => array(
		'label4Template' => 'قالب عنوان التغذية',
		'helping' => 'هناك بعض الأقسام الخاصة التي تسمح لك بتخصيص عنوان التغذية الشخصية الخاص بك.',
		'help4origtitle' => 'استخدم هذه التعليمات البرمجية لتحديد المكان الذي يجب أن يظهر فيه العنوان الأصلي لهذه الخلاصة',
		'help4date' => 'مع هذه التعليمة البرمجية يمكنك إضافة تاريخ اليوم بتنسيق المفضلة لديك. استخدم محرك البحث المفضل على الويب لمعرفة المعلمات المتاحة في وظيفة التاريخ في PHP.<br />ابحث عن التفاصيل: <a href="https://www.php.net/manual/en/function.date.php" target="_blank" rel="noopener nofollow">https://www.php.net/manual/en/function.date.php</a><br />مثال: {date}ymd{/date} => 20190401',
		'help4phpparseurl' => 'سيتم تقسيم عنوان URL مع وظيفة PHP <code>parse_url</code> ويمكنك الحصول على القيمة المحددة للنتيجة.<br />ابحث عن التفاصيل: <a href="https://www.php.net/manual/en/function.parse-url.php" target="_blank" rel="noopener nofollow">https://www.php.net/manual/en/function.parse-url.php</a>',
		'help4phpparseurlvalues' => 'وتتوفر الكلمات الخاصة التالية:',
		'help4phpparseurlschema' => 'سوف تحصل على \'HTTP\', \'HTTPS\', \'FTP\' أو بعضها البعض. مثال: \'HTTPS\'',
		'help4phpparseurlhost' => 'في المثال ستحصل على \'github.com\'.',
		'help4phpparseurlport' => 'سوف تحصل على المنفذ الموجود في عنوان URL المحدد.',
		'help4phpparseurluser' => 'سوف تحصل على المستخدم الموجود في عنوان URL المحدد.',
		'help4phpparseurlpass' => 'ستحصل على كلمة المرور الموجودة في عنوان URL المحدد.',
		'help4phpparseurlpath' => 'في المثال ستحصل على \'/FreshRSS/FreshRSS\'.',
		'help4phpparseurlquery' => 'سوف تحصل على النص بين \'؟\' و \'#\'.',
		'help4phpparseurlfragment' => 'سوف تحصل على النص بعد \'#\'.',
		'help4phpparseurlhostspecial' => 'ربما تعطيك الكلمة الرئيسية "المضيف" الكثير جدًا، بحيث يمكنك استخدام الكلمات الخاصة التالية. هذه هي القيم من قبل \'.\' (نقطة) مفصولة <unk> من \'المضيف\'.',
		'help4phpparseurlhostsub' => 'ستحصل على النص الكامل قبل النقطة قبل الأخيرة.',
		'help4phpparseurlhostname' => 'ستحصل على النص بين النقطة قبل الأخيرة والنقطة الأخيرة.',
		'help4phpparseurlhosttld' => 'ستحصل على النص بعد النقطة الأخيرة.',
		'example' => 'مثال',
		'example_template_code' => 'رمز القالب:',
		'example_url' => 'الرابط:',
		'example_title' => 'عنوان الخلاصة المولدة:',
		'info' => 'معلومات:',
		'infodesc' => 'إذا كان القالب فارغاً، فستحصل على العنوان الأصلي للخلاصة المضافة!',
	),
);