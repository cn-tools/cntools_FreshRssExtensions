<?php

return array(
	'Copy2Clipboard' => array(
        'button' => array(
            'title' => 'Copiar para área de transferência',
        ),
        'config' => array(
            'findcolor' => array(
                'label' => 'Selecionar',
                'desc' => '',
                'options' => array(
                    'default' => 'Padrão',
                    'color' => 'Cor',
                ),
            ),
        ),
        'messages' => array(
            'good_one' => 'Um link foi copiado',
            'good_more' => '#count# links foram copiados',
            'bad' => 'Ocorreu um erro durante a cópia!',
        ),
        'install' => array(
            'bad_freshrss' => '`Copy2Clipboard`necessário pelo menos FreshRSS versão `%s`. (Você usa a versão FreshRSS `%s`)',
        ),
    ),
);
