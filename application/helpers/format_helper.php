<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('format_money'))
{
	/**
	 * Format money
	 * 
	 * @access public
	 * @param string $value
	 * @param int $decimal
	 * @return string
	 */
	function format_money($value, $decimal = 2)
	{
		if (is_null($value)) {
			return false;
		}
		
		$string= '';
		$string .= ($value < 0) ? '-' : '';
		$string .= number_format(round(abs($value), (int)$decimal), (int)$decimal, ',', '.');
		
		return $string;
	}
}

if ( ! function_exists('format_currency'))
{
	/**
	 * Format currency
	 * 
	 * @access public
	 * @param string $float
	 * @param bool $format
	 * @return string
	 */
	function format_currency($float, $format = false)
	{
		if (is_null($float)) {
			return false;
		}
		
		$ci =& get_instance();
		$ci->load->library('localisation/currency');
		
		return $ci->currency->format($float, '', '', $format);
	}
}

if ( ! function_exists('format_date'))
{
	/**
	 * Format date
	 * 
	 * @access public
	 * @param string $string
	 * @param bool $time
	 * @return string
	 */
	function format_date($string, $time = false)
	{
		$format = $time ? 'd/m/Y H:i' : 'd/m/Y';
		
		if ($string == '0000-00-00 00:00:00' || $string == '0000-00-00') {
			return '';
		}
		
		return date($format, strtotime($string));
	}
}

if ( ! function_exists('format_doc_number'))
{
	/**
	 * Format document number
	 * Create document number from prefix and ID 
	 * 
	 * @access public
	 * @param string $type
	 * @param int $id
	 * @return string
	 */
	function format_doc_number($type, $id = 0)
	{
		$ci =& get_instance();
		$ci->load->config('numbering');
		
		$prefixs = $ci->config->item('prefix_no');
		
		if (array_key_exists($type, $prefixs)) {
			$number = sprintf('%07d', $id);
			return $prefixs[$type].(string)$number;
		}
	}
}

if ( ! function_exists('format_address'))
{
	/**
	 * Format address
	 * 
	 * @access public
	 * @param array $address
	 * @return string
	 */
	function format_address($address)
	{
		$find = array('{name}', '{address}', '{telephone}', '{postcode}', '{subdistrict}', '{city}', '{province}');
		
		$replace = array(
			'name'		=> $address['name'],
			'address'	=> $address['address'],
			'telephone'	=> $address['telephone'],
			'postcode'	=> $address['postcode'],
			'subdistrict'	=> $address['subdistrict'],
			'city'	=> $address['city'],
			'province'	=> $address['province']
		);
		
		$format = '{name}'."\n".'{address}, {subdistrict}, {city} - {province} {postcode}'."\n".'{telephone}';
		
		return str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
	}
}

if ( ! function_exists('get_number'))
{
	/**
	 * Get number
	 * 
	 * @access public
	 * @param string $doc_number
	 * @return int|bool
	 */
	function get_number($doc_number)
	{
		preg_match('/([a-zA-Z]+)(\d+)/', $doc_number, $matches);
		
		if (count($matches) == 3) {
			return (int)$matches[2];
		}
		
		return false;
	}
}

if ( ! function_exists('check_date_format'))
{
	/**
	 * Check date format
	 * 
	 * @access public
	 * @param string $date
	 * @return void
	 */
	function check_date_format($date)
	{
		if (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/', $date)) {
			return true;
		}
		
		return false;
	}
}

if ( ! function_exists('format_serial'))
{
	/**
	 * Format serial
	 * 
	 * @access public
	 * @param string $serial
	 * @param string $digit_split
	 * @return string
	 */
	function format_serial($serial, $digit_split = 4)
	{
		return implode('-', str_split($digit_split, 4));
	}
}

if ( ! function_exists('format_tax_number'))
{
	/**
	 * Format tax number
	 * 
	 * @access public
	 * @param string $tax_number
	 * @return string
	 */
	function format_tax_number($tax_number)
	{
		return $tax_number;
	}
}

if ( ! function_exists('to_word'))
{
	/**
	 * To word for Indonesian
	 * 
	 * @access private
	 * @param float $num
	 * @return string
	 */
	function to_word($num)
	{
		$num = abs($num);
		$words = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');
		$temp = ' ';
		
		if ($num < 12) {
			$temp = ' '.$words[$num];
		} elseif ($num < 20) {
			$temp = to_word($num - 10).' belas';
		} elseif ($num < 100) {
			$temp = to_word($num / 10).' puluh'.to_word($num % 10);
		} elseif ($num < 200) {
			$temp = ' seratus'.to_word($num - 100);
		} elseif ($num < 1000) {
			$temp = to_word($num / 100).' ratus'.to_word($num % 100);
		} elseif ($num < 2000) {
			$temp = ' seribu'.to_word($num - 1000);
		} elseif ($num < 1000000) {
			$temp = to_word($num / 1000).' ribu'.to_word($num % 1000);
		} elseif ($num < 1000000000) {
			$temp = to_word($num / 1000000).' juta'.to_word($num % 1000000);
		} elseif ($num < 1000000000000) {
			$temp = to_word($num / 1000000000).' milyar'.to_word(fmod($num, 1000000000));
		} elseif ($num < 1000000000000000) {
			$temp = to_word($num / 1000000000000).' trilyun'.to_word(fmod($num, 1000000000000));
		}
		
		return $temp;
	}
}

if ( ! function_exists('in_words'))
{	
	/**
	 * Inwords
	 * 
	 * @access public
	 * @param float $num
	 * @param int $style
	 * @return string
	 */
	function in_words($num, $style = null)
	{
		if ($num < 0) {
			$result = 'minus '.trim(to_word($num));
		} else {
			$poin = trim(to_comma($num));
			$result = trim(to_word($num));
		}
		
		switch($style) {
			case 1:
				$result = $poin 
				? strtoupper($result).' KOMA '.strtoupper($poin) 
				: strtoupper($result);
			break;
				
			case 2:
				$result = $poin 
				? strtolower($result).' koma '.strtolower($poin) 
				: strtolower($result);
			break;
			
			case 3:
				$result = $poin 
				? ucwords($result).' Koma '.ucwords($poin) 
				: ucwords($result);
			break;
			
			default:
				$result = $poin 
				? ucfirst($result).' koma '.ucfirst($poin) 
				: ucfirst($result);
			break;
		}
		
		return $result;
	}
}

if ( ! function_exists('to_comma'))
{	
	/**
	 * To comma
	 * 
	 * @access private
	 * @param float $num
	 * @return string
	 */
	function to_comma($num)
	{
		$num = stristr($num, '.');
		$numbers = array('nol', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan');
		$temp = ' ';
		$length = strlen($num);
		$pos = 1;
		
		while($pos < $length) {
			$char = substr($num, $pos, 1);
			$pos++;
			$temp.= ' '.$numbers[$char];
		}
		
		return $temp;
	}
}

if ( ! function_exists('substrwords'))
{
	/**
	 * Cut string
	 * 
	 * @access public
	 * @param string $text
	 * @param string $maxchar
	 * @param string $end
	 * @return string
	 */
	function substrwords($text, $maxchar = 300, $end = '...') {
		if (strlen($text) > $maxchar || $text == '') {
			$words = preg_split('/\s/', $text);
			$output = '';
			
			for ($i=0; $i<count($words); $i++) {
				$length = strlen($output)+strlen($words[$i]);
				
				if ($length > $maxchar) {
					break;
				} else {
					$output .= " " . $words[$i];
				}
			}
			
			$output .= $end;
		} 
		else {
			$output = $text;
		}
		return $output;
	}
}

if ( ! function_exists('format_time_ago'))
{
	function format_time_ago($datetime)
	{
		$datetime1 = new DateTime('now');
		$datetime2 = new DateTime($datetime);

		$interval = $datetime1->diff($datetime2);
		$suffix = ($interval->invert ? ' yang lalu' : '');
		
		if ($v = $interval->y >= 1) return pluralize($interval->y, 'tahun').$suffix;
		if ($v = $interval->m >= 1) return pluralize($interval->m, 'bulan').$suffix;
		if ($v = $interval->d >= 1) return pluralize($interval->d, 'hari').$suffix;
		if ($v = $interval->h >= 1) return pluralize($interval->h, 'jam').$suffix;
		if ($v = $interval->i >= 1) return pluralize($interval->i, 'menit').$suffix;
		
		return pluralize($interval->s, 'detik').$suffix;
	}
}

if ( ! function_exists('pluralize'))
{
	function pluralize($count, $text)
	{
		//return $count.(($count == 1) ? ( " $text" ) : (" ${text}s"));
		return $count.(($count == 1) ? ( " $text" ) : (" ${text}"));
	}
}

if ( ! function_exists('calculate_tax'))
{
	/**
	 * Calculate tax
	 * 
	 * @access public
	 * @param float $amount
	 * @param float $tax_value
	 * @param string $tax_type
	 * @return string
	 */
	function calculate_tax($amount, $tax_value, $tax_type)
	{
		$_ci =& get_instance();
		$_ci->load->library('currency');
			
		if ($tax_value && $tax_type == 'Eksklusif') {
			return $_ci->currency->format($amount + ($amount * $tax_value/100));
		} else {
			return $_ci->currency->format($amount);
		}
	}
}