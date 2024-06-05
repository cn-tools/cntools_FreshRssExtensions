<?php

return array(
	'FeedTitleBuilder' => array(
		'label4Template' => 'Szablon tytułu kanału',
		'helping' => 'Istnieją pewne specjalne sekcje, które pozwalają dostosować swój osobisty tytuł kanału.',
		'help4origtitle' => 'Użyj tego kodu, aby określić, gdzie powinien być wyświetlany oryginalny tytuł tego kanału',
		'help4date' => 'Ten kod umożliwia dodanie dzisiejszej daty w preferowanym formacie. Skorzystaj ze swojej ulubionej wyszukiwarki internetowej, aby dowiedzieć się, jakie parametry są dostępne w funkcji daty w PHP.<br />Szczegóły znajdziesz tutaj: <a href="https://www.php.net/manual/function.date.php " target="_blank" rel="noopener nofollow">https://www.php.net/manual/function.date.php</a><br />Przykład: {date}ymd{/date} = > 20190401',
		'help4phpparseurl' => 'Adres URL zostanie podzielony na funkcję PHP <code>parse_url</code> i możesz uzyskać określoną wartość wyniku.<br />Szukaj szczegółów: <a href="https://www.php.net/manual/en/function.parse-url.php" target="_blank" rel="noopener nofollow">https://www.php.net/manual/en/function.parse-url.php</a>',
		'help4phpparseurlvalues' => 'Dostępne są następujące słowa specjalne:',
		'help4phpparseurlschema' => 'Otrzymasz \'HTTP\', \'HTTPS\', \'FTP\' lub nawzajem. Np. \'HTTPS\'',
		'help4phpparseurlhost' => 'Na przykładzie otrzymasz "github.com”.',
		'help4phpparseurlport' => 'Otrzymasz port, który znajduje się w zdefiniowanym adresie URL.',
		'help4phpparseurluser' => 'Dostaniesz użytkownika, który jest w zdefiniowanym adresie URL.',
		'help4phpparseurlpass' => 'Otrzymasz hasło, które znajduje się w zdefiniowanym URL.',
		'help4phpparseurlpath' => 'W przykładzie otrzymasz \'/FreshRSS/FreshRSS\'.',
		'help4phpparseurlquery' => 'Otrzymasz tekst pomiędzy \'?\' a \'#\'.',
		'help4phpparseurlfragment' => 'Otrzymasz tekst po \'#\'.',
		'help4phpparseurlhostspecial' => 'Może słowo kluczowe "host" daje zbyt wiele, więc możesz użyć następujących specjalnych słów. Są to wartości rozdzielone przez \'.\' (kropka) od \'host\'.',
		'help4phpparseurlhostsub' => 'Pełny tekst otrzymasz przed przedostatnią kropką.',
		'help4phpparseurlhostname' => 'Otrzymasz tekst pomiędzy przedostatnią a ostatnią kropką.',
		'help4phpparseurlhosttld' => 'Otrzymasz tekst po ostatniej kropce.',
		'example' => 'Przykład',
		'example_template_code' => 'Kod szablonu:',
		'example_url' => 'URL:',
		'example_title' => 'Wygenerowany tytuł kanału:',
		'info' => 'Informacje:',
		'infodesc' => 'Jeśli szablon jest pusty, otrzymasz oryginalny tytuł dodanego kanału!',
	),
);