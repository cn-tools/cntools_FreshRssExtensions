<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'Kopiera till urklipp',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Välj',
                'desc' => '',
                'options' => array(
                    'default' => 'Standard',
                    'color' => 'Färg',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'En länk har kopierats',
            'good_more' => '#count# länkar har kopierats',
            'bad' => 'Ett fel uppstod under kopieringen!',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Urklipp `krävde åtminstone FreshRSS version `%s`. (Du använder FreshRSS version `%s`)',
        ),
    ),
);
