<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_RichText_Run extends PHPExcel_RichText_TextElement implements PHPExcel_RichText_ITextElement
{
	private $_font;

	public function __construct($pText = '')
	{
		$this->setText($pText);
		$this->_font = new PHPExcel_Style_Font();
	}

	public function getFont()
	{
		return $this->_font;
	}

	public function setFont(PHPExcel_Style_Font $pFont = NULL)
	{
		$this->_font = $pFont;
		return $this;
	}

	public function getHashCode()
	{
		return md5($this->getText() . $this->_font->getHashCode() . 'PHPExcel_RichText_Run');
	}

	public function __clone()
	{
		$vars = get_object_vars($this);

		foreach ($vars as $key => $value) {
			if (is_object($value)) {
				$this->$key = clone $value;
			}
			else {
				$this->$key = $value;
			}
		}
	}
}

?>
