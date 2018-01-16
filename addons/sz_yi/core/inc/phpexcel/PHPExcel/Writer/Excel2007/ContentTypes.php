<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_Writer_Excel2007_ContentTypes extends PHPExcel_Writer_Excel2007_WriterPart
{
	public function writeContentTypes(PHPExcel $pPHPExcel = NULL, $includeCharts = false)
	{
		$objWriter = NULL;

		if ($this->getParentWriter()->getUseDiskCaching()) {
			$objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
		}
		else {
			$objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_MEMORY);
		}

		$objWriter->startDocument('1.0', 'UTF-8', 'yes');
		$objWriter->startElement('Types');
		$objWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/package/2006/content-types');
		$this->_writeOverrideContentType($objWriter, '/xl/theme/theme1.xml', 'application/vnd.openxmlformats-officedocument.theme+xml');
		$this->_writeOverrideContentType($objWriter, '/xl/styles.xml', 'application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml');
		$this->_writeDefaultContentType($objWriter, 'rels', 'application/vnd.openxmlformats-package.relationships+xml');
		$this->_writeDefaultContentType($objWriter, 'xml', 'application/xml');
		$this->_writeDefaultContentType($objWriter, 'vml', 'application/vnd.openxmlformats-officedocument.vmlDrawing');
		$this->_writeOverrideContentType($objWriter, '/xl/workbook.xml', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml');
		$this->_writeOverrideContentType($objWriter, '/docProps/app.xml', 'application/vnd.openxmlformats-officedocument.extended-properties+xml');
		$this->_writeOverrideContentType($objWriter, '/docProps/core.xml', 'application/vnd.openxmlformats-package.core-properties+xml');
		$customPropertyList = $pPHPExcel->getProperties()->getCustomProperties();

		if (!empty($customPropertyList)) {
			$this->_writeOverrideContentType($objWriter, '/docProps/custom.xml', 'application/vnd.openxmlformats-officedocument.custom-properties+xml');
		}

		$sheetCount = $pPHPExcel->getSheetCount();
		$i = 0;

		while ($i < $sheetCount) {
			$this->_writeOverrideContentType($objWriter, '/xl/worksheets/sheet' . ($i + 1) . '.xml', 'application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml');
			++$i;
		}

		$this->_writeOverrideContentType($objWriter, '/xl/sharedStrings.xml', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml');
		$chart = 1;
		$i = 0;

		while ($i < $sheetCount) {
			$drawings = $pPHPExcel->getSheet($i)->getDrawingCollection();
			$drawingCount = count($drawings);
			$chartCount = ($includeCharts ? $pPHPExcel->getSheet($i)->getChartCount() : 0);
			if ((0 < $drawingCount) || (0 < $chartCount)) {
				$this->_writeOverrideContentType($objWriter, '/xl/drawings/drawing' . ($i + 1) . '.xml', 'application/vnd.openxmlformats-officedocument.drawing+xml');
			}

			if (0 < $chartCount) {
				$c = 0;

				while ($c < $chartCount) {
					$this->_writeOverrideContentType($objWriter, '/xl/charts/chart' . $chart++ . '.xml', 'application/vnd.openxmlformats-officedocument.drawingml.chart+xml');
					++$c;
				}
			}

			++$i;
		}

		$i = 0;

		while ($i < $sheetCount) {
			if (0 < count($pPHPExcel->getSheet($i)->getComments())) {
				$this->_writeOverrideContentType($objWriter, '/xl/comments' . ($i + 1) . '.xml', 'application/vnd.openxmlformats-officedocument.spreadsheetml.comments+xml');
			}

			++$i;
		}

		$aMediaContentTypes = array();
		$mediaCount = $this->getParentWriter()->getDrawingHashTable()->count();
		$i = 0;

		while ($i < $mediaCount) {
			$extension = '';
			$mimeType = '';

			if ($this->getParentWriter()->getDrawingHashTable()->getByIndex($i) instanceof PHPExcel_Worksheet_Drawing) {
				$extension = strtolower($this->getParentWriter()->getDrawingHashTable()->getByIndex($i)->getExtension());
				$mimeType = $this->_getImageMimeType($this->getParentWriter()->getDrawingHashTable()->getByIndex($i)->getPath());
			}
			else {
				if ($this->getParentWriter()->getDrawingHashTable()->getByIndex($i) instanceof PHPExcel_Worksheet_MemoryDrawing) {
					$extension = strtolower($this->getParentWriter()->getDrawingHashTable()->getByIndex($i)->getMimeType());
					$extension = explode('/', $extension);
					$extension = $extension[1];
					$mimeType = $this->getParentWriter()->getDrawingHashTable()->getByIndex($i)->getMimeType();
				}
			}

			if (!isset($aMediaContentTypes[$extension])) {
				$aMediaContentTypes[$extension] = $mimeType;
				$this->_writeDefaultContentType($objWriter, $extension, $mimeType);
			}

			++$i;
		}

		$sheetCount = $pPHPExcel->getSheetCount();
		$i = 0;

		while ($i < $sheetCount) {
			if (0 < count($pPHPExcel->getSheet()->getHeaderFooter()->getImages())) {
				foreach ($pPHPExcel->getSheet()->getHeaderFooter()->getImages() as $image) {
					if (!isset($aMediaContentTypes[strtolower($image->getExtension())])) {
						$aMediaContentTypes[strtolower($image->getExtension())] = $this->_getImageMimeType($image->getPath());
						$this->_writeDefaultContentType($objWriter, strtolower($image->getExtension()), $aMediaContentTypes[strtolower($image->getExtension())]);
					}
				}
			}

			++$i;
		}

		$objWriter->endElement();
		return $objWriter->getData();
	}

	private function _getImageMimeType($pFile = '')
	{
		if (PHPExcel_Shared_File::file_exists($pFile)) {
			$image = getimagesize($pFile);
			return image_type_to_mime_type($image[2]);
		}

		throw new PHPExcel_Writer_Exception('File ' . $pFile . ' does not exist');
	}

	private function _writeDefaultContentType(PHPExcel_Shared_XMLWriter $objWriter = NULL, $pPartname = '', $pContentType = '')
	{
		if (($pPartname != '') && ($pContentType != '')) {
			$objWriter->startElement('Default');
			$objWriter->writeAttribute('Extension', $pPartname);
			$objWriter->writeAttribute('ContentType', $pContentType);
			$objWriter->endElement();
			return NULL;
		}

		throw new PHPExcel_Writer_Exception('Invalid parameters passed.');
	}

	private function _writeOverrideContentType(PHPExcel_Shared_XMLWriter $objWriter = NULL, $pPartname = '', $pContentType = '')
	{
		if (($pPartname != '') && ($pContentType != '')) {
			$objWriter->startElement('Override');
			$objWriter->writeAttribute('PartName', $pPartname);
			$objWriter->writeAttribute('ContentType', $pContentType);
			$objWriter->endElement();
			return NULL;
		}

		throw new PHPExcel_Writer_Exception('Invalid parameters passed.');
	}
}

?>
