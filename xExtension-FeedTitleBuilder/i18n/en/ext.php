<?php

return array(
	'FeedTitleBuilder' => array(
		'label4Template' => 'Feed title template',
		'helping' => 'There are some special sections that allow you to customize your personal feed title.',
		'help4origtitle' => 'Use this code to define where the original title of this feed should be shown',
		'help4date' => 'With this code you can add the day date in your favorite format. Use your favorite web search engine to find out which parameters are available in the date function in PHP.<br />Look for details: <a href="https://www.php.net/manual/en/function.date.php" target="_blank" rel="noopener nofollow">https://www.php.net/manual/en/function.date.php</a><br />Example: {date}ymd{/date} => 20190401',
		'help4phpparseurl' => 'The URL will be splitted with the PHP function <code>parse_url</code> and you can get the specific value of the result.<br />Look for details: <a href="https://www.php.net/manual/en/function.parse-url.php" target="_blank" rel="noopener nofollow">https://www.php.net/manual/en/function.parse-url.php</a>',
		'help4phpparseurlvalues' => 'The following special words are available:',
		'help4phpparseurlschema' => 'You will get \'HTTP\', \'HTTPS\', \'FTP\' or each other. Ex. \'HTTPS\'',
		'help4phpparseurlhost' => 'In the example you will get \'github.com\'.',
		'help4phpparseurlport' => 'You will get the port which is in the URL defined.',
		'help4phpparseurluser' => 'You will get the user which is in the URL defined.',
		'help4phpparseurlpass' => 'You will get the password which is in the URL defined.',
		'help4phpparseurlpath' => 'In the example you will get \'/FreshRSS/FreshRSS\'.',
		'help4phpparseurlquery' => 'You will get the text between \'?\' and \'#\'.',
		'help4phpparseurlfragment' => 'You will get the text after \'#\'.',
		'help4phpparseurlhostspecial' => 'Maybe the key word \'host\' gives you too much, so you can use the following special words. These are the by \'.\' (dot) separated values ​​of \'host\'.',
		'help4phpparseurlhostsub' => 'You will get the full text before the penultimate dot.',
		'help4phpparseurlhostname' => 'You will get the text between the penultimate and last dot.',
		'help4phpparseurlhosttld' => 'You will get the text after the last dot.',
		'example' => 'Example',
		'example_template_code' => 'Template code:',
		'example_url' => 'URL:',
		'example_title' => 'Generated feed title:',
		'info' => 'Information:',
		'infodesc' => 'If the template is empty, you will get the original title of the added feed!',
	),
);