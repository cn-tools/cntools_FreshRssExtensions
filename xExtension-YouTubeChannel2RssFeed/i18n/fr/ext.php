<?php

return array(
    'YouTubeChannel2RssFeed' => array(
        '3rd_party' => array(
            'help' => array(
                'lnk_txt' => 'Documentation',
                'text' => 'Des détails sur la configuration et les fonctionnalités peuvent être trouvés ici :',
            ),
            'hint' => 'Avec l\'aide de l\'URL à spécifier, Les Shorts YouTube peuvent être identifiés d\'une part et les manuels YouTube peuvent être convertis en flux d\'autre part',
            'instance' => 'Instance de :',
            'url' => 'URL',
        ),
        'install' => array(
            'bad_freshrss' => 'YouTubeChannel2RssFeed" nécessite au moins la version FreshRSS `%s`. (Vous utilisez la version FreshRSS `%s`)',
        ),
        'shorts' => array(
            'duration' => array(
                'label' => 'Durée en secondes',
                'hint' => 'Si vous définissez une durée supérieure à 0, toutes les vidéos plus courtes seront identifiées comme courtes, même si YouTube dit qu\'elles ne sont pas courtes.',
            ),
            'label' =>  'Shorts YouTube',
            'hint' => 'Ce paramètre n\'est pris que si une URL d\'instance est stockée. Si aucun n\'est déposé, les shorts sont autorisés.',
            'options' => array(
                'default' => array(
                    'text' => 'Par défaut',
                    'title' => 'Aucune vérification des Shorts YouTube et l\'entrée de flux est ajoutée à la base de données comme d\'habitude.',
                ),
                'block' => array(
                    'text' => 'Bloquer',
                    'title' => 'Si la nouvelle entrée de flux est reconnue comme un raccourci Youtube, elle ne sera pas ajoutée à la base de données.',
                ),
                'mark_as_read' => array(
                    'text' => 'Marquer comme lu',
                    'title' => 'Si le nouveau flux est reconnu comme un raccourci Youtube, il sera marqué comme lu.',
                ),
            ),
        ),
    ),
);
