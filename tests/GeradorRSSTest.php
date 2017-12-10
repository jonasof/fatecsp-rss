<?php

use Mockery\Mock;
use JonasOF\RSSFatecSP\GeradorRSS;

class GeradorRSSTest extends PHPUnit_Framework_TestCase
{
    public $items;
    public $gerador;
    public $resultado_esperado;
    
    protected function setUp() {
        $di = (new DI\ContainerBuilder());
        $di->addDefinitions([
            \Carbon\Carbon::class => $this->getCarbonMock()
        ]);
        $container = $di->build();
        
        $this->items = json_decode(file_get_contents(__DIR__ . "/data/itens.json"));
        $this->resultado_esperado = file_get_contents(__DIR__ . '/data/rss.html');
        
        $this->gerador = $container->get(GeradorRSS::class);
    }
    
    public function test()
    {
        $rss = $this->gerador->gerar($this->items);
        $this->assertEquals($this->resultado_esperado, $rss);
    }
    
    protected function getCarbonMock()
    {
        return Mockery::mock(Carbon\Carbon::class)
                ->makePartial()
                ->shouldReceive("getTimestamp")
                ->andReturn(1512900000)
                ->getMock();
    }
}