<?php

namespace JonasOF\RSSFatecSP;

use Desarrolla2\Cache\Cache as DesarrollaCache;
use Desarrolla2\Cache\Adapter\File;

class Cache
{
    public $cache;
    public $config;
    
    public function __construct(Configuracao $config)
    {
        $this->config = $config->config();
        $this->cache = new DesarrollaCache(new File($this->config['cache']['pasta']));
    }
    
    public function verificarCache() 
    {
        return ($this->config['cache']['ativar'])  ? 
            $this->cache->get('html_cache') : null;
    }
    
    public function salvarCache($html) 
    {
        if ($this->config['cache']['ativar'])
            return $this->cache->set('html_cache', $html, $this->config['cache']['duracao_minutos'] * 60);
    }

}
