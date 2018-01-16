<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_Writer_Excel2007 extends PHPExcel_Writer_Abstract implements PHPExcel_Writer_IWriter
{
	private $_office2003compatibility = false;
	private $_writerParts = array();
	private $_spreadSheet;
	private $_stringTable = array();
	private $_stylesConditionalHashTable;
	private $_fillHashTable;
	private $_fontHashTable;
	private $_bordersHashTable;
	private $_numFmtHashTable;
	private $_drawingHashTable;

	public function __construct(PHPExcel $pPHPExcel = NULL)
	{
		$this->setPHPExcel($pPHPExcel);
		$writerPartsArray = array('stringtable' => 'PHPExcel_Writer_Excel2007_StringTable', 'contenttypes' => 'PHPExcel_Writer_Excel2007_ContentTypes', 'docprops' => 'PHPExcel_Writer_Excel2007_DocProps', 'rels' => 'PHPExcel_Writer_Excel2007_Rels', 'theme' => 'PHPExcel_Writer_Excel2007_Theme', 'style' => 'PHPExcel_Writer_Excel2007_Style', 'workbook' => 'PHPExcel_Writer_Excel2007_Workbook', 'worksheet' => 'PHPExcel_Writer_Excel2007_Worksheet', 'drawing' => 'PHPExcel_Writer_Excel2007_Drawing', 'comments' => 'PHPExcel_Writer_Excel2007_Comments', 'chart' => 'PHPExcel_Writer_Excel2007_Chart');

		foreach ($writerPartsArray as $writer => $class) {
			$this->_writerParts[$writer] = new $class($this);
		}

		$hashTablesArray = array('_stylesConditionalHashTable', '_fillHashTable', '_fontHashTable', '_bordersHashTable', '_numFmtHashTable', '_drawingHashTable');

		foreach ($hashTablesArray as $tableName) {
			$this->$tableName = new PHPExcel_HashTable();
		}
	}

	public function getWriterPart($pPartName = '')
	{
		if (($pPartName != '') && isset($this->_writerParts[strtolower($pPartName)])) {
			return $this->_writerParts[strtolower($pPartName)];
		}
	}

	public function save($pFilename = NULL)
	{
		if ($this->_spreadSheet !== NULL) {
			$this->_spreadSheet->garbageCollect();
			$originalFilename = $pFilename;
			if ((strtolower($pFilename) == 'php://output') || (strtolower($pFilename) == 'php://stdout')) {
				$pFilename = @tempnam(PHPExcel_Shared_File::sys_get_temp_dir(), 'phpxltmp');

				if ($pFilename == '') {
					$pFilename = $originalFilename;
				}
			}

			$saveDebugLog = PHPExcel_Calculation::getInstance($this->_spreadSheet)->getDebugLog()->getWriteDebugLog();
			PHPExcel_Calculation::getInstance($this->_spreadSheet)->getDebugLog()->setWriteDebugLog(false);
			$saveDateReturnType = PHPExcel_Calculation_Functions::getReturnDateType();
			PHPExcel_Calculation_Functions::setReturnDateType(PHPExcel_Calculation_Functions::RETURNDATE_EXCEL);
			$this->_stringTable = array();
			$i = 0;

			while ($i < $this->_spreadSheet->getSheetCount()) {
				$this->_stringTable = $this->getWriterPart('StringTable')->createStringTable($this->_spreadSheet->getSheet($i), $this->_stringTable);
				++$i;
			}

			$this->_stylesConditionalHashTable->addFromSource($this->getWriterPart('Style')->allConditionalStyles($this->_spreadSheet));
			$this->_fillHashTable->addFromSource($this->getWriterPart('Style')->allFills($this->_spreadSheet));
			$this->_fontHashTable->addFromSource($this->getWriterPart('Style')->allFonts($this->_spreadSheet));
			$this->_bordersHashTable->addFromSource($this->getWriterPart('Style')->allBorders($this->_spreadSheet));
			$this->_numFmtHashTable->addFromSource($this->getWriterPart('Style')->allNumberFormats($this->_spreadSheet));
			$this->_drawingHashTable->addFromSource($this->getWriterPart('Drawing')->allDrawings($this->_spreadSheet));
			$zipClass = PHPExcel_Settings::getZipClass();
			$objZip = new $zipClass();
			$ro = new ReflectionObject($objZip);
			$zipOverWrite = $ro->getConstant('OVERWRITE');
			$zipCreate = $ro->getConstant('CREATE');

			if (file_exists($pFilename)) {
				unlink($pFilename);
			}

			if ($objZip->open($pFilename, $zipOverWrite) !== true) {
				if ($objZip->open($pFilename, $zipCreate) !== true) {
					throw new PHPExcel_Writer_Exception('Could not open ' . $pFilename . ' for writing.');
				}
			}

			$objZip->addFromString('[Content_Types].xml', $this->getWriterPart('ContentTypes')->writeContentTypes($this->_spreadSheet, $this->_includeCharts));
			$objZip->addFromString('_rels/.rels', $this->getWriterPart('Rels')->writeRelationships($this->_spreadSheet));
			$objZip->addFromString('xl/_rels/workbook.xml.rels', $this->getWriterPart('Rels')->writeWorkbookRelationships($this->_spreadSheet));
			$objZip->addFromString('docProps/app.xml', $this->getWriterPart('DocProps')->writeDocPropsApp($this->_spreadSheet));
			$objZip->addFromString('docProps/core.xml', $this->getWriterPart('DocProps')->writeDocPropsCore($this->_spreadSheet));
			$customPropertiesPart = $this->getWriterPart('DocProps')->writeDocPropsCustom($this->_spreadSheet);

			if ($customPropertiesPart !== NULL) {
				$objZip->addFromString('docProps/custom.xml', $customPropertiesPart);
			}

			$objZip->addFromString('xl/theme/theme1.xml', $this->getWriterPart('Theme')->writeTheme($this->_spreadSheet));
			$objZip->addFromString('xl/sharedStrings.xml', $this->getWriterPart('StringTable')->writeStringTable($this->_stringTable));
			$objZip->addFromString('xl/styles.xml', $this->getWriterPart('Style')->writeStyles($this->_spreadSheet));
			$objZip->addFromString('xl/workbook.xml', $this->getWriterPart('Workbook')->writeWorkbook($this->_spreadSheet, $this->_preCalculateFormulas));
			$chartCount = 0;
			$i = 0;

			while ($i < $this->_spreadSheet->getSheetCount()) {
				$objZip->addFromString('xl/worksheets/sheet' . ($i + 1) . '.xml', $this->getWriterPart('Worksheet')->writeWorksheet($this->_spreadSheet->getSheet($i), $this->_stringTable, $this->_includeCharts));

				if ($this->_includeCharts) {
					$charts = $this->_spreadSheet->getSheet($i)->getChartCollection();

					if (0 < count($charts)) {
						foreach ($charts as $chart) {
							$objZip->addFromString('xl/charts/chart' . ($chartCount + 1) . '.xml', $this->getWriterPart('Chart')->writeChart($chart));
							++$chartCount;
						}
					}
				}

				++$i;
			}

			$chartRef1 = $chartRef2 = 0;
			$i = 0;

			while ($i < $this->_spreadSheet->getSheetCount()) {
				$objZip->addFromString('xl/worksheets/_rels/sheet' . ($i + 1) . '.xml.rels', $this->getWriterPart('Rels')->writeWorksheetRelationships($this->_spreadSheet->getSheet($i), $i + 1, $this->_includeCharts));
				$drawings = $this->_spreadSheet->getSheet($i)->getDrawingCollection();
				$drawingCount = count($drawings);

				if ($this->_includeCharts) {
					$chartCount = $this->_spreadSheet->getSheet($i)->getChartCount();
				}

				if ((0 < $drawingCount) || (0 < $chartCount)) {
					$objZip->addFromString('xl/drawings/_rels/drawing' . ($i + 1) . '.xml.rels', $this->getWriterPart('Rels')->writeDrawingRelationships($this->_spreadSheet->getSheet($i), $chartRef1, $this->_includeCharts));
					$objZip->addFromString('xl/drawings/drawing' . ($i + 1) . '.xml', $this->getWriterPart('Drawing')->writeDrawings($this->_spreadSheet->getSheet($i), $chartRef2, $this->_includeCharts));
				}

				if (0 < count($this->_spreadSheet->getSheet($i)->getComments())) {
					$objZip->addFromString('xl/drawings/vmlDrawing' . ($i + 1) . '.vml', $this->getWriterPart('Comments')->writeVMLComments($this->_spreadSheet->getSheet($i)));
					$objZip->addFromString('xl/comments' . ($i + 1) . '.xml', $this->getWriterPart('Comments')->writeComments($this->_spreadSheet->getSheet($i)));
				}

				if (0 < count($this->_spreadSheet->getSheet($i)->getHeaderFooter()->getImages())) {
					$objZip->addFromString('xl/drawings/vmlDrawingHF' . ($i + 1) . '.vml', $this->getWriterPart('Drawing')->writeVMLHeaderFooterImages($this->_spreadSheet->getSheet($i)));
					$objZip->addFromString('xl/drawings/_rels/vmlDrawingHF' . ($i + 1) . '.vml.rels', $this->getWriterPart('Rels')->writeHeaderFooterDrawingRelationships($this->_spreadSheet->getSheet($i)));

					foreach ($this->_spreadSheet->getSheet($i)->getHeaderFooter()->getImages() as $image) {
						$objZip->addFromString('xl/media/' . $image->getIndexedFilename(), file_get_contents($image->getPath()));
					}
				}

				++$i;
			}

			$i = 0;

			while ($i < $this->getDrawingHashTable()->count()) {
				if ($this->getDrawingHashTable()->getByIndex($i) instanceof PHPExcel_Worksheet_Drawing) {
					$imageContents = NULL;
					$imagePath = $this->getDrawingHashTable()->getByIndex($i)->getPath();

					if (strpos($imagePath, 'zip://') !== false) {
						$imagePath = substr($imagePath, 6);
						$imagePathSplitted = explode('#', $imagePath);
						$imageZip = new ZipArchive();
						$imageZip->open($imagePathSplitted[0]);
						$imageContents = $imageZip->getFromName($imagePathSplitted[1]);
						$imageZip->close();
						unset($imageZip);
					}
					else {
						$imageContents = file_get_contents($imagePath);
					}

					$objZip->addFromString('xl/media/' . str_replace(' ', '_', $this->getDrawingHashTable()->getByIndex($i)->getIndexedFilename()), $imageContents);
				}
				else {
					if ($this->getDrawingHashTable()->getByIndex($i) instanceof PHPExcel_Worksheet_MemoryDrawing) {
						ob_start();
						call_user_func($this->getDrawingHashTable()->getByIndex($i)->getRenderingFunction(), $this->getDrawingHashTable()->getByIndex($i)->getImageResource());
						$imageContents = ob_get_contents();
						ob_end_clean();
						$objZip->addFromString('xl/media/' . str_replace(' ', '_', $this->getDrawingHashTable()->getByIndex($i)->getIndexedFilename()), $imageContents);
					}
				}

				++$i;
			}

			PHPExcel_Calculation_Functions::setReturnDateType($saveDateReturnType);
			PHPExcel_Calculation::getInstance($this->_spreadSheet)->getDebugLog()->setWriteDebugLog($saveDebugLog);

			if ($objZip->close() === false) {
				throw new PHPExcel_Writer_Exception('Could not close zip file ' . $pFilename . '.');
			}

			if ($originalFilename != $pFilename) {
				if (copy($pFilename, $originalFilename) === false) {
					throw new PHPExcel_Writer_Exception('Could not copy temporary zip file ' . $pFilename . ' to ' . $originalFilename . '.');
				}

				@unlink($pFilename);
				return NULL;
			}
		}
		else {
			throw new PHPExcel_Writer_Exception('PHPExcel object unassigned.');
		}
	}

	public function getPHPExcel()
	{
		if ($this->_spreadSheet !== NULL) {
			return $this->_spreadSheet;
		}

		throw new PHPExcel_Writer_Exception('No PHPExcel assigned.');
	}

	public function setPHPExcel(PHPExcel $pPHPExcel = NULL)
	{
		$this->_spreadSheet = $pPHPExcel;
		return $this;
	}

	public function getStringTable()
	{
		return $this->_stringTable;
	}

	public function getStylesConditionalHashTable()
	{
		return $this->_stylesConditionalHashTable;
	}

	public function getFillHashTable()
	{
		return $this->_fillHashTable;
	}

	public function getFontHashTable()
	{
		return $this->_fontHashTable;
	}

	public function getBordersHashTable()
	{
		return $this->_bordersHashTable;
	}

	public function getNumFmtHashTable()
	{
		return $this->_numFmtHashTable;
	}

	public function getDrawingHashTable()
	{
		return $this->_drawingHashTable;
	}

	public function getOffice2003Compatibility()
	{
		return $this->_office2003compatibility;
	}

	public function setOffice2003Compatibility($pValue = false)
	{
		$this->_office2003compatibility = $pValue;
		return $this;
	}
}

?>
