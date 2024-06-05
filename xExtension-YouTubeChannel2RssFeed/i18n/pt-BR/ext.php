<?php

return array(
    'YouTubeChannel2RssFeed' => array(
        '3rd_party' => array(
            'help' => array(
                'lnk_txt' => 'Documentação',
                'text' => 'Detalhes sobre a configuração e funcionalidade podem ser encontrados aqui:',
            ),
            'hint' => 'Com a ajuda da URL a ser especificada, Os Shorts do YouTube podem ser identificados por uma mão e as handels do YouTube podem ser convertidas em feeds do outro',
            'instance' => 'Instância de:',
            'url' => 'URL:',
        ),
        'install' => array(
            'bad_freshrss' => '`YouTubeChannel2RssFeed`necessário pelo menos a versão FreshRSS `%s`. (Você usa a versão FreshRSS `%s`)',
        ),
        'shorts' => array(
            'duration' => array(
                'label' => 'Duração em segundos',
                'hint' => 'Se você definir uma duração maior que 0, todos os vídeos menores serão identificados como curtos, mesmo que o YouTube diga que não é curto.',
            ),
            'label' =>  'Shorts do YouTube',
            'hint' => 'Esta configuração é tomada somente se uma URL de instância é armazenada. Se nenhuma for depositada, os curtos são permitidos.',
            'options' => array(
                'default' => array(
                    'text' => 'Padrão',
                    'title' => 'Nenhuma verificação de Shorts do YouTube e a entrada do feed é adicionada ao banco de dados como normal.',
                ),
                'block' => array(
                    'text' => 'Bloquear',
                    'title' => 'Se o novo feed for reconhecido como um Youtube Short, ele não será adicionado no banco de dados.',
                ),
                'mark_as_read' => array(
                    'text' => 'Marcar Tudo como Lido',
                    'title' => 'Se o novo feed for reconhecido como um Youtube Short, ele será marcado como lido.',
                ),
            ),
        ),
    ),
);
