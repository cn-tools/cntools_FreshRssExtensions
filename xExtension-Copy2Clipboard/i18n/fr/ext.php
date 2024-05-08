<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'Copier dans le presse-papiers',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Sélection',
                'desc' => '',
                'options' => array(
                    'default' => 'Standard',
                    'color' => 'Couleur',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'Un lien a été copié',
            'good_more' => '#count# liens ont été copiés',
            'bad' => 'Une erreur s\'est produite lors de la copie !',
        ),
        'install' => array(
            'bad_freshrss' => 'Copy2Clipboard" nécessite au moins la version FreshRSS `%s`. (Vous utilisez la version FreshRSS `%s`)',
        ),
    ),
);
