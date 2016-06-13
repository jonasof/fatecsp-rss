<?php

if (! file_exists(__DIR__ . '/vendor/autoload.php')) {
    die("É necessário instalar dependências: composer install");
}

require __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/configuracao_default.php';
if (file_exists(__DIR__ . '/configuracao.php'))
    $config = array_merge ($config, require __DIR__ . '/configuracao.php');

if ($config['debug'] === true) {
    error_reporting(E_ALL);
    ini_set("display_errors", true);
}
    
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Item;
use Carbon\Carbon;
use voku\cache\Cache;

(new RSSFatecSP($config))->gerarRSS();

class RSSFatecSP 
{
    public $itens = [];
    private $config = [];
    private $cache;

    public function __construct($config) {
        $this->config = $config;
        $this->cache = new Cache();
    }
    
    public function gerarRSS()
    {
        $cache = $this->verificarCache();
        if (is_null($cache)) 
        {
            $this->carregar();
            $renderizado = $this->renderizar();
            echo $renderizado;
            $this->salvarCache($renderizado);
        } else {
            echo $cache; 
        }
        
    }
    
    private function carregar() 
    {
        phpQuery::newDocumentHtml(file_get_contents($this->config['url_pagina_alimentacao']));
        
        $datas = [];
        $descricoes = [];
        $links = [];
        $titulos = [];
        
        $count = 0;
        foreach (pq("#content .post tr td #texto_main") as $key=>$string) { 
            if($count % 2)
                $descricoes[] = pq($string)->getStrings()[0];
            else
                $datas[] = pq($string)->getStrings()[0];
            $count++;
        }
        
        foreach (pq("#content .post tr td #titulo_main a") as $link) {
            $titulos[] = pq($link)->getStrings()[0];
            $links[] = $this->processarLink(pq($link)->attr("href"));
        }
        
        foreach ($titulos as $key=>$titulo) {
            
            if (!isset($datas[$key]) || $datas[$key] == "Próxima página") 
                break;
            
            $this->itens[] = (object) [
                "titulo" => $titulo,
                "descricao" => $descricoes[$key],
                "data" => $datas[$key],
                "link" => $links[$key]
            ];
        }
    }
    
    private function renderizar() 
    {
        $feed = new Feed();

        $channel = new Channel();
        $channel
            ->title($this->config['feed']['titulo'])
            ->description($this->config['feed']['descricao'])
            ->url($this->config['feed']['url'])
            ->language('pt-BR')
            ->pubDate((new Carbon())->getTimestamp())
            ->lastBuildDate((new Carbon())->getTimestamp())
            ->ttl(600)
            ->appendTo($feed);
        
        foreach ($this->itens as $post) 
        {
            $item = new Item();
            $item
                ->title($post->titulo)
                ->description($post->titulo)
                ->url($post->link)
                ->pubDate(Carbon::createFromFormat("j/m/Y", $post->data)->setTime(0, 0, 0)->getTimestamp())
                ->appendTo($channel);
        }

        return $feed->render();
    }
    
    private function processarLink($link)
    {
        $link = trim($link);
        
        if(filter_var($link, FILTER_VALIDATE_URL))
            return $link;
        
        if(preg_match("/(javascript:abredestaque\(([0-9]+)\))+/", $link)) {
            
            $id = str_replace(["javascript:abredestaque(", ")"], "", $link);
            return "http://fatecsp.br/destaques.php?not=" . $id;
        }
        
        return "http://fatecsp.br/$link";
    }
    
    private function verificarCache() 
    {
        return ($this->config['cache']['ativar'])  ? 
            $this->cache->getItem('html_cache') : null;
    }
    
    private function salvarCache($html) 
    {
        if ($this->config['cache']['ativar'])
            return $this->cache->setItem('html_cache', $html, $this->config['cache']['duracao_minutos'] * 60);
    }
}