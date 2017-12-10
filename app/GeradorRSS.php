<?php

namespace JonasOF\RSSFatecSP;

use Carbon\Carbon;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Item;

class GeradorRSS
{    
    public $config;
    public $language = 'pt-BR';
    public $ttl = 600;
    public $carbon;
    
    public function __construct(Configuracao $config, Carbon $carbon)
    {
        $this->config = $config->config();
        $this->carbon = $carbon;
    }
    
    public function gerar($itens) 
    {
        $feed = new Feed();

        $channel = new Channel();
        $channel
            ->title($this->config['feed']['titulo'])
            ->description($this->config['feed']['descricao'])
            ->url($this->config['feed']['url'])
            ->language($this->language)
            ->pubDate($this->getCurrentTimestamp())
            ->lastBuildDate($this->getCurrentTimestamp())
            ->ttl($this->ttl)
            ->appendTo($feed);

        foreach ($itens as $post) 
        {
            $item = new Item();
            $item
                ->title($post->titulo)
                ->description($post->descricao)
                ->url($post->link)
                ->pubDate(Carbon::createFromFormat("j/m/Y", $post->data)->setTime(0, 0, 0)->getTimestamp())
                ->appendTo($channel);
        }

        return $feed->render();
    }
    
    protected function getCurrentTimestamp() 
    {
        return $this->carbon->getTimestamp();
    }
}
