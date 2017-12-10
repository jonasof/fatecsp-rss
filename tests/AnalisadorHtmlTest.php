<?php

use JonasOF\RSSFatecSP\AnalisadorHtml;

class AnalisadorHtmlTest extends PHPUnit_Framework_TestCase
{
    public $pagina_html;
    public $analisador;
    public $resultado_esperado;
    
    protected function setUp() 
    {
        $container = (new DI\ContainerBuilder())->build();
        
        $this->pagina_html = file_get_contents(__DIR__ . "/data/pagina.html");
        $this->resultado_esperado = file_get_contents(__DIR__ . '/data/itens.json');
        
        $this->analisador = $container->get(AnalisadorHtml::class);
    }
    
    public function test()
    {
        $itens = $this->analisador->analisar($this->pagina_html);
        $this->assertEquals($this->resultado_esperado, json_encode($itens));
    }
}