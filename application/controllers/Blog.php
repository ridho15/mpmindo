<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends MY_Controller
{
	private $limit = 10;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('blog_article_model');
		$this->load->model('blog_category_model');
	}
	
	public function index()
	{
		$data['breadcrumbs'][] = array(
			'text' => 'Blog',
			'href' => site_url('blog')
		);
					
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		
		$data['categories'] = $this->populate_categories();
		$data['archives'] = $this->populate_archives();
		
		$params['start'] = ($page -1) * $this->limit;
		$params['limit'] = $this->limit;
		
		$data['articles'] = $this->populate_articles($params);
		$data['pagination'] = $this->create_pagination('blog?limit='.$this->limit, $params);
		
		$this->load
		->title('Blog', $this->config->item('site_name'))
		->metadata('description', $this->config->item('meta_description'))
		->metadata('keywords', $this->config->item('meta_keyword'))
		->metadata('author', $this->config->item('site_name'))
		->view('blog', $data);
	}
	
	public function category($blog_category_id = null)
	{
		$data['breadcrumbs'][] = array(
			'text' => 'Blog',
			'href' => site_url('blog')
		);
			
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		
		$category = $this->blog_category_model->get_category($blog_category_id);
		
		if ($category) {
			$data['breadcrumbs'][] = array(
				'text' => $category['name'],
				'href' => site_url('blog/category/'.$category['slug'])
			);
		
			$data['categories'] = $this->populate_categories();
			$data['archives'] = $this->populate_archives();
			
			$params['start'] = ($page -1) * $this->limit;
			$params['limit'] = $this->limit;
			$params['blog_category_id'] = $blog_category_id;
			
			$data['articles'] = $this->populate_articles($params);
			$data['pagination'] = $this->create_pagination($category['slug'].'?limit='.$this->limit, $params);
			
			foreach ($category as $key => $value) {
				$data[$key] = $value;
			}
			
			if ($category['image']) {
				$data['image'] = $this->image->resize($category['image'], 600);
			} else {
				$data['image'] = false;
			}
			
			$this->load
			->title($category['seo_title'], $this->config->item('site_name'))
			->metadata('description', $category['meta_description'])
			->metadata('keywords', $category['meta_keyword'])
			->metadata('author', $this->config->item('site_name'))
			->view('blog_category', $data);	
		} else {
			show_404();
		}
	}
	
	public function archive($year = null, $month = null)
	{
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		
		$data['categories'] = $this->populate_categories();
		$data['archives'] = $this->populate_archives();
		
		$params['start'] = ($page -1) * $this->limit;
		$params['limit'] = $this->limit;
		$params['year'] = $year;
		$params['month'] = $month;
		
		$data['articles'] = $this->populate_articles($params);
		$data['pagination'] = $this->create_pagination('blog/'.$year.'/'.$month.'?limit='.$this->limit, $params);
		
		$this->load
		->title('Arsip Blog', $this->config->item('site_name'))
		->metadata('description', $this->config->item('meta_description'))
		->metadata('keywords', $this->config->item('meta_keyword'))
		->metadata('author', $this->config->item('site_name'))
		->view('blog_archive', $data);
	}
	
	public function article($blog_article_id = null)
	{
		$data['breadcrumbs'][] = array(
			'text' => 'Blog',
			'href' => site_url('blog')
		);
		
		if ($article = $this->blog_article_model->get_article($blog_article_id)) {
			foreach ($article as $key => $value) {
				$data[$key] = $value;
			}
			
			$data['breadcrumbs'][] = array(
				'text' => $article['title'],
				'href' => site_url('blog/'.$article['slug'])
			);
			
			$data['date_added'] = date('d M Y', strtotime($article['date_added']));
			
			if ($article['image']) {
				$data['image'] = $this->image->resize($article['image'], 600);
			} else {
				$data['image'] = false;
			}
			
			$data['categories'] = $this->populate_categories();
			$data['archives'] = $this->populate_archives();
			
			$this->load
			->title($article['seo_title'], $this->config->item('site_name'))
			->metadata('description', $article['meta_description'])
			->metadata('keywords', $article['meta_keyword'])
			->metadata('author', $article['author'])
			->view('blog_article', $data);
		} else {
			show_404();
		}
	}
	
	private function populate_articles($params = array())
	{
		$articles = array();
		$result = $this->blog_article_model->get_articles($params);
		
		$this->load->library('image');
		
		foreach ($result as $article) {
			if ($article['image']) {
				$image = $this->image->resize($article['image'], 600);
			} else {
				$image = false;
			}
			
			$articles[] = array(
				'blog_article_id' => $article['blog_article_id'],
				'title' => $article['title'],
				'image' => $image,
				'description' => $article['description'],
				'href' => site_url('blog/'.$article['slug']),
				'date_added' => date('d M Y', strtotime($article['date_added'])),
				'views' => $article['views'],
				'author' => $article['author'],
			);
		}
		
		return $articles;
	}
	
	private function populate_categories()
	{
		$categories = array();
		
		foreach ($this->blog_category_model->get_category_menus() as $category) {
			$categories[] = array(
				'text' => $category['name'],
				'href' => site_url('blog/category/'.$category['slug']),
				'article_num' => $category['article_num'],
			);
		}
		
		return $categories;
	}
	
	private function populate_archives()
	{
		$archives = array();
		
		foreach ($this->blog_category_model->get_archive() as $archive) {
			$archives[] = array(
				'text' => $archive['date_str'],
				'href' => site_url('blog/'.$archive['year'].'/'.$archive['month']),
				'article_num' => $archive['article_num'],
			);
		}
		
		return $archives;
	}
	
	private function create_pagination($base, $params)
	{
		$config = array(
			'base_url'				=> site_url($base),
			'total_rows'			=> $this->blog_article_model->get_articles($params, true),
			'per_page'				=> $this->limit,
			'page_query_string'		=> true,
			'query_string_segment'	=> 'page',
			'full_tag_open'			=> '<ul class="pagination">',
			'full_tag_close'		=> '</ul>',
			'num_tag_open'			=> '<li>',
			'num_tag_close'			=> '</li>',
			'cur_tag_open'			=> '<li class="active"><a>',
			'cur_tag_close'			=> '</a></li>',
			'first_tag_open'		=> '<li>',
			'first_tag_close'		=> '</li>',
			'last_tag_open'			=> '<li>',
			'last_tag_close'		=> '</li>',
			'prev_tag_open'			=> '<li>',
			'prev_tag_close'		=> '</li>',
			'next_tag_open'			=> '<li>',
			'next_tag_close'		=> '</li>',
			'use_page_numbers'		=> true
		);
		
		$this->load->library('pagination');
		
		$this->pagination->initialize($config);
		
		return $this->pagination->create_links();
	}
}