<?php

return array(
	'FeedTitleBuilder' => array(
		'label4Template' => 'Feed titel sjabloon',
		'helping' => 'Er zijn enkele speciale secties waarmee u uw persoonlijke feed titel kunt aanpassen.',
		'help4origtitle' => 'Gebruik deze code om te definiëren waar de oorspronkelijke titel van deze feed moet worden weergegeven',
		'help4date' => 'Met deze code kunt u de dag datum toevoegen in uw favoriete formaat. Gebruik uw favoriete webzoekmachine om uit te zoeken welke parameters beschikbaar zijn in de datumfunctie in PHP.<br />Zoek naar details: <a href="https://www.php.net/manual/en/function.date.php" target="_blank" rel="noopener nofollow">https://www.php.net/manual/en/function.date.php</a><br />Voorbeeld: {date}ymd{/date} => 20190401',
		'help4phpparseurl' => 'De URL zal worden gesplitst met de PHP functie <code>parse_url</code> en u kunt de specifieke waarde van het resultaat krijgen.<br />Zoek naar details: <a href="https://www.php.net/manual/en/function.parse-url.php" target="_blank" rel="noopener nofollow">https://www.php.net/manual/en/function.parse-url.php</a>',
		'help4phpparseurlvalues' => 'De volgende speciale woorden zijn beschikbaar:',
		'help4phpparseurlschema' => 'U krijgt \'HTTP\', \'HTTPS\', \'FTP\' of elkaar. Ex. \'HTTPS\'',
		'help4phpparseurlhost' => 'In het voorbeeld krijg je \'github.com\'.',
		'help4phpparseurlport' => 'U krijgt de poort die in de URL is gedefinieerd.',
		'help4phpparseurluser' => 'U krijgt de gebruiker die in de URL is gedefinieerd.',
		'help4phpparseurlpass' => 'U krijgt het wachtwoord dat in de opgegeven URL staat.',
		'help4phpparseurlpath' => 'In het voorbeeld krijgt u \'/FreshRSS/FreshRSS\'.',
		'help4phpparseurlquery' => 'U krijgt de tekst tussen \'?\' en \'#\'.',
		'help4phpparseurlfragment' => 'U krijgt de tekst na \'#\'.',
		'help4phpparseurlhostspecial' => 'Misschien geeft het sleutelwoord \'host\' je te veel, dus je kunt de volgende speciale woorden gebruiken. Dit zijn de door de \'.\' gescheiden waarden (dot) van \'host\'.',
		'help4phpparseurlhostsub' => 'Je krijgt de volledige tekst voor de voorlaatste punt.',
		'help4phpparseurlhostname' => 'Je krijgt de tekst tussen de voorlaatste en de laatste punt.',
		'help4phpparseurlhosttld' => 'Je krijgt de tekst na de laatste punt.',
		'example' => 'Voorbeeld',
		'example_template_code' => 'Template code:',
		'example_url' => 'URL:',
		'example_title' => 'Gegenereerde feed titel:',
		'info' => 'Informatie:',
		'infodesc' => 'Als de sjabloon leeg is, krijg je de originele titel van de toegevoegde feed!',
	),
);