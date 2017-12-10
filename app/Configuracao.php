<?php

namespace JonasOF\RSSFatecSP;

class Configuracao
{
    public $configs;
    
    public function config() 
    {
        if ($this->configs === null) {
        
            $config = require __DIR__ . '/../configuracao_default.php';
            if (file_exists(__DIR__ . '/../configuracao.php'))
                $config = array_merge ($config, require __DIR__ . '/../configuracao.php');

            $this->configs = $config;
            
            return $config;
        } else {
            return $this->configs;
        }
    }
}
