<?php

namespace JonasOF\RSSFatecSP;

use phpQuery;

class AnalisadorHtml
{
    public $config;
    
    public function __construct(Configuracao $config)
    {
        $this->config = $config->config();
    }
    
    public function analisar($html) 
    {
        phpQuery::newDocumentHtml($html);
        
        foreach (pq("article > article") as $string) {
            $itens[] = (object) [
                "titulo" => pq($string)->find("a")->text(),
                "descricao" => pq($string)->find("p")->text(),
                "link" => $this->processarLink(pq($string)->find("a")->attr("href")),
                "data" => pq($string)->find(".data")->text()
            ];
        }
        
        return $itens;
    }
    
    private function processarLink($link)
    {
        $link = trim($link);
        
        if(filter_var($link, FILTER_VALIDATE_URL))
            return $link;
        
        if(preg_match("/(javascript:abredestaque\(([0-9]+)\))+/", $link)) {
            
            $id = str_replace(["javascript:abredestaque(", ")", ";"], "", $link);
            return "http://fatecsp.br/destaques.php?not=" . $id;
        }
        
        return "http://fatecsp.br/$link";
    }
}
