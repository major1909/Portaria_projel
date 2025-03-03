<?
//DATA DA EMISS�O
$b = date('d'); $c = date('n'); $d = date('Y'); 
if(strlen($b) == 1){$b = "0".$b;};
if(strlen($c) == 1){$c = "0".$c;}; 
$data_emissao_hoje = $b."/".$c."/".$d;

//HORA DA EMISSAO
$hora_atual = date('H:i');

//CONFIGURA��ES DO BD MYSQL                               
include "config_pcp_imprimir.php";  
include "config_pcp.php";
include "valida_sessao.php";


//ENDERE�O DA BIBLIOTECA FPDF                             
$end_fpdf    =  "";     
//ENDERE�O ONDE SER� GERADO O PDF                         
$end_final   =  "Imprimir PCP Previs�o Di�ria.pdf"; 
//TIPO DO PDF GERADO                                      
//F-> SALVA NO ENDERE�O ESPECIFICADO NA VAR END_FINAL     
$tipo_pdf    =  "F";                          
//T�TULO DO RELAT�RIO                                     
$titulo      =  "Imprimir PCP Previs�o Di�ria";


//VARIAVEIS
//NUMERO DE RESULTADOS POR P�GINA                         
$dados_por_pagina_pdf = 60; 
//echo "<br>";  echo "dados_por_pagina_pdf_padrao = ".$dados_por_pagina_pdf; echo "<br>";

//FOLHA
$pag_largura = 750;
$pag_altura = 500;

//FONTES
$fonte_cabecalho = 10;
$fonte_rodape = 8;
$fonte_titulo = 10;
$fonte_texto = 8;
$fonte_erro = 20;

//MARGEM
$margem_vertical = 10;
$margem_horizontal = 10;

//DIAS DO FOR DA DATA PROGR.
$dias_inicial = 0;	
$dias_previsao = $dias_previsao - 1; 

//PAGINA DO PDF
$linha_atual_pdf = 0; 
$linha_inicio_pdf = 0;


/************** N�O MEXER DAQUI PRA BAIXO ***************/


// FILTROS DO BANCO
if ( $f_data_emissao <> "") {$f_data_emissao_db = "and data_emissao='$f_data_emissao'";} else {$f_data_emissao_db = "";}
if ( $f_num_os <> "") {$f_num_os_db = "and num_os='$f_num_os'";} else {$f_num_os_db = "";}
if ( $f_num_proposta <> "") {$f_num_proposta_db = "and num_proposta='$f_num_proposta'";} else {$f_num_proposta_db = "";}
if ( $f_nome_cliente <> "" ) {$f_nome_cliente_db = "and nome_cliente='$f_nome_cliente'";} else {$f_nome_cliente_db = "";}
if ( $f_oc_obra <> "" ) {$f_oc_obra_db = "and oc_obra='$f_oc_obra'";} else {$f_oc_obra_db = "";}
if ( $f_mercado <> "" ) {$f_mercado_db = "and mercado='$f_mercado'";} else {$f_mercado_db = "";}
if ( $f_estado <> "" ) {$f_estado_db = "and estado='$f_estado'";} else {$f_estado_db = "";}
if ( $f_data_entrega <> "" ) {$f_data_entrega_db = "and data_entrega='$f_data_entrega'";} else {$f_data_entrega_db = "";}
if ( $f_local_venda <> "" ) {$f_local_venda_db = "and local_venda='$f_local_venda'";} else {$f_local_venda_db = "";}
if ( $f_fornecimento_motor <> "" ) {$f_fornecimento_motor_db = "and fornecimento_motor='$f_fornecimento_motor'";} else {$f_fornecimento_motor_db = "";}
if ( $f_descr_vent <> "" ) {$f_descr_vent_db = "and descr_vent='$f_descr_vent'";} else {$f_descr_vent_db = "";}
if ( $f_modelo <> "" ) {$f_modelo_db = "and modelo='$f_modelo'";} else {$f_modelo_db = "";}
if ( $f_tamanho <> "" ) {$f_tamanho_db = "and tamanho='$f_tamanho'";} else {$f_tamanho_db = "";}
if ( $f_arranjo <> "" ) {$f_arranjo_db = "and arranjo='$f_arranjo'";} else {$f_arranjo_db = "";}
if ( $f_classe <> "" ) {$f_classe_db = "and classe='$f_classe'";} else {$f_classe_db = "";}
if ( $f_rotacao <> "" ) {$f_rotacao_db = "and rotacao='$f_rotacao'";} else {$f_rotacao_db = "";}
if ( $f_gab <> "" ) {$f_gab_db = "and gab='$f_gab'";} else {$f_gab_db = "";}
if ( $f_pintura <> "" ) {$f_pintura_db = "and pintura='$f_pintura'";} else {$f_pintura_db = "";}
if ( $f_construcao <> "" ) {$f_construcao_db = "and construcao='$f_construcao'";} else {$f_construcao_db = "";}
if ( $f_qt <> "" ) {$f_qt_db = "and qt='$f_qt'";} else {$f_qt_db = "";}
if ( $f_valor_uni <> "" ) {$f_valor_uni_db = "and valor_uni='$f_valor_uni'";} else {$f_valor_uni_db = "";}
if ( $f_valor_total <> "" ) {$f_valor_total_db = "and valor_total='$f_valor_total'";} else {$f_valor_total_db = "";}
if ( $f_obs <> "" ) {$f_obs_db = "and obs='$f_obs'";} else {$f_obs_db = "";}
if ( $f_data_motor_recebido <> "" ) {$f_data_motor_recebido_db = "and data_motor_recebido='$f_data_motor_recebido'";} else {$f_data_motor_recebido_db = "";}
if ( $f_reprogramacao <> "" ) {$f_reprogramacao_db = "and reprogramacao='$f_reprogramacao'";} else {$f_reprogramacao_db = "";}
if ( $f_baixa == "TODOS") { $f_baixa_db = "";  } else { $f_baixa_db = "AND baixa='$f_baixa'";  }
if ( $f_data_baixa <> "" ) {$f_data_baixa_db = "and data_baixa='$f_data_baixa'";} else {$f_data_baixa_db = "";}

// ----------------------------------------------------------------------------------------------------------



// PREPARA PARA GERAR O PDF
	define('FPDF_FONTPATH','./font/');
	require('fpdf.php');        
	$pdf = new FPDF();
	
// CRIAR P�GINA NO PDF   
	$pdf->Open();                    
	$pdf->AddPage('L');
	
// MONTA O CABE�ALHO              
	$pdf->SetFont("Arial", "B", $fonte_cabecalho);
	$pdf->Ln(2);
//	$pdf->Cell(40, -15, "Data Impress�o - $data_emissao_hoje" , 0, 0, 'R');    
	$pdf->Cell(180, -15, "Impress�o PCP Previs�o por Dia" , 0, 0, 'C');
//  $pdf->Cell(25, -15, "P�g. 1 de $paginas", 0, 0, 'R');
                    	

// FOR SOMAR DIAS	------------------------------------------------
	for ( $dias=$dias_inicial; $dias<=$dias_previsao; $dias++ )	{
  

// DESCOBRI DIA, MES E ANO DA DATA PROGR. DIARIA  ------------------------------------------------
//echo "<br>"; echo $f_data_prog_diaria;
$dia = substr($f_data_previsao, -2); 
$mes = substr($f_data_previsao, -5,2); 
$ano = substr($f_data_previsao, -10,4);


// FOR DESCOBRI DIA DA SEMANA	------------------------------------------------
	for($semana = 0; $semana<$dias; $semana++) {
    
  	if($mes == "01" || $mes == "03" || $mes == "05" || $mes == "07" || $mes == "08" || $mes == "10" || $mes == "12"){
  	if($mes == 12 && $dia == 31){  $mes = 01;   $ano++;   $dia = 00;  }
  	if($dia == 31 && $mes != 12){  $mes++;  $dia = 00;   }  }//fecha if geral
  
  	if($mes == "04" || $mes == "06" || $mes == "09" || $mes == "11"){
  	if($dia == 30){  $dia = 00;   $mes++;   }   }//fecha if geral
  
  	if($mes == "02"){
  	if($ano % 4 == 0 && $ano % 100 != 0){ //ano bissexto
  	if($dia == 29){  $dia = 00;   $mes++;    }    }
  else {
  	if($dia == 28){  $dia = 00;  $mes++;   }   }  }//FECHA IF DO M�S 2
  
  $dia++;   
  
}//FECHAR FOR DESCOBRI DIA DA SEMANA (semana)	------------------------------------------------
  
  
// Confirma Sa�da de 2 d�gitos   ------------------------------------------------
	if(strlen($dia) == 1){$dia = "0".$dia;};
	if(strlen($mes) == 1){$mes = "0".$mes;};
  
// Monta Sa�da ----------------------------------------------------------------
	$data_previsao = $dia."/".$mes."/".$ano;   
	$data_previsao_db = $ano."/".$mes."/".$dia; 

//	echo "<br>";  echo "data_programada_db = ". $data_programada_db; 

	$mes_x = $mes;
  
	if ($mes == 1) { $mes_x = 13; $ano = $ano-1;}
	if ($mes == 2) { $mes_x = 14; $ano = $ano-1;}
	
	$val4 = (($mes_x+1)*3)/5;
		$val4_int=number_format($val4,0,'.','');
		if ($val4 < $val4_int) {$val4 = $val4_int - 1;} else {$val4 = $val4_int;}
	$val5 = $ano/4;
   		$val5_int=number_format($val5,0,'.','');
		if ($val5 < $val5_int) {$val5 = $val5_int - 1;} else {$val5 = $val5_int;}
	$val6 = $ano/100;
   		$val6_int=number_format($val6,0,'.','');
		if ($val6 < $val6_int) {$val6 = $val6_int - 1;} else {$val6 = $val6_int;}
	$val7 = $ano/400;
   		$val7_int=number_format($val7,0,'.','');
		if ($val7 < $val7_int) {$val7 = $val7_int - 1;} else {$val7 = $val7_int;}
	$val8 = $dia+($mes_x*2)+$val4+$ano+$val5-$val6+$val7+2;
	$val9 = $val8/7;
		$val9_int=number_format($val9,0,'.','');
		if ($val9 < $val9_int) {$val9 = $val9_int - 1;} else {$val9 = $val9_int;}
	$val0 = $val8-($val9*7); 
		
//	 	echo "<br>";   echo "val0 = ".$val0;
   
		if($val0 == "0")  {$dia_semana = "S�bado";}
        if($val0 == "1")  {$dia_semana = "Domingo";}
        if($val0 == "2")  {$dia_semana = "Segunda-feira";}
		if($val0 == "3")  {$dia_semana = "Ter�a-feira";}
        if($val0 == "4")  {$dia_semana = "Quarta-feira";}
        if($val0 == "5")  {$dia_semana = "Quinta-feira";}
        if($val0 == "6")  {$dia_semana = "Sexta-feira";}
        
//	   	echo "<br><br>";  echo "dia_semana = ".$dia_semana; 

//-----------------------------------------------------------------------------------------


//QUANDO N�O FOR SABADO OU DOMINGO 
	if ( $dia_semana <> "S�bado" AND $dia_semana <> "Domingo"  ) { 


//MONTA O SUB-TITULO 
   $pdf->Ln(1); 
   $pdf->SetFont("Arial", "B", $fonte_cabecalho);
   $pdf->Cell(45, 8, "Data Previs�o Montagem $data_previsao - $dia_semana", 0, 1, 'L');   
   
   $pdf->Ln(2); 
   $pdf->SetFont("Arial", "B", $fonte_texto);
   $pdf->Cell(15, 6, "ENTREGA", 1, 0, 'C'); 
   $pdf->Cell(15, 6, "O.S", 1, 0, 'C');          
   $pdf->Cell(50, 6, "NOME CLIENTE", 1, 0, 'C'); 
    
   $pdf->Cell(22, 6, "DESC", 1, 0, 'C');
   $pdf->Cell(8, 6, "QT", 1, 0, 'C');
   $pdf->Cell(13, 6, "MOD", 1, 0, 'C'); 
   $pdf->Cell(10, 6, "TAM", 1, 0, 'C');
   $pdf->Cell(8, 6, "AR", 1, 0, 'C');
   $pdf->Cell(9, 6, "RT", 1, 0, 'C');
   $pdf->Cell(9, 6, "GB", 1, 0, 'C');

   $pdf->Cell(10, 6, "CH", 1, 0, 'C');
   $pdf->Cell(15, 6, "PINT", 1, 0, 'C');
   
   $pdf->Cell(8, 6, "CO", 1, 0, 'C');
   $pdf->Cell(8, 6, "CI", 1, 0, 'C');
   $pdf->Cell(8, 6, "CII", 1, 0, 'C');
   $pdf->Cell(8, 6, "RL", 1, 0, 'C');
   $pdf->Cell(8, 6, "RS", 1, 0, 'C');
   $pdf->Cell(8, 6, "BAL", 1, 0, 'C');
   $pdf->Cell(8, 6, "PIN", 1, 0, 'C');
   $pdf->Cell(8, 6, "GAB", 1, 0, 'C');
   $pdf->Cell(8, 6, "FUN", 1, 0, 'C');
   
   $pdf->Cell(9, 6, "1", 1, 0, 'C');
   $pdf->Cell(9, 6, "2", 1, 0, 'C');
   $pdf->Cell(9, 6, "3", 1, 1, 'C');


//--------------------------------------------------------------------------------------

//CONECTA COM O MYSQL
	$conn = mysql_connect($servidor, $usuario, $senha);
	$db = mysql_select_db($bd, $conn);
	$query = "select * from pcp where data_previsao='$data_previsao_db' $f_data_emissao_db $f_num_os_db $f_num_proposta_db $f_nome_cliente_db $f_oc_obra_db $f_mercado_db $f_estado_db $f_data_entrega_db $f_local_venda_db $f_fornecimento_motor_db $f_descr_vent_db $f_modelo_db $f_tamanho_db $f_arranjo_db $f_classe_db $f_rotacao_db $f_gab_db $f_pintura_db $f_construcao_db $f_qt_db $f_valor_uni_db $f_valor_total_db $f_obs_db $f_data_motor_recebido_db $f_reprogramacao_db $f_baixa_db $f_data_baixa_db ORDER BY data_entrega";
	$result = MYSQL_QUERY($query); 

//ABRI WHILE 	
while ($dados = mysql_fetch_array($result)) {  
$data_entrega = $dados["data_entrega"]; 
$num_os = $dados["num_os"]; 
$item = $dados["item"]; 

$nome_cliente = $dados["nome_cliente"];
$oc_obra = $dados["oc_obra"]; 
$mercado = $dados["mercado"]; 
$estado = $dados["estado"];  
$fornecimento_motor = $dados["fornecimento_motor"];

$descr_vent = $dados["descr_vent"]; 
$qt = $dados["qt"]; 
$modelo = $dados["modelo"]; 
$tamanho = $dados["tamanho"]; 
$arranjo = $dados["arranjo"]; 
$rotacao = $dados["rotacao"]; 
$gab = $dados["gab"]; 
$construcao = $dados["construcao"];  
$pintura = $dados["pintura"]; 

$status_corte = $dados["status_corte"];
$status_cald1 = $dados["status_cald1"];
$status_cald2 = $dados["status_cald2"];
$status_rotor_ll = $dados["status_rotor_ll"];
$status_rotor_sir = $dados["status_rotor_sir"];
$status_balanc = $dados["status_balanc"];
$status_pintura = $dados["status_pintura"];
$status_gabinete = $dados["status_gabinete"];
$status_funilaria = $dados["status_funilaria"];

$mat1 = $dados["mat1"];
$mat2 = $dados["mat2"];
$mat3 = $dados["mat3"];

$dia_data_entrega = substr($data_entrega, -2);   
$mes_data_entrega = substr($data_entrega, -5,2);  
$ano_data_entrega = substr($data_entrega, -10,4); 
$data_entrega = ($dia_data_entrega."/".$mes_data_entrega."/".$ano_data_entrega); 
   
   
   if ( $data_entrega == $data_entrega_antiga) 
		{ 
		if  ( $num_os == $num_os_antigo  )  
			{  
			if ( $nome_cliente ==  $nome_cliente_antigo ) { $data_entrega = ""; $num_os = "          "; $nome_cliente = ""; } 
			}   
		}
   
   $pdf->Cell(15, 4, $data_entrega, 1, 0, 'C');  
   
   $pdf->Cell(15, 4, $num_os ."/". $item, 1, 0, 'C');  
       
   $pdf->Cell(50, 4, $nome_cliente, 1, 0, 'L'); 
   
   $data_entrega_antiga = ($dia_data_entrega."/".$mes_data_entrega."/".$ano_data_entrega); 
   $num_os_antigo = $dados["num_os"];  
   $nome_cliente_antigo = $dados["nome_cliente"];
     
   $pdf->Cell(22, 4, $descr_vent, 1, 0, 'C');  
   $pdf->Cell(8, 4, $qt, 1, 0, 'C');
   $pdf->Cell(13, 4, $modelo, 1, 0, 'C');    
   $pdf->Cell(10, 4, $tamanho, 1, 0, 'C'); 
   $pdf->Cell(8, 4, $arranjo, 1, 0, 'C');  
   $pdf->Cell(9, 4, $rotacao, 1, 0, 'C'); 
   $pdf->Cell(9, 4, $gab, 1, 0, 'C');  
   $pdf->Cell(10, 4, $construcao, 1, 0, 'C'); 
   $pdf->Cell(15, 4, $pintura, 1, 0, 'C');
   
   $pdf->Cell(8, 4, $status_corte, 1, 0, 'C');
   $pdf->Cell(8, 4, $status_cald1, 1, 0, 'C');
   $pdf->Cell(8, 4, $status_cald2, 1, 0, 'C');
   $pdf->Cell(8, 4, $status_rotor_ll, 1, 0, 'C');
   $pdf->Cell(8, 4, $status_rotor_sir, 1, 0, 'C');
   $pdf->Cell(8, 4, $status_balanc, 1, 0, 'C'); 
   $pdf->Cell(8, 4, $status_pintura, 1, 0, 'C');
   $pdf->Cell(8, 4, $status_gabinete, 1, 0, 'C');
   $pdf->Cell(8, 4, $status_funilaria, 1, 0, 'C');
   
   $pdf->Cell(9, 4, $mat1, 1, 0, 'C');
   $pdf->Cell(9, 4, $mat2, 1, 0, 'C');
   $pdf->Cell(9, 4, $mat3, 1, 1, 'C');
   
//echo "num_os = ".$num_os ."/". $item;

}//FECHAR WHILE	

}//QUANDO N�O FOR SABADO OU DOMINGO 

else

{//QUANDO FOR SABADO OU DOMINGO
   $pdf->Ln(2); 
   $pdf->SetFont("Arial", "B", $fonte_cabecalho);
   $pdf->Cell(45, 8, "Data Montagem $data_previsao - $dia_semana", 0, 1, 'L'); 
   $dias_previsao = $dias_previsao + 1; 
   
}//QUANDO FOR SABADO OU DOMINGO 

}//FECHAR FOR SOMAR DIAS (dias)	


//QUANDO A DATA PROG. DIARIA FOR EM BRANCO 
if ( $f_data_previsao == "" ) {
   $pdf->Ln(6);    
   $pdf->SetFont("Arial", "B", $fonte_erro);
   $pdf->Cell(180, 10, "VOC� N�O SELECIONOU A DATA PREVISAO", 1, 1, 'C'); 
}//FECHA IF QUANDO A DATA PROG. DIARIA FOR EM BRANCO 


//MONTA O RODAPE              
	$pdf->SetFont("Arial", "B", $fonte_rodape);
	$pdf->Ln(16);
	$pdf->Cell(250, -15, "Data Impress�o - $data_emissao_hoje - $hora_atual" , 0, 0, 'R');    

//SAIDA DO PDF
$pdf->Output("$end_final", "$tipo_pdf");

//$pdf->Output();

?>