<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Total_coupon
{
	protected $sort_order = 2;
	
	public function __construct()
	{
		$this->ci =& get_instance();
	}
	
	/**
	 * Get total
	 * 
	 * @access public
	 * @param array &$total_data
	 * @param float &$total
	 * @return void
	 */
	public function get(&$total_data, &$total)
	{
		if ($this->ci->session->userdata('coupon')) {
			$this->ci->load->model('coupon_model');

			if ($coupon_info = $this->ci->coupon_model->get_coupon($this->ci->session->userdata('coupon'))) {
				$discount_total = 0;

				$sub_total = $this->ci->shopping_cart->get_sub_total();

				if ($coupon_info['type'] == 'f') {
					$coupon_info['discount'] = min($coupon_info['discount'], $sub_total);
				}

				foreach ($this->ci->shopping_cart->get_products() as $product) {
					$discount = 0;

					if ($coupon_info['type'] == 'f') {
						$discount = $coupon_info['discount'] * ($product['total'] / $sub_total);
					} elseif ($coupon_info['type'] == 'p') {
						$discount = $product['total'] / 100 * $coupon_info['discount'];
					}

					$discount_total += $discount;
				}

				if ($coupon_info['shipping'] and $this->ci->session->userdata('shipping_method')) {
					$shipping_methods = $this->ci->session->userdata('shipping_method');
					foreach ($shipping_methods as $$shipping_method) {
						$discount_total += $shipping_method['cost'];
					}
				}

				$total_data[] = array(
					'code' => 'coupon',
					'title'	=> sprintf('Kupon (%s)', $this->ci->session->userdata('coupon')),
					'text' => $this->ci->currency->format(-$discount_total),
					'value'	=> -$discount_total,
					'sort_order' => $this->sort_order
				);

				$total -= $discount_total;
			}
		}
	}
	
	/**
	 * Confirm
	 * 
	 * @access public
	 * @param array $order_info
	 * @param array $order_total
	 * @return void
	 */
	public function confirm($order_info, $order_total)
	{
		$code = '';
		$start = strpos($order_total['title'], '(') + 1;
		$end = strrpos($order_total['title'], ')');

		if ($start && $end) $code = substr($order_total['title'], $start, $end - $start);

		$this->ci->load->model('coupon_model');

		if ($coupon_info = $this->ci->coupon_model->get_coupon($code)) {
			$this->ci->coupon_model->redeem(
				$coupon_info['coupon_id'], 
				$order_info['order_id'], 
				$order_info['user_id'], 
				$order_total['value']
			);
		}
	} 
}