<?php
// 唐上美联佳网络科技有限公司(技术支持)
class Qiniu_ImageView
{
	public $Mode;
	public $Width;
	public $Height;
	public $Quality;
	public $Format;

	public function MakeRequest($url)
	{
		$ops = array($this->Mode);

		if (!empty($this->Width)) {
			$ops[] = 'w/' . $this->Width;
		}

		if (!empty($this->Height)) {
			$ops[] = 'h/' . $this->Height;
		}

		if (!empty($this->Quality)) {
			$ops[] = 'q/' . $this->Quality;
		}

		if (!empty($this->Format)) {
			$ops[] = 'format/' . $this->Format;
		}

		return $url . '?imageView/' . implode('/', $ops);
	}
}

class Qiniu_Exif
{
	public function MakeRequest($url)
	{
		return $url . '?exif';
	}
}

class Qiniu_ImageInfo
{
	public function MakeRequest($url)
	{
		return $url . '?imageInfo';
	}
}

require_once 'auth_digest.php';

?>
