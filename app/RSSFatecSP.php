<?php

namespace JonasOF\RSSFatecSP;

class RSSFatecSP 
{
    public $analisador;
    public $cache;
    public $gerador;
    public $config;
    
    public function __construct(
        AnalisadorHtml $analisador, 
        Cache $cache, 
        GeradorRSS $gerador, 
        Configuracao $config
    ) {
        $this->config = $config->config();
        $this->analisador = $analisador;
        $this->cache = $cache;
        $this->gerador = $gerador;
    }
    
    public function gerarRSS() 
    {
        $cache = $this->cache->verificarCache();
        if (is_null($cache)) 
        {
            $list = $this->analisador->analisar(file_get_contents($this->config['url_pagina_alimentacao']));
            $renderizado = $this->gerador->gerar($list);
            return $renderizado;
            $this->cache->salvarCache($renderizado);
        } else {
            return $cache; 
        }
    }
}