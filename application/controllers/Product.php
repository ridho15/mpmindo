<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller
{
	private $limit = 20;
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('category_model');
		$this->load->model('product_model');
		$this->load->helper('format');
		$this->load->helper('form');
		$this->load->library('user_agent');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$segments = $this->uri->segment_array();
		
		if (count($segments) < 2) {
			$this->products();
		} else {
			$category = false;
			array_shift($segments);
			$product = array_pop($segments);
			$path = '';
			$path2 = '';
			$path3 = '';
			$path_ids = array();
			
			$category_name = '';
			$category_link = '';
			
			foreach ($segments as $slug) {
				if ($category = $this->category_model->get_category($slug)) {
					$path .= $category['slug'].'/';
					$path_ids[] = $category['category_id'];
					$this->load->breadcrumb($category['name'], site_url('category/'.$path));
					$category_name = $category['name'];
					$category_link = site_url('category/'.$path);
				}
			}
			
			array_pop($path_ids);
			
			$path_id = implode('-', $path_ids);
			
			if ($category && $category['path_id'] == $path_id) {
				$data = $this->product_model->get_product($product);
				if ($data) {
					$data['category_name'] = $category_name;
					$data['category_link'] = $category_link;
					$data['images'] = array();
					$images = $this->product_model->get_product_images($data['product_id']);
					
					if ($images) {
						foreach ($images as $image) {
							$data['images'][] = array(
								'popup' => $this->image->resize($image['image'], 600, 600),
								'thumb' => $this->image->resize($image['image'], 600, 600),
							);
						}
					} else {
						$data['images'][] = array(
							'popup' => $this->image->resize('no_image.jpg', 600, 600),
							'thumb' => $this->image->resize('no_image.jpg', 600, 600),
						);
					}
					
					$data['price'] = calculate_tax($data['price'], $data['tax_value'], $data['tax_type']);
					
					if ($data['discount']) {
						$data['discount'] = calculate_tax($data['discount'], $data['tax_value'], $data['tax_type']);
					} else {
						$data['discount'] = false;
					}
					
					$this->load->library('weight');
					
					$data['weight'] = $this->weight->format($data['weight'], $data['weight_class_id']);
					
					$href = ($path != '') ? 'product/'.$path.$data['slug'] : 'product/'.$data['slug'];
					$href_title = ($path != '') ? $path.$data['slug'] : $data['slug'];

					$data['discounts'] = array();
					$discounts = $this->product_model->get_product_discounts($data['product_id']);
					
					foreach ($discounts as $discount) {
						$data['discounts'][] = array(
							'quantity' => $discount['quantity'],
							'price' => calculate_tax($discount['price'], $data['tax_value'], $data['tax_type'])
						);
					}
					
					$data['review_count'] = $this->db
					->select('review_id')
					->where('active', 1)
					->count_all_results('review');
					
					$data['related_products'] = [];
					
					foreach ($this->product_model->get_product_related($data['product_id']) as $related_product) {
						$path2 = '';
			
						if ($category = $this->category_model->get_category($related_product['category_id'])) {
							if ($category['path_id']) {
								$category_ids = explode('-', $category['path_id']);
								foreach ($category_ids as $category_id) {
									if ($category_path = $this->category_model->get_category($category_id)) {
										$path2 .= $category_path['slug'].'/';
									}
								}
							}
							
							$path2 .= $category['slug'].'/';
						}
						
							$name = strip_tags(html_entity_decode($related_product['name'], ENT_QUOTES, 'UTF-8'));
						
						$path2 .= $related_product['slug'];

						//get sale volume, rating
						$this->load->model('order_model');
						$quantity_sale = $this->order_model->get_sum_sale($related_product['product_id']);
						$ratings = $this->order_model->get_rating_values($related_product['product_id']);

						$data['related_products'][] = array(
							'product_id' => $related_product['product_id'],
							'thumb' => $this->image->resize($related_product['image'] ? $related_product['image'] : 'no_image.jpg', 350, 350),
							'name' => $name,
							'description' => substr(strip_tags(html_entity_decode($related_product['description'], ENT_QUOTES, 'UTF-8')), 0, 100).'...',
							'price' => calculate_tax($related_product['price'], $related_product['tax_value'], $related_product['tax_type']),
							'discount' => (float)$related_product['discount'] ? calculate_tax($related_product['discount'], $related_product['tax_value'], $related_product['tax_type']) : false,
							'discount_percent' => (float)$related_product['discount'] ? ceil(($related_product['price']-$related_product['discount'])/$related_product['price']*100).'%' : false,
							'rating' => $related_product['rating'],
							'reviews' => (int)$related_product['reviews'],
							'href' => site_url('product/'.$path2),
							'quantity' => (int)$related_product['quantity'],
							'count_comment' => $ratings['count'],
							'rating_value' => $ratings['value'],
							'quantity_sale' => $quantity_sale,
							'href_title' => $path2,
							'specification' => substr(strip_tags(html_entity_decode($related_product['specification'], ENT_QUOTES, 'UTF-8')), 0, 100).'...',
							'whatisinthebox' => substr(strip_tags(html_entity_decode($related_product['whatisinthebox'], ENT_QUOTES, 'UTF-8')), 0, 100).'...'
						);
					}
					if(count($data['related_products']) == 1) {
						$product_related = $this->product_model->get_product_by_id($data['product_id']);

						foreach ($product_related as $product) {
							$path5 = '';
		
							if ($category = $this->category_model->get_category($product['category_id'])) {
								if ($category['path_id']) {
									$category_ids = explode('-', $category['path_id']);
									foreach ($category_ids as $category_id) {
										if ($category_path = $this->category_model->get_category($category_id)) {
											$path5 .= $category_path['slug'].'/';
										}
									}
								}
								
								$path5 .= $category['slug'].'/';
							}
							
								$name = strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'));
							
							$path5 .= $product['slug'];


							$href = ($path5 != '') ? 'product/'.$path5.$product['slug'] : 'product/'.$product['slug'];
							$name = strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'));
							$this->load->model('order_model');
							$ratings = $this->order_model->get_rating_values($product['product_id']);

							$products = array(
								'product_id' => $product['product_id'],
								'thumb' => $this->image->resize($product['image'] ? $product['image'] : 'no_image.jpg', 180, 180),
								'name' => $name,
								'description' => substrwords(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 500),
								'price' => calculate_tax($product['price'], $product['tax_value'], $product['tax_type']),
								'discount' => (float)$product['discount'] ? calculate_tax($product['discount'], $product['tax_value'], $product['tax_type']) : false,
								'quantity' => (int)$product['quantity'],
								'wholesaler' => ($product['wholesaler'] > 1) ? true : false,
								'discount_percent' => (float)$product['discount'] ? ceil(($product['price']-$product['discount'])/$product['price']*100).'%' : false,
								'href' => site_url('product/'.$path5),
								'count_comment' => $ratings['count'],
								'rating_value' => $ratings['value'],
								'quantity_sale' => $this->order_model->get_sum_sale($product['product_id']),
								'href_title' => $path5,
								'specification' => substr(strip_tags(html_entity_decode($product['specification'], ENT_QUOTES, 'UTF-8')), 0, 100).'...',
								'whatisinthebox' => substr(strip_tags(html_entity_decode($product['whatisinthebox'], ENT_QUOTES, 'UTF-8')), 0, 100).'...'
							);
							$data['related_products'][] = $products;
						}
					}
					
					$data['minimum'] = $data['minimum'] ? (int)$data['minimum'] : 1;
					
					// check stock from inventory system
					// $this->load->model('api/stocks_model', 'api_stocks_model');
					// $data['quantity'] = $this->api_stocks_model->get_stock_balance($data['inventory_product_id']);

					$this->load->model('product_model');
					$quantity = $this->product_model->get_qty_product($data['product_id']);
					$data['quantity'] = $quantity['qty'];
					
					$this->load->model('product_view_model');
					$this->product_view_model->add($data['product_id']);
					
					$this->product_model->update_view($data['product_id']);

					if($this->user->user_id() > 0) {
						$this->load->model('product_view_user_model');
						$this->product_view_user_model->add($data['product_id'], $this->user->user_id());
					}
					
					//get sale volume, rating
					$this->load->model('order_model');
					$data['quantity_sale'] = $this->order_model->get_sum_sale($data['product_id']);
					$ratings = $this->order_model->get_rating_values($data['product_id']);
					$data['count_comment'] = $ratings['count'];
					$data['rating_value'] = $ratings['value'];
					$comments = $this->order_model->get_comments($data['product_id']);
					foreach($comments as $comment) {
						$comment['image'] = $this->image->resize($comment['image'] ? $comment['image'] : 'no_image.jpg', 25, 25);
					}
					$data['comments'] = $comments;
					$data['href_title'] = $href_title;

					$data['product_histories'] = [];

					if($this->user->user_id() > 0) {
						$producthistories = $this->product_model->get_product_view_history($this->user->user_id(),$data['product_id']);
						foreach($producthistories as $histories) {
							$product_history = $this->product_model->get_product_by_id($histories['product_id']);

							foreach ($product_history as $product) {
								$path3 = '';
			
								if ($category = $this->category_model->get_category($product['category_id'])) {
									if ($category['path_id']) {
										$category_ids = explode('-', $category['path_id']);
										foreach ($category_ids as $category_id) {
											if ($category_path = $this->category_model->get_category($category_id)) {
												$path3 .= $category_path['slug'].'/';
											}
										}
									}
									
									$path3 .= $category['slug'].'/';
								}
								
									$name = strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'));
								
								$path3 .= $product['slug'];


								$href = ($path3 != '') ? 'product/'.$path3.$product['slug'] : 'product/'.$product['slug'];
								$name = strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'));
								$this->load->model('order_model');
								$ratings = $this->order_model->get_rating_values($product['product_id']);
					
								$products = array(
									'product_id' => $product['product_id'],
									'thumb' => $this->image->resize($product['image'] ? $product['image'] : 'no_image.jpg', 180, 180),
									'name' => $name,
									'description' => substrwords(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 500),
									'price' => calculate_tax($product['price'], $product['tax_value'], $product['tax_type']),
									'discount' => (float)$product['discount'] ? calculate_tax($product['discount'], $product['tax_value'], $product['tax_type']) : false,
									'quantity' => (int)$product['quantity'],
									'wholesaler' => ($product['wholesaler'] > 1) ? true : false,
									'discount_percent' => (float)$product['discount'] ? ceil(($product['price']-$product['discount'])/$product['price']*100).'%' : false,
									'href' => site_url('product/'.$path3),
									'count_comment' => $ratings['count'],
									'rating_value' => $ratings['value'],
									'quantity_sale' => $this->order_model->get_sum_sale($product['product_id']),
									'href_title' => $path3,
									'specification' => substr(strip_tags(html_entity_decode($product['specification'], ENT_QUOTES, 'UTF-8')), 0, 100).'...',
									'whatisinthebox' => substr(strip_tags(html_entity_decode($product['whatisinthebox'], ENT_QUOTES, 'UTF-8')), 0, 100).'...'
								);
								$data['product_histories'][] = $products;
							}
						}
						if(count($data['product_histories']) == 1) {
							$product_history = $this->product_model->get_product_by_id($data['product_id']);

							foreach ($product_history as $product) {
								$path6 = '';
			
								if ($category = $this->category_model->get_category($product['category_id'])) {
									if ($category['path_id']) {
										$category_ids = explode('-', $category['path_id']);
										foreach ($category_ids as $category_id) {
											if ($category_path = $this->category_model->get_category($category_id)) {
												$path6 .= $category_path['slug'].'/';
											}
										}
									}
									
									$path6 .= $category['slug'].'/';
								}
								
									$name = strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'));
								
								$path6 .= $product['slug'];


								$href = ($path6 != '') ? 'product/'.$path6.$product['slug'] : 'product/'.$product['slug'];
								$name = strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'));
								$this->load->model('order_model');
								$ratings = $this->order_model->get_rating_values($product['product_id']);
					
								$products = array(
									'product_id' => $product['product_id'],
									'thumb' => $this->image->resize($product['image'] ? $product['image'] : 'no_image.jpg', 180, 180),
									'name' => $name,
									'description' => substrwords(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 500),
									'price' => calculate_tax($product['price'], $product['tax_value'], $product['tax_type']),
									'discount' => (float)$product['discount'] ? calculate_tax($product['discount'], $product['tax_value'], $product['tax_type']) : false,
									'quantity' => (int)$product['quantity'],
									'wholesaler' => ($product['wholesaler'] > 1) ? true : false,
									'discount_percent' => (float)$product['discount'] ? ceil(($product['price']-$product['discount'])/$product['price']*100).'%' : false,
									'href' => site_url('product/'.$path6),
									'count_comment' => $ratings['count'],
									'rating_value' => $ratings['value'],
									'quantity_sale' => $this->order_model->get_sum_sale($product['product_id']),
									'href_title' => $path6,
									'specification' => substr(strip_tags(html_entity_decode($product['specification'], ENT_QUOTES, 'UTF-8')), 0, 100).'...',
									'whatisinthebox' => substr(strip_tags(html_entity_decode($product['whatisinthebox'], ENT_QUOTES, 'UTF-8')), 0, 100).'...'
								);
								$data['product_histories'][] = $products;
							}
						}
					}
					$data['mobile'] = $this->agent->is_mobile();

					$this->load
					->title($data['meta_title'], $this->config->item('site_name'))
					->metadata('description', $data['meta_description'])
					->metadata('keyword', $data['meta_keyword'])
					->breadcrumb($data['name'], site_url($href))
					->view('product', $data);
				} else {
					show_404();
				}
				
			} else {
				show_404();
			}
		}
	}
	
	public function review()
	{
		check_ajax();
		
		$limit = 10;
		
		if ($this->input->get('page')) {
			$page = $this->input->get('page');
		} else {
			$page = 1;
		}
		
		$start = ($page -1) * $limit;
		
		$reviews = $this->db
		->select('r.review_id, r.text, r.quality, r.accuracy, r.date_added, u.name as author, u.image')
		->join('user u', 'u.user_id = r.user_id', 'left')
		->from('review r')
		->where('r.product_id', (int)$this->input->get('product_id'))
		->limit($limit, $start)
		->order_by('date_added', 'desc')
		->get()
		->result_array();
		
		$data['reviews'] = array();
			
		foreach ($reviews as $review) {
			if ($review['image']) {
				$review['image'] = $this->image->resize($review['image'], 80, 80);
			} else {
				$review['image'] = $this->image->resize('user.png', 80, 80);
			}
			
			$review['date_added'] = format_time_ago($review['date_added']);
			
			$data['reviews'][] = $review;
		}
		
		$num_rows = $this->db
		->select('review_id')
		->where('product_id', (int)$this->input->get('product_id'))
		->count_all_results('review');
		
		if ($page < ceil($num_rows/$limit)) {
			$page = $page+1;
		
			$data['next'] = site_url('product/review?product_id='.$this->input->get('product_id').'&page='.$page);
		} else {
			$data['next'] = false;
		}
		
		
		return $this->load->layout(false)->view('product_review', $data);
	}
	
	public function comment()
	{
		check_ajax();
		
		$this->load->model('comment_model');
		
		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('comment', 'Komentar', 'trim|required|min_length[16]|max_length[255]');
			
			$json = array();
			
			if ($this->form_validation->run() == true) {
				$this->comment_model->insert(array(
					'text' => htmlentities($this->input->post('comment'), ENT_QUOTES, 'UTF-8'),
					'parent_id' => $this->input->post('parent_id'),
					'product_id' => $this->input->post('product_id'),
					'user_id' => $this->user->user_id(),
					'active' => (int)$this->config->item('comment_approve'),
					'date_added' => date('Y-m-d H:i:s', time())
				));
				
				if ($this->config->item('comment_approve')) {
					$json['success'] = 'Komentar kamu telah berhasil dikirim!';
				} else {
					$json['success'] = 'Komentar sudah dikirim namun membutuhkan validasi oleh admin sebelum ditampilkan!';
				}
			} else {
				if (form_error('comment') != '') {
					$json['error']['comment'] = form_error('comment');
				}
			}
			
			$this->output->set_output(json_encode($json));
		} else {
			$limit = 10;
			
			if ($this->input->get('page')) {
				$page = $this->input->get('page');
			} else {
				$page = 1;
			}
			
			$start = ($page -1) * $limit;
			
			$comments = $this->comment_model->get_comments(array(
				'product_id' => (int)$this->input->get('product_id'),
				'parent_id' => 0,
				'limit' => $limit,
				'start' => $start,
			));
			
			$data['comments'] = array();
				
			foreach ($comments as $comment) {
				if ($comment['image']) {
					$comment['image'] = $this->image->resize($comment['image'], 64, 64);
				} else {
					$comment['image'] = $this->image->resize('user.png', 64, 64);
				}
				
				$comment['text'] = nl2br($comment['text']);
				$comment['date_added'] = format_time_ago($comment['date_added']);
				
				$childs = array();
				$comment_child = $this->comment_model->get_comments(array(
					'product_id' => (int)$this->input->get('product_id'),
					'parent_id' => (int)$comment['comment_id']
				));
				
				foreach ($comment_child as $child) {
					if ($child['image']) {
						$child['image'] = $this->image->resize($child['image'], 64, 64);
					} else {
						$child['image'] = $this->image->resize('user.png', 64, 64);
					}
					
					$child['text'] = nl2br($child['text']);
					
					$child['date_added'] = format_time_ago($child['date_added']);
					
					$childs[] = $child;
				}
				
				$comment['child'] = $childs;
			
				$data['comments'][] = $comment;
			}
			
			$num_rows = $this->comment_model->count_comments(array(
				'product_id' => (int)$this->input->get('product_id'),
				'parent_id' => 0,
			));
			
			if ($page < ceil($num_rows/$limit)) {
				$page = $page+1;
			
				$data['next'] = site_url('product/comment?product_id='.$this->input->get('product_id').'&page='.$page);
			} else {
				$data['next'] = false;
			}
			
			return $this->load->layout(false)->view('product_comment', $data);
		}
	}
	
	/**
	 * Quick View
	 * 
	 * @access public
	 * @return void
	 */
	public function quick_view()
	{
		$data = $this->product_model->get_product($this->input->get('product_id'));
		
		if ($data) {
			$data['category'] = false;
			$data['category_link'] = false;
			$path = '';
			$category = $this->category_model->get_category($data['category_id']);
			
			if ($category) {
				$data['category'] = $category['name'];
				$path_ids = explode('-', $category['path_id']);
				
				foreach ($path_ids as $category_id) {
					if ($category_path = $this->category_model->get($category_id)) {
						$path .= $category_path['slug'].'/';
						$data['category'] = $category_path['name'];
					}
				}
				
				$path .= $category['slug'].'/';
				$data['category_link'] = site_url('category/'.$path);
			}
			
			$images = $this->product_model->get_product_images($data['product_id']);
					
			if (isset($images[0])) {
				$data['thumb'] = $this->image->resize($images[0]['image'], 500, 500);
			} else {
				$data['thumb'] = $this->image->resize('no_image.jpg', 500, 500);
			}
			
			$data['price'] = calculate_tax($data['price'], $data['tax_value'], $data['tax_type']);
					
			if ($data['discount']) {
				$data['discount'] = calculate_tax($data['discount'], $data['tax_value'], $data['tax_type']);
			} else {
				$data['discount'] = false;
			}
					
			$this->load->library('weight');
			
			$data['weight'] = $this->weight->format($data['weight'], $data['weight_class_id']);
			$data['href'] = site_url('product/'.$path.$data['slug']);
			$data['minimum'] = $data['minimum'] ? (int)$data['minimum'] : 1;
			
			$this->load->model('order_model');
			$ratings = $this->order_model->get_rating_values($data['product_id']);

			$data['count_comment'] = $ratings['count'];
			$data['rating_value'] = $ratings['value'];
			$data['quantity_sale'] = $this->order_model->get_sum_sale($data['product_id']);

			$quantity = $this->product_model->get_qty_product($data['product_id']);
			$data['quantitystok'] = (int)$quantity['qty'];

			$this->output->set_output($this->load->layout(false)->view('product_quick_view', $data, true));
		}
	}
}