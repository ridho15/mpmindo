<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currency
{
	private $ci;
	private $code;
	private $currencies = array();
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->ci =& get_instance();
		$this->initialize();
		log_message('debug', 'Currency Class Initialized');
	}
	
	/**
	 * Initialize currencies data
	 * 
	 * @access private
	 * @return void
	 */
	private function initialize()
	{
		$this->ci->load->model('currency_model');
		
		foreach ($this->ci->currency_model->get_currencies() as $result) {
			$this->currencies[$result['code']] = $result;
		}
		
		$get_currency = $this->ci->input->get('currency');
		$session_currency = $this->ci->session->userdata('currency');
		$cookie_currency = $this->ci->input->cookie('currency');
		
		if ($get_currency && array_key_exists($get_currency, $this->currencies))
		$this->set($get_currency);
		
		elseif ($session_currency && array_key_exists($session_currency, $this->currencies))
		$this->set($session_currency);
		
		elseif ($cookie_currency && array_key_exists($cookie_currency, $this->currencies))
		$this->set($cookie_currency);
		
		else $this->set('IDR');
	}
	
	/**
	 * Set currency
	 * 
	 * @access public
	 * @param string $currency Currency code
	 * @return void
	 */
	public function set($currency)
	{
		$this->code = $currency;
		
		if ( ! $this->ci->session->userdata('currency') || $this->ci->session->userdata('currency') != $currency) {
			$this->ci->session->set_userdata('currency', $currency);
		}
		
		if ( ! $this->ci->input->cookie('currency') || $this->ci->input->cookie('currency') != $currency) {
			$this->ci->input->set_cookie('currency', $currency, time() + 60 * 60 * 24 * 30);
		}
	}
	
	/**
	 * Format currency
	 * 
	 * @access public
	 * @param mixed $number
	 * @param string $currency
	 * @param string $value
	 * @param bool $format
	 * @return string
	 */
	public function format($number, $currency = '', $value = '', $format = true)
	{
		if ($currency && $this->has($currency)) {
			$symbol = $this->currencies[$currency]['symbol'];
		} else {
			$symbol = $this->currencies[$this->code]['symbol'];
			$currency = $this->code;
		}
		
		$value = ($value) ? $value : $this->currencies[$currency]['value'];
		$value = ($value) ? (float)$number * $value : $number;

		$string = '';
		
		if ($symbol && $format) {
			$string.= ($number < 0) ? '-'.$symbol : $symbol.' ';
		} else {
			$string.= ($number < 0) ? '-' : '';
		}
		
		$string.= number_format(round(abs($value), 2), 0, ',', '.');

		return $string;
	}
	
	/**
	 * Convert currency
	 * 
	 * @access public
	 * @param string $value
	 * @param string $from
	 * @param string $to
	 * @return string
	 */
	public function convert($value, $from, $to)
	{
		$from = (isset($this->currencies[$from])) ? $this->currencies[$from]['value'] : 0;
		$to = (isset($this->currencies[$to])) ? $this->currencies[$to]['value'] : 0;		
		return $value * ($to / $from);
	}
	
	/**
	 * Get currency ID
	 * 
	 * @access public
	 * @param string $currency
	 * @return int
	 */
	public function get_id($currency = '')
	{
		if ( ! $currency)
		return $this->currencies[$this->code]['currency_id'];
		elseif ($currency && isset($this->currencies[$currency]))
		return $this->currencies[$currency]['currency_id'];
		return 0;
	}
	
	/**
	 * Get symbol left
	 * 
	 * @access public
	 * @param string $currency
	 * @return string
	 */
	public function get_symbol($currency = '')
	{
		if ( ! $currency)
		return $this->currencies[$this->code]['symbol'];
		elseif ($currency && isset($this->currencies[$currency]))
		return $this->currencies[$currency]['symbol'];
		return '';
	}
	
	/**
	 * Get code
	 * 
	 * @access public
	 * @return string Currency code
	 */
	public function get_code()
	{
		return $this->code;
	}
	
	/**
	 * Get value
	 * 
	 * @access public
	 * @param string $currency Currency code
	 * @return string
	 */
	public function get_value($currency = '')
	{
		if ( ! $currency)
		return $this->currencies[$this->code]['value'];
		elseif ($currency && isset($this->currencies[$currency]))
		return $this->currencies[$currency]['value'];
		return 0;
	}
	
	/**
	 * Does currency exists?
	 * 
	 * @access public
	 * @param string $currency Currency code
	 * @return bool True if exists or false if not exists
	 */
	public function has($currency)
	{
		return isset($this->currencies[$currency]);
	}
	
	/**
	 * To word for Indonesian
	 * 
	 * @access private
	 * @param float $num
	 * @return string
	 */
	public function to_word($num)
	{
		$num = abs($num);
		$words = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');
		$temp = ' ';
		
		if ($num < 12) {
			$temp = ' '.$words[$num];
		} elseif ($num < 20) {
			$temp = $this->to_word($num - 10).' belas';
		} elseif ($num < 100) {
			$temp = $this->to_word($num / 10).' puluh'.$this->to_word($num % 10);
		} elseif ($num < 200) {
			$temp = ' seratus'.$this->to_word($num - 100);
		} elseif ($num < 1000) {
			$temp = $this->to_word($num / 100).' ratus'.$this->to_word($num % 100);
		} elseif ($num < 2000) {
			$temp = ' seribu'.$this->to_word($num - 1000);
		} elseif ($num < 1000000) {
			$temp = $this->to_word($num / 1000).' ribu'.$this->to_word($num % 1000);
		} elseif ($num < 1000000000) {
			$temp = $this->to_word($num / 1000000).' juta'.$this->to_word($num % 1000000);
		} elseif ($num < 1000000000000) {
			$temp = $this->to_word($num / 1000000000).' milyar'.$this->to_word(fmod($num, 1000000000));
		} elseif ($num < 1000000000000000) {
			$temp = $this->to_word($num / 1000000000000).' trilyun'.$this->to_word(fmod($num, 1000000000000));
		}
		
		return $temp;
	}
	
	/**
	 * Inwords
	 * 
	 * @access public
	 * @param float $num
	 * @param int $style
	 * @return string
	 */
	public function in_words($num, $style = null)
	{
		if ($num < 0) {
			$result = 'minus '.trim($this->to_word($num));
		} else {
			$poin = trim($this->to_comma($num));
			$result = trim($this->to_word($num));
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
	
	/**
	 * To comma
	 * 
	 * @access private
	 * @param float $num
	 * @return string
	 */
	private function to_comma($num)
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