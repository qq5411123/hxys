<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace Qiniu\Http;

final class Request
{
	public $url;
	public $headers;
	public $body;
	public $method;

	public function __construct($method, $url, array $headers = array(), $body = NULL)
	{
		$this->method = strtoupper($method);
		$this->url = $url;
		$this->headers = $headers;
		$this->body = $body;
	}
}


?>
