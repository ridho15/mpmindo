<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image
{
	private $ci;
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->ci =& get_instance();
	}
	
	/**
	 * Resize image
	 * 
	 * @access public
	 * @param string $filename
	 * @param int $width
	 * @param int $height
	 * @param bool $crop_center
	 * @return string
	 */
	public function resize($filename, $width = 0, $height = 0, $crop_center = true)
	{
		if ( ! file_exists(DIR_IMAGE.$filename) OR ! is_file(DIR_IMAGE.$filename)) return;
		
		$info = pathinfo($filename);
		$dirname = $info['dirname'];
		$file_name = $info['filename'];
		$extension = $info['extension'];
		
		$cache = $dirname != '.' ? $dirname.'/'.md5($file_name) : md5($file_name);
		$old_image = $filename;
		$new_image = 'cache/'.$cache.'-'.$width.'-'.$height.'.'.$extension;
		
		if ( ! file_exists(DIR_IMAGE.$new_image) || (filemtime(DIR_IMAGE.$old_image) > filemtime(DIR_IMAGE.$new_image))) {
			$path = '';
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
						
			foreach ($directories as $directory) {
				$path = $path.'/'.$directory;
							
				if ( ! file_exists(DIR_IMAGE.$path)) {
					@mkdir(DIR_IMAGE.$path, 0777);
				}
			}
						
			$image = new Image_Manipulator(DIR_IMAGE.$old_image);
			$image->resize($width, $height, $crop_center);
			$image->save(DIR_IMAGE.$new_image);
		}
			
		return HTTP_IMAGE.$new_image;
	}
}

class Image_Manipulator
{
	private $file;
	private $image;
	private $info;
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @param string $file
	 * @return void
	 */
	public function __construct($file)
	{
		if (file_exists($file)) {
			$this->file = $file;

			$info = getimagesize($file);

			$this->info = array(
				'width' => $info[0],
				'height' => $info[1],
				'bits' => $info['bits'],
				'mime' => $info['mime']
			);

			$this->image = $this->create($file);
		} else {
			exit('Error: Could not load image '.$file.'!');
		}
	}
	
	/**
	 * Create
	 * 
	 * @access private
	 * @param string $image
	 * @return string
	 */
	private function create($image)
	{
		$mime = $this->info['mime'];

		if ($mime == 'image/gif') {
			return imagecreatefromgif($image);
		} elseif ($mime == 'image/png') {
			return imagecreatefrompng($image);
		} elseif ($mime == 'image/jpeg') {
			return imagecreatefromjpeg($image);
		}
	}
	
	/**
	 * Save
	 * 
	 * @access public
	 * @param mixed $file
	 * @param int $quality
	 * @return void
	 */
	public function save($file, $quality = 90)
	{
		$info = pathinfo($file);

		$extension = strtolower($info['extension']);

		if (is_resource($this->image)) {
			if ($extension == 'jpeg' || $extension == 'jpg') {
				imagejpeg($this->image, $file, $quality);
			} elseif($extension == 'png') {
				imagepng($this->image, $file);
			} elseif($extension == 'gif') {
				imagegif($this->image, $file);
			}

			imagedestroy($this->image);
		}
	}
	
	/**
	 * Resize
	 * 
	 * @access public
	 * @param int $width
	 * @param int $height
	 * @param bool $crop_center
	 * @return void
	 */
	public function resize($width = 0, $height = 0, $crop_center = false)
	{
		if ( ! $this->info['width'] || !$this->info['height']) {
			return;
		}

		$xpos = 0;
		$ypos = 0;
		$scale = 1;
		
		if ($width == 0 && $height > 0) {
			$width = ($height * $this->info['width']) / $this->info['height'];
		} elseif ($width > 0 && $height == 0) {
			$height = ($width * $this->info['height']) / $this->info['width'];
		} elseif ($width == 0 && $height == 0) {
			return;
		}

		$scale_w = $width / $this->info['width'];
		$scale_h = $height / $this->info['height'];
		
		if ($crop_center) {
			$ratio_source = $this->info['width'] / $this->info['height'];
			$ratio_dest = $width / $height;
			
			if ($ratio_dest < $ratio_source) {
				$scale = $scale_h;
			} else {
				$scale = $scale_w;
			}
		} else {
			$scale = min($scale_w, $scale_h);
		}

		if ($scale == 1 && $scale_h == $scale_w && $this->info['mime'] != 'image/png') {
			return;
		}

		$new_width = (int)($this->info['width'] * $scale);
		$new_height = (int)($this->info['height'] * $scale);			
		$xpos = (int)(($width - $new_width) / 2);
		$ypos = (int)(($height - $new_height) / 2);

		$image_old = $this->image;
		$this->image = imagecreatetruecolor($width, $height);

		if (isset($this->info['mime']) && $this->info['mime'] == 'image/png') {		
			imagealphablending($this->image, false);
			imagesavealpha($this->image, true);
			$background = imagecolorallocatealpha($this->image, 255, 255, 255, 127);
			imagecolortransparent($this->image, $background);
		} else {
			$background = imagecolorallocate($this->image, 255, 255, 255);
		}

		imagefilledrectangle($this->image, 0, 0, $width, $height, $background);
		imagecopyresampled($this->image, $image_old, $xpos, $ypos, 0, 0, $new_width, $new_height, $this->info['width'], $this->info['height']);
		imagedestroy($image_old);

		$this->info['width']  = $width;
		$this->info['height'] = $height;
	}
}