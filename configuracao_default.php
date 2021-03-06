<?php

/* 
 * Caso queira mudar essas configurações crie um arquivo configuracao.php
 * com a mesma estrutura
 */

return [
    "url_pagina_alimentacao" => "http://www.fatecsp.br/?c=todosdestaques",
    "debug" => true,
    "cache" => [
        "ativar" => true,
        "duracao_minutos" => 600,
        "pasta" => __DIR__ . '/cache'
    ],
    "feed" => [
        "titulo" => 'Fatec/SP Destaques',
        "descricao" => 'Notícias Fatec/SP, obtidas de http://www.fatecsp.br/?c=todosdestaques. Ver https://github.com/jonasof/fatecsp-rss',
        "url" => 'http://www.fatecsp.br/'
    ]
];