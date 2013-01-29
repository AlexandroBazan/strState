<?php
/*-----------------------------------------------
 *|		     		strState v1.0 				|
 *-----------------------------------------------
 *|	 Desarrollado por Alexandro Bazán Ladines 	|
 *|				100% gratis - 2013				|
 *-----------------------------------------------
 */

class strState
{

/*
 *El metodo estatico core sera el motor de todos los demas metodos que requeriran de
 *La utilizacion de Expreciones Regulares para la validación de la cadena a evaluar
 */

	private static function core($regex,$values)
	{
		$reply = false;
		$param = "";

		if($values['str'] !== false)
		{
			if(is_numeric($values['min']))
			{
				if(is_numeric($values['max']))
					$param = '{'.$values['min'].','.$values['max'].'}';
				else
					$param = '{'.$values['min'].'}';
			}
			if(preg_match('/^'.$regex.$param.'+$/', $values['str']))
				return true;
		}else
			return false;
	}

/*
 *El metodo num retornara verdadero (true) si la cadena solo contiene caracteres numéricos
 *de lo contrario retornara falso (false).
 *
 *el parametro $str capturara la cadena a evaluar, los parametros $min y $max seran opcionales
 *estos obtendran el tamaño minimo y maximo relativamente de la cadena, en el caso de que se requiera que
 *la cadena tenga un un tamaño fijo solo se devera enviar el parametro $min para que este tomo el comportamiento
 *de tamaño fijo de la cadena.
 */

	public static function num($str = false, $min = false, $max = false)
	{
		$values = array(
				'str' => $val,
				'min' => $min,
				'max' => $max
				);

		$regex = '[0-9]';

		return self::core($regex,$values);
		
	}

/*
 *El metodo alpha retornara verdadero (true) si la cadena solo contiene caracteres del alfabeto
 *de lo contrario retornara falso (false).
 *
 *el parametro $str capturara la cadena a evaluar, los parametros $min y $max seran opcionales
 *estos obtendran el tamaño minimo y maximo relativamente de la cadena, en el caso de que se requiera que
 *la cadena tenga un un tamaño fijo solo se devera enviar el parametro $min para que este tomo el comportamiento
 *de tamaño fijo de la cadena.
 */

	public static function alpha($val = false, $min = false, $max = false)
	{
		$values = array(
				'str' => $val,
				'min' => $min,
				'max' => $max
				);

		$regex = '[a-zA-Z ñ Ñ á Á éÉ íÍ óÓ úÚ-\s]';

		return self::core($regex,$values);
	}

/*
 *El metodo alphaNum retornara verdadero (true) si la cadena solo contiene caracteres alfanuméricos
 *de lo contrario retornara falso (false).
 *
 *el parametro $str capturara la cadena a evaluar, los parametros $min y $max seran opcionales
 *estos obtendran el tamaño minimo y maximo relativamente de la cadena, en el caso de que se requiera que
 *la cadena tenga un un tamaño fijo solo se devera enviar el parametro $min para que este tomo el comportamiento
 *de tamaño fijo de la cadena.
 */

	public static function alphaNum($val = false, $min = false, $max = false)
	{
		$values = array(
				'str' => $val,
				'min' => $min,
				'max' => $max
				);

		$regex = '[a-zA-Z-0-9 ñ Ñ á Á éÉ íÍ óÓ úÚ-\s]';

		return self::core($regex,$values);
	}

/*
 *El metodo eMail retornara verdadero (true) si la cadena cumple con el formato de correo por defecto
 *o las extenciones de correo que queremos que acepte el validador, de lo contrario retornara falso (false).
 *
 *el parametro $str capturara la cadena a evaluar, el parametro $dom captura la o las extensiones de correo
 *que aceptara el validador por medio de un array o cadena.
 */

	public static function eMail($str = false, $dom = false)
	{
		$values = array(
				'str' => $str,
				'min' => false,
				'max' => false
				);
		
		if(!$dom)
			$add = '[a-zA-Z-0-9-]+\.[a-z]{2,4}?(\.[a-z]{2})';
		else{
			if(!is_array($dom))
				$add = $dom;
			else{

				$valDom = end(explode('@', $str));
				$bool   = false;

				for($i = 0; $i < count($dom); $i++)
				{
					if($dom[$i] === $valDom)
					{
						$add  = $dom[$i];
						$bool = true;
						$i    = count($dom);
					}
				}

				if(!$bool)
					return false;
			}
		}

		$regex = '[_a-z0-9-]+(\.[_a-z0-9-]+)*@'.$add;

		return self::core($regex,$values);
	}

/*
 *El metodo eMail retornara verdadero (true) si la cadena cumple con el formato de fecha por defecto
 *o el formato que queremos que acepte el validador, de lo contrario retornara falso (false).
 *
 *el parametro $str capturara la cadena a evaluar, el parametro $format seran opcionales
 *este indicara al validador el formato de fecha que se desea validar, en este caso no se hace uso del metodo core.
 */

	public static function date($str = false, $format = false, $min = false, $max = false)
	{

		if(!$str)
			return false;
		
		$min = !$min? date('Y') - 108 : $min ;
		$max = !$max? date('Y') : $max ;

		$format = str_replace('/', '-', $format);	
		$str    = str_replace('/', '-', $str);

		$str = explode('-', $str);

		if(count($str) !== 3 )
			return false;

		if($format === 'yyyy-MM-dd')
			$str = array((int)$str[2],(int)$str[1],(int)$str[0]);
		elseif ($format === 'dd-MM-yyyy')
			$str = array((int)$str[0],(int)$str[1],(int)$str[2]);
		elseif ($format === 'yyyy-dd-MM')
			$str = array((int)$str[1],(int)$str[2],(int)$str[0]);
		elseif ($format === 'MM-dd-yyyy')
			$str = array((int)$str[1],(int)$str[0],(int)$str[2]);
		else
			return false;

		if($str[1]>12 || $str[1]<1 || strlen($str[2]) !== 4 || strlen($str[0]) > 2 || strlen($str[1]) > 2)
			return false;

		if($str[1] === 2)
		{

			if(($str[2]%4 === 0) || ($str[2]%4 !== 0) && $str[2]%400 === 0) 
				$days = 29;
			else
				$days = 28;

		}else{

			if($str[1] === 1 || $str[1] === 3 || $str[1] === 5 || $str[1] === 7 || $str[1] === 8 || $str[1] === 10 || $str[1] === 12)
				$days = 31;
			else
				$days = 30;

		}

		if( !($str[0] > 0  && $str[0] <= $days ) || !($str[1] > 0 && $str[1] < 13) || !($str[2] >= $min && $str[2] <= $max ))
			return false;

		return true;

	}
}

?>