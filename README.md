# fatecsp-rss
Gerador de RSS para notícias em destaque da Fatec-SP

Este script foi criado para disponibilizar o conteúdo da página http://www.fatecsp.br/?c=todosdestaques em formato RSS. 
É possível que ele deixe de funcionar caso a estrutura da página seja alterada.

Disponibilizo um instância pública pronta para uso em: http://fatecsp-rss.jonasof.com.br/

## Como instalar

 - Clone ou baixe o repositório para o seu servidor http
 - Rode o comando "composer install" dentro da pasta
 - Pronto! Mas considere também os seguintes itens:
   - Caso queria aterar as configurações disponíveis em configuracoes_default.php, 
     crie um arquivo configuracoes.php na mesma pasta e com a mesma estrutura
   - É melhor, em termos de segurança, apontar a raiz do servidor http para a pasta "public"

## Requerimentos

 - PHP >= 5.4
 - Servidor http
