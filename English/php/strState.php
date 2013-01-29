<?php
/*-----------------------------------------------
 *|		     	strState for PHP		    	|
 *-----------------------------------------------
 *|	   Powered by Alexandro Bazán Ladines   	|
 *|				100% free - 2013				|
 *-----------------------------------------------
 */

class strState
{

/*
 *The static method 'core' will drive all other methods that require
 *the use of regular expressions to validate the string to evaluate.
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
 *Method 'num' will return true if the string contains only numeric characters
 *otherwise it will return false.
 *
 *the parameter $ str capture the string to evaluate, the parameters $ min and $ max are optional
 *these will obtain the minimum and maximum relative size of the chain, in the case it is required
 *that chain have a fixed size  only send the parameter $ min.
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
 *Method 'alpha' will return true if the string contains only alphabetic characters will return false otherwise.
 *
 *the parameter $ str capture the string to evaluate, the parameters $ min and $ max are optional
 *these will obtain the minimum and maximum relative size of the chain, in the case it is required
 *that chain have a fixed size  only send the parameter $ min.
 */

	public static function alpha($val = false, $min = false, $max = false)
	{
		$values = array(
				'str' => $val,
				'min' => $min,
				'max' => $max
				);

		$regex = '[a-zA-Z]';

		return self::core($regex,$values);
	}

/*
 *Method 'alphaNum' will return true if the string contains only alphabetic or numeric characters will return false otherwise.
 *
 *the parameter $ str capture the string to evaluate, the parameters $ min and $ max are optional
 *these will obtain the minimum and maximum relative size of the chain, in the case it is required
 *that chain have a fixed size  only send the parameter $ min.
 */

	public static function alphaNum($val = false, $min = false, $max = false)
	{
		$values = array(
				'str' => $val,
				'min' => $min,
				'max' => $max
				);

		$regex = '[a-zA-Z-0-9]';

		return self::core($regex,$values);
	}

/*
 *Method 'eMail' will return true if the string meets the default mail format or mail extensions we want to accept
 *the validator,otherwise will return false.
 *
 *the parameter $ str capture the string to evaluate,
 *the parameter $ dom capture the email extensions allowed for validation.
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
 *Method 'date' will return true if the string conforms to the default date format or the format you want to accept the validator,
 *otherwise it will return false.
 *
 *the parameter $ str  capture the string to evaluate, the parameter $format  is optional
 *will indicate the validator  the date format you want to validate, the parameter $ min and $ max will get the range of years
 *in which the date should be.
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