<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_Settings
{
	const PCLZIP = 'PHPExcel_Shared_ZipArchive';
	const ZIPARCHIVE = 'ZipArchive';
	const CHART_RENDERER_JPGRAPH = 'jpgraph';
	const PDF_RENDERER_TCPDF = 'tcPDF';
	const PDF_RENDERER_DOMPDF = 'DomPDF';
	const PDF_RENDERER_MPDF = 'mPDF';

	static private $_chartRenderers = array(self::CHART_RENDERER_JPGRAPH);
	static private $_pdfRenderers = array(self::PDF_RENDERER_TCPDF, self::PDF_RENDERER_DOMPDF, self::PDF_RENDERER_MPDF);
	static private $_zipClass = self::ZIPARCHIVE;
	static private $_chartRendererName;
	static private $_chartRendererPath;
	static private $_pdfRendererName;
	static private $_pdfRendererPath;

	static public function setZipClass($zipClass)
	{
		if (($zipClass === self::PCLZIP) || ($zipClass === self::ZIPARCHIVE)) {
			self::$_zipClass = $zipClass;
			return true;
		}

		return false;
	}

	static public function getZipClass()
	{
		return self::$_zipClass;
	}

	static public function getCacheStorageMethod()
	{
		return PHPExcel_CachedObjectStorageFactory::getCacheStorageMethod();
	}

	static public function getCacheStorageClass()
	{
		return PHPExcel_CachedObjectStorageFactory::getCacheStorageClass();
	}

	static public function setCacheStorageMethod($method = PHPExcel_CachedObjectStorageFactory::cache_in_memory, $arguments = array())
	{
		return PHPExcel_CachedObjectStorageFactory::initialize($method, $arguments);
	}

	static public function setLocale($locale = 'en_us')
	{
		return PHPExcel_Calculation::getInstance()->setLocale($locale);
	}

	static public function setChartRenderer($libraryName, $libraryBaseDir)
	{
		if (!self::setChartRendererName($libraryName)) {
			return false;
		}

		return self::setChartRendererPath($libraryBaseDir);
	}

	static public function setChartRendererName($libraryName)
	{
		if (!in_array($libraryName, self::$_chartRenderers)) {
			return false;
		}

		self::$_chartRendererName = $libraryName;
		return true;
	}

	static public function setChartRendererPath($libraryBaseDir)
	{
		if ((file_exists($libraryBaseDir) === false) || (is_readable($libraryBaseDir) === false)) {
			return false;
		}

		self::$_chartRendererPath = $libraryBaseDir;
		return true;
	}

	static public function getChartRendererName()
	{
		return self::$_chartRendererName;
	}

	static public function getChartRendererPath()
	{
		return self::$_chartRendererPath;
	}

	static public function setPdfRenderer($libraryName, $libraryBaseDir)
	{
		if (!self::setPdfRendererName($libraryName)) {
			return false;
		}

		return self::setPdfRendererPath($libraryBaseDir);
	}

	static public function setPdfRendererName($libraryName)
	{
		if (!in_array($libraryName, self::$_pdfRenderers)) {
			return false;
		}

		self::$_pdfRendererName = $libraryName;
		return true;
	}

	static public function setPdfRendererPath($libraryBaseDir)
	{
		if ((file_exists($libraryBaseDir) === false) || (is_readable($libraryBaseDir) === false)) {
			return false;
		}

		self::$_pdfRendererPath = $libraryBaseDir;
		return true;
	}

	static public function getPdfRendererName()
	{
		return self::$_pdfRendererName;
	}

	static public function getPdfRendererPath()
	{
		return self::$_pdfRendererPath;
	}
}

if (!defined('PHPEXCEL_ROOT')) {
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../');
	require PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php';
}

?>
