<?
/*
Classes para acesso a bancos de dados MySQL
by Rafael Lossurdo - rafael@dicaslinux.com.br
abril/2005
OBSERVA��ES:
Nenhuma das informa��es abaixo devem ser alteradas!!!
As altera��es pertinentes, devem ser feitas no
arquivo classesConfig.inc.php
Classe.: MySQL
M�todos: open()
*** conecta com o bco de dados com as vari�veis pr�definidas
em classesConfig.inc.php
close()
*** fecha a conex�o com o bco de dados
query( string SQL )
*** executa uma string SQL
linhas()
*** retorna quantas linhas aquela query resultou
result( linha a ser mostrada, campo a ser mostrado )
*** retorna o conte�do do campo e linha escolhidos
*/
include "classesConfig.inc.php"; // vari�veis do bco. de dados
class MySQL {
var $db;
var $query;
function open() {
$this->db = @mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD);
if(!$this->db) { echo "Erro na conex�o!"; }
if(!@mysql_select_db(DB_DATABASE)) { echo "Erro na sele��o do banco
de dados!"; }
}
function close() {
@mysql_close($this->$db);
}
function query( $sql ) {
$this->query = @mysql_query( $sql, $this->db );
return $this->query;
}
function linhas() {
return @mysql_num_rows( $this->query );
}
function result( $linha, $campo ) {
return @mysql_result( $this->query, $linha, $campo );
}
}
?>