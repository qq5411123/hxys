<?php
// 唐上美联佳网络科技有限公司(技术支持)
interface PHPExcel_Reader_IReader
{
	public function canRead($pFilename);

	public function load($pFilename);
}


?>
