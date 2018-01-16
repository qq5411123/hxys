<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_Calculation_ExceptionHandler
{
	public function __construct()
	{
		set_error_handler(array('PHPExcel_Calculation_Exception', 'errorHandlerCallback'), 30719);
	}

	public function __destruct()
	{
		restore_error_handler();
	}
}


?>
