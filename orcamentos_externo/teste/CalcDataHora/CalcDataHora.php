<?php
// *
// * @author Andre Lourenco Pedroso - alp.pedroso@gmail.com
// *
// * @date 15 de Janeiro de 2007
// *

/**
 *
 * - Manipulacao de Data ou Hora.
 *
 * 		Operacoes: soma DIA, MES ,ANO, HORA, MINUTOS, SEGUNDOS.
 *		Formatos :
 *			Data: 15/01/2007
 *			Hora: 10:35:00
 * 		Para subtrair, basta passar um valor negativo:
 * 		Ex:
 * 			$obj->somaDia(-10);
 *
 * - Calcula diferenca entre duas datas.
 *
 * 		Operacoes: difDataHora.
 *		Formatos :
 *			Data: 15/01/2007 10:35:00
 * 		E necessario passar duas datas como parametro e o tipo de retorno desejado:
 * 		Ex:
 * 			$obj->difDataHora($dataMenor,$dataMaior,"m");
 *
 */
class CalcDataHora
{
	private $datahora;
	private $hora;

	function CalcDataHora($data="",$hora="")
	{
		if($hora=="")
		{
			$hora = date("H:i:s");
		}
		if($data=="")
		{
			$data = date("d/m/Y ");
		}
		else if ($this->validaData($data,"d"))
		{
			die ("Padrao de data ($data) invalido! - Padrao = 15/01/2007");
		}
		$this->data = explode("/",$data);
		$this->hora = explode(":",$hora);
	}
	private function validaData($data,$op)
	{
		switch($op)
		{
			case "d": // Padrao: 15/01/2007
				$er = "(([0][1-9]|[1-2][0-9]|[3][0-1])\/([0][1-9]|[1][0-2])\/([0-9]{4}))";
				if(ereg($er,$data))
				{
					return 0;
				}
				else
				{
					return 1;
				}
				break;
			case "dh": // Padrao 15/01/2007 10:30:00
				$er = "(([0][1-9]|[1-2][0-9]|[3][0-1])\/([0][1-9]|[1][0-2])\/([0-9]{4})*)";
				if(ereg($er,$data))
				{
					return 0;
				}
				else
				{
					return 1;
				}
				break;
		}
	}
	// DATA
	public function somaDia($dias=1)
	{
		$this->data = strftime("%d/%m/%Y", mktime(0, 0, 0, $this->data[1], $this->data[0]+$dias, $this->data[2]));
		return $this->data;
	}
	public function somaMes($meses=1)
	{
		$this->data = strftime("%d/%m/%Y", mktime(0, 0, 0, $this->data[1]+$meses, $this->data[0], $this->data[2]));
		return $this->data;
	}
	public function somaAno($anos=1)
	{
		$this->data = strftime("%d/%m/%Y", mktime (0, 0, 0, $this->data[1], $this->data[0], $this->data[2]+$anos));
		return $this->data;
	}
	public function getData()
	{
		return $this->data;
	}
	// HORA
	public function somaSegundo($segundos=1)
	{
		$this->hora = strftime("%H:%M:%S",mktime($this->hora[0],$this->hora[1],$this->hora[2]+$segundos, 0, 0, 0));
		return $this->hora;
	}
	public function somaMinuto($minutos=1)
	{
		$this->hora = strftime("%H:%M:%S",mktime($this->hora[0],$this->hora[1]+$minutos,$this->hora[2], 0, 0, 0));
		return $this->hora;
	}
	public function somaHora($horas=1)
	{
		$this->hora = strftime("%H:%M:%S",mktime($this->hora[0]+$horas,$this->hora[1],$this->hora[2], 0, 0, 0));
		return $this->hora;
	}
	public function getHora()
	{
		return $this->hora;
	}


	/**
	 *
	 * Retorna diferença entre as datas em Dias, Horas ou Minutos
	 * Function difDataHora(data menor, [data maior],[dias horas minutos segundos])
	 *
	 * Formato 04/05/2006 12:00:00
	 *
	 * Chame a funcao com o valor NULL como 'data maior' para 'data maior' = data atual.
	 *
	 * Formatacao do retorno [dias horas minutos segundos]:
	 *
	 * "s": Segundos
	 * "m": Minutos
	 * "H": Horas
	 * "h": Horas arredondada
	 * "D": Dias
	 * "d": Dias arredontados
	 *
	 * Original: Gambiarra.com.br Bozo@gambiarra.com.br
	 *
	 * Modificado: Andre Lourenco Pedroso alp.pedroso@gmail.com
	 * Data 15/01/2007 10:00
	 */
	public function difDataHora($datamenor,$datamaior="",$tipo="")
	{
		if($this->validaData($datamenor,"dh"))
		{
			die ("data errada - $datamenor");
		}
		if($datamaior==""){
			$datamaior = date("d/m/Y H:i:s");
		}
		if($tipo==""){
			$tipo = "h";
		}
		list ($diamenor, $mesmenor, $anomenor, $horamenor, $minutomenor, $segundomenor) = split("[/: ]",$datamenor);
		list ($diamaior, $mesmaior, $anomaior, $horamaior, $minutomaior, $segundomaior) = split("[/: ]",$datamaior);

		$segundos =	mktime($horamaior,$minutomaior,$segundomaior,$mesmaior,$diamaior, $anomaior)-mktime($horamenor,$minutomenor,$segundomenor,$mesmenor,$diamenor, $anomenor);

		switch($tipo){
			case "s": // Segundo
				$diferenca = $segundos;
				break;
			case "m": // Minuto
				$diferenca = $segundos/60;
				break;
			case "H": // Hora
				$diferenca = $segundos/3600;
				break;
			case "h": // Hora Arredondada
				$diferenca = round($segundos/3600);
				break;
			case "D": // Dia
				$diferenca = $segundos/86400;
				break;
			case "d": // Dia Arredondado
				$diferenca = round($segundos/86400);
				break;
		}
		return $diferenca;
	}
}

?>
