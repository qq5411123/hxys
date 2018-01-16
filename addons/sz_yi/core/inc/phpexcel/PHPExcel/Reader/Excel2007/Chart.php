<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_Reader_Excel2007_Chart
{
	static private function _getAttribute($component, $name, $format)
	{
		$attributes = $component->attributes();

		if (isset($attributes[$name])) {
			if ($format == 'string') {
				return (string) $attributes[$name];
			}

			if ($format == 'integer') {
				return (int) $attributes[$name];
			}

			if ($format == 'boolean') {
				return (bool) (($attributes[$name] === '0') || ($attributes[$name] !== 'true')) ? false : true;
			}

			return (double) $attributes[$name];
		}
	}

	static private function _readColor($color, $background = false)
	{
		if (isset($color['rgb'])) {
			return (string) $color['rgb'];
		}

		if (isset($color['indexed'])) {
			return PHPExcel_Style_Color::indexedColor($color['indexed'] - 7, $background)->getARGB();
		}
	}

	static public function readChart($chartElements, $chartName)
	{
		$namespacesChartMeta = $chartElements->getNamespaces(true);
		$chartElementsC = $chartElements->children($namespacesChartMeta['c']);
		$XaxisLabel = $YaxisLabel = $legend = $title = NULL;
		$dispBlanksAs = $plotVisOnly = NULL;

		foreach ($chartElementsC as $chartElementKey => $chartElement) {
			switch ($chartElementKey) {
			case 'chart':
				foreach ($chartElement as $chartDetailsKey => $chartDetails) {
					$chartDetailsC = $chartDetails->children($namespacesChartMeta['c']);

					switch ($chartDetailsKey) {
					case 'plotArea':
						$plotAreaLayout = $XaxisLable = $YaxisLable = NULL;
						$plotSeries = $plotAttributes = array();

						foreach ($chartDetails as $chartDetailKey => $chartDetail) {
							switch ($chartDetailKey) {
							case 'layout':
								$plotAreaLayout = self::_chartLayoutDetails($chartDetail, $namespacesChartMeta, 'plotArea');
								break;

							case 'catAx':
								if (isset($chartDetail->title)) {
									$XaxisLabel = self::_chartTitle($chartDetail->title->children($namespacesChartMeta['c']), $namespacesChartMeta, 'cat');
								}

								break;

							case 'dateAx':
								if (isset($chartDetail->title)) {
									$XaxisLabel = self::_chartTitle($chartDetail->title->children($namespacesChartMeta['c']), $namespacesChartMeta, 'cat');
								}

								break;

							case 'valAx':
								if (isset($chartDetail->title)) {
									$YaxisLabel = self::_chartTitle($chartDetail->title->children($namespacesChartMeta['c']), $namespacesChartMeta, 'cat');
								}

								break;

							case 'barChart':
							case 'bar3DChart':
								$barDirection = self::_getAttribute($chartDetail->barDir, 'val', 'string');
								$plotSer = self::_chartDataSeries($chartDetail, $namespacesChartMeta, $chartDetailKey);
								$plotSer->setPlotDirection($barDirection);
								$plotSeries[] = $plotSer;
								$plotAttributes = self::_readChartAttributes($chartDetail);
								break;

							case 'lineChart':
							case 'line3DChart':
								$plotSeries[] = self::_chartDataSeries($chartDetail, $namespacesChartMeta, $chartDetailKey);
								$plotAttributes = self::_readChartAttributes($chartDetail);
								break;

							case 'areaChart':
							case 'area3DChart':
								$plotSeries[] = self::_chartDataSeries($chartDetail, $namespacesChartMeta, $chartDetailKey);
								$plotAttributes = self::_readChartAttributes($chartDetail);
								break;

							case 'doughnutChart':
							case 'pieChart':
							case 'pie3DChart':
								$explosion = isset($chartDetail->ser->explosion);
								$plotSer = self::_chartDataSeries($chartDetail, $namespacesChartMeta, $chartDetailKey);
								$plotSer->setPlotStyle($explosion);
								$plotSeries[] = $plotSer;
								$plotAttributes = self::_readChartAttributes($chartDetail);
								break;

							case 'scatterChart':
								$scatterStyle = self::_getAttribute($chartDetail->scatterStyle, 'val', 'string');
								$plotSer = self::_chartDataSeries($chartDetail, $namespacesChartMeta, $chartDetailKey);
								$plotSer->setPlotStyle($scatterStyle);
								$plotSeries[] = $plotSer;
								$plotAttributes = self::_readChartAttributes($chartDetail);
								break;

							case 'bubbleChart':
								$bubbleScale = self::_getAttribute($chartDetail->bubbleScale, 'val', 'integer');
								$plotSer = self::_chartDataSeries($chartDetail, $namespacesChartMeta, $chartDetailKey);
								$plotSer->setPlotStyle($bubbleScale);
								$plotSeries[] = $plotSer;
								$plotAttributes = self::_readChartAttributes($chartDetail);
								break;

							case 'radarChart':
								$radarStyle = self::_getAttribute($chartDetail->radarStyle, 'val', 'string');
								$plotSer = self::_chartDataSeries($chartDetail, $namespacesChartMeta, $chartDetailKey);
								$plotSer->setPlotStyle($radarStyle);
								$plotSeries[] = $plotSer;
								$plotAttributes = self::_readChartAttributes($chartDetail);
								break;

							case 'surfaceChart':
							case 'surface3DChart':
								$wireFrame = self::_getAttribute($chartDetail->wireframe, 'val', 'boolean');
								$plotSer = self::_chartDataSeries($chartDetail, $namespacesChartMeta, $chartDetailKey);
								$plotSer->setPlotStyle($wireFrame);
								$plotSeries[] = $plotSer;
								$plotAttributes = self::_readChartAttributes($chartDetail);
								break;

							case 'stockChart':
								$plotSeries[] = self::_chartDataSeries($chartDetail, $namespacesChartMeta, $chartDetailKey);
								$plotAttributes = self::_readChartAttributes($plotAreaLayout);
								break;
							}
						}

						if ($plotAreaLayout == NULL) {
							$plotAreaLayout = new PHPExcel_Chart_Layout();
						}

						$plotArea = new PHPExcel_Chart_PlotArea($plotAreaLayout, $plotSeries);
						self::_setChartAttributes($plotAreaLayout, $plotAttributes);
						break;

					case 'plotVisOnly':
						$plotVisOnly = self::_getAttribute($chartDetails, 'val', 'string');
						break;

					case 'dispBlanksAs':
						$dispBlanksAs = self::_getAttribute($chartDetails, 'val', 'string');
						break;

					case 'title':
						$title = self::_chartTitle($chartDetails, $namespacesChartMeta, 'title');
						break;

					case 'legend':
						$legendPos = 'r';
						$legendLayout = NULL;
						$legendOverlay = false;

						foreach ($chartDetails as $chartDetailKey => $chartDetail) {
							switch ($chartDetailKey) {
							case 'legendPos':
								$legendPos = self::_getAttribute($chartDetail, 'val', 'string');
								break;

							case 'overlay':
								$legendOverlay = self::_getAttribute($chartDetail, 'val', 'boolean');
								break;

							case 'layout':
								$legendLayout = self::_chartLayoutDetails($chartDetail, $namespacesChartMeta, 'legend');
								break;
							}
						}

						$legend = new PHPExcel_Chart_Legend($legendPos, $legendLayout, $legendOverlay);
						break;
					}
				}
			}
		}

		$chart = new PHPExcel_Chart($chartName, $title, $legend, $plotArea, $plotVisOnly, $dispBlanksAs, $XaxisLabel, $YaxisLabel);
		return $chart;
	}

	static private function _chartTitle($titleDetails, $namespacesChartMeta, $type)
	{
		$caption = array();
		$titleLayout = NULL;

		foreach ($titleDetails as $titleDetailKey => $chartDetail) {
			switch ($titleDetailKey) {
			case 'tx':
				$titleDetails = $chartDetail->rich->children($namespacesChartMeta['a']);

				foreach ($titleDetails as $titleKey => $titleDetail) {
					24	CASE	48	OP1:$titleKey	OP2:'p'	RES:TMP:19	;0	>>26	
					25	JMPZ	43	OP1:TMP:19:'p'	OP2:UNUSED:37	RES:UNUSED:0	;0	>>37	
					26	INIT_METHOD_CALL	112	OP1:$titleDetail	OP2:'children'	RES:UNUSED:0	;0	<<24	
					27	FETCH_DIM_FUNC_ARG	93	OP1:$namespacesChartMeta	OP2:'a'	RES:VAR:21	;268435457	
					28	SEND_VAR	66	OP1:VAR:21:$namespacesChartMeta['a']	OP2:UNUSED:1	RES:UNUSED:0	;61	
					29	DO_FCALL_BY_NAME	61	OP1:UNUSED:0	OP2:UNUSED:0	RES:VAR:22	;1	
					30	ASSIGN	38	OP1:$titleDetailPart	OP2:VAR:22:$titleDetail->children($namespacesChartMeta['a'])	RES:	;0	
					31	FETCH_CLASS	109	OP1:UNUSED:0	OP2:UNUSED:0	RES:TMP:25	;1	
					32	INIT_STATIC_METHOD_CALL	113	OP1:VAR:25:self	OP2:'_parseRichText'	RES:UNUSED:0	;1	
					33	SEND_VAR	66	OP1:$titleDetailPart	OP2:UNUSED:1	RES:UNUSED:0	;61	
					34	DO_FCALL_BY_NAME	61	OP1:UNUSED:0	OP2:UNUSED:0	RES:VAR:26	;1	
					35	ASSIGN_DIM	147	OP1:$caption	OP2:UNUSED:440	RES:	;0	
					36	871: ::printBacktrace()
2856: Decompiler::getOpVal(array('var' => 27, 'op_type' => 4), array('Ts' => array, 'indent' => '					', 'nextbbs' => array, 'op_array' => array, 'opcodes' => array, ...))
2892: Decompiler::dumpop(array('op1' => array, 'op2' => array, 'result' => array, 'opcode' => 137, 'extended_value' => 0, ...), array('Ts' => array, 'indent' => '					', 'nextbbs' => array, 'op_array' => array, 'opcodes' => array, ...))
960: Decompiler::dumpRange(array('Ts' => array, 'indent' => '					', 'nextbbs' => array, 'op_array' => array, 'opcodes' => array, ...), array(0 => 24, 1 => 36))
1484: Decompiler::decompileBasicBlock(array('Ts' => array, 'indent' => '					', 'nextbbs' => array, 'op_array' => array, 'opcodes' => array, ...), array(0 => 24, 1 => 36), true)
1521: Decompiler::decompileComplexBlock(array('Ts' => array, 'indent' => '					', 'nextbbs' => array, 'op_array' => array, 'opcodes' => array, ...), array(0 => 24, 1 => 36))
1465: Decompiler::recognizeAndDecompileClosedBlocks(array('Ts' => array, 'indent' => '					', 'nextbbs' => array, 'op_array' => array, 'opcodes' => array, ...), array(0 => 20, 1 => 37))
1521: Decompiler::decompileComplexBlock(array('Ts' => array, 'indent' => '					', 'nextbbs' => array, 'op_array' => array, 'opcodes' => array, ...), array(0 => 20, 1 => 37))
1374: Decompiler::recognizeAndDecompileClosedBlocks(array('Ts' => array, 'indent' => '					', 'nextbbs' => array, 'op_array' => array, 'opcodes' => array, ...), array(0 => 13, 1 => 39))
1521: Decompiler::decompileComplexBlock(array('Ts' => array, 'indent' => '					', 'nextbbs' => array, 'op_array' => array, 'opcodes' => array, ...), array(0 => 11, 1 => 50))
1465: Decompiler::recognizeAndDecompileClosedBlocks(array('Ts' => array, 'indent' => '					', 'nextbbs' => array, 'op_array' => array, 'opcodes' => array, ...), array(0 => 7, 1 => 52))
1521: Decompiler::decompileComplexBlock(array('Ts' => array, 'indent' => '					', 'nextbbs' => array, 'op_array' => array, 'opcodes' => array, ...), array(0 => 7, 1 => 52))
1723: Decompiler::recognizeAndDecompileClosedBlocks(array('Ts' => array, 'indent' => '					', 'nextbbs' => array, 'op_array' => array, 'opcodes' => array, ...), array(0 => 0, 1 => 59), '		')
2987: Decompiler::dop_array(array('type' => 2, 'function_name' => '_chartTitle', 'fn_flags' => 1025, 'arg_info' => array, 'num_args' => 3, ...), '		')
3208: Decompiler::dfunction(array('op_array' => array), '	', array(0 => 'static', 1 => 'private'), false)
3258: Decompiler::dclass(array('type' => 2, 'name_length' => 31, 'name' => 'PHPExcel_Reader_Excel2007_Chart', 'parent' => NULL, 'refcount' => 2, ...))
84: Decompiler::output('// 模块由折翼天使资源社区提供')

Notice: Undefined offset: 27 in D:\yunlu\bin\decoders\dezend\scripts\Decompiler.ioncube.class.php on line 873
OP_DATA	137	OP1:VAR:26:self::_parseRichText($titleDetailPart)	OP2:VAR:27:	RES:UNUSED:0	;0	
					==
					$titleDetailPart = $titleDetail->children($namespacesChartMeta['a']);
					$caption[] = self::_parseRichText($titleDetailPart);
				}

				break;

			case 'layout':
				$titleLayout = self::_chartLayoutDetails($chartDetail, $namespacesChartMeta);
				break;
			}
		}

		return new PHPExcel_Chart_Title($caption, $titleLayout);
	}

	static private function _chartLayoutDetails($chartDetail, $namespacesChartMeta)
	{
		if (!isset($chartDetail->manualLayout)) {
			return NULL;
		}

		$details = $chartDetail->manualLayout->children($namespacesChartMeta['c']);

		if (is_null($details)) {
			return NULL;
		}

		$layout = array();

		foreach ($details as $detailKey => $detail) {
			$layout[$detailKey] = self::_getAttribute($detail, 'val', 'string');
		}

		return new PHPExcel_Chart_Layout($layout);
	}

	static private function _chartDataSeries($chartDetail, $namespacesChartMeta, $plotType)
	{
		$multiSeriesType = NULL;
		$smoothLine = false;
		$seriesLabel = $seriesCategory = $seriesValues = $plotOrder = array();
		$seriesDetailSet = $chartDetail->children($namespacesChartMeta['c']);

		foreach ($seriesDetailSet as $seriesDetailKey => $seriesDetails) {
			switch ($seriesDetailKey) {
			case 'grouping':
				$multiSeriesType = self::_getAttribute($chartDetail->grouping, 'val', 'string');
				break;

			case 'ser':
				$marker = NULL;

				foreach ($seriesDetails as $seriesKey => $seriesDetail) {
					switch ($seriesKey) {
					case 'idx':
						$seriesIndex = self::_getAttribute($seriesDetail, 'val', 'integer');
						break;

					case 'order':
						$seriesOrder = self::_getAttribute($seriesDetail, 'val', 'integer');
						$plotOrder[$seriesIndex] = $seriesOrder;
						break;

					case 'tx':
						$seriesLabel[$seriesIndex] = self::_chartDataSeriesValueSet($seriesDetail, $namespacesChartMeta);
						break;

					case 'marker':
						$marker = self::_getAttribute($seriesDetail->symbol, 'val', 'string');
						break;

					case 'smooth':
						$smoothLine = self::_getAttribute($seriesDetail, 'val', 'boolean');
						break;

					case 'cat':
						$seriesCategory[$seriesIndex] = self::_chartDataSeriesValueSet($seriesDetail, $namespacesChartMeta);
						break;

					case 'val':
						$seriesValues[$seriesIndex] = self::_chartDataSeriesValueSet($seriesDetail, $namespacesChartMeta, $marker);
						break;

					case 'xVal':
						$seriesCategory[$seriesIndex] = self::_chartDataSeriesValueSet($seriesDetail, $namespacesChartMeta, $marker);
						break;

					case 'yVal':
						$seriesValues[$seriesIndex] = self::_chartDataSeriesValueSet($seriesDetail, $namespacesChartMeta, $marker);
						break;
					}
				}
			}
		}

		return new PHPExcel_Chart_DataSeries($plotType, $multiSeriesType, $plotOrder, $seriesLabel, $seriesCategory, $seriesValues, $smoothLine);
	}

	static private function _chartDataSeriesValueSet($seriesDetail, $namespacesChartMeta, $marker = NULL, $smoothLine = false)
	{
		if (isset($seriesDetail->strRef)) {
			$seriesSource = (string) $seriesDetail->strRef->f;
			$seriesData = self::_chartDataSeriesValues($seriesDetail->strRef->strCache->children($namespacesChartMeta['c']), 's');
			return new PHPExcel_Chart_DataSeriesValues('String', $seriesSource, $seriesData['formatCode'], $seriesData['pointCount'], $seriesData['dataValues'], $marker, $smoothLine);
		}

		if (isset($seriesDetail->numRef)) {
			$seriesSource = (string) $seriesDetail->numRef->f;
			$seriesData = self::_chartDataSeriesValues($seriesDetail->numRef->numCache->children($namespacesChartMeta['c']));
			return new PHPExcel_Chart_DataSeriesValues('Number', $seriesSource, $seriesData['formatCode'], $seriesData['pointCount'], $seriesData['dataValues'], $marker, $smoothLine);
		}

		if (isset($seriesDetail->multiLvlStrRef)) {
			$seriesSource = (string) $seriesDetail->multiLvlStrRef->f;
			$seriesData = self::_chartDataSeriesValuesMultiLevel($seriesDetail->multiLvlStrRef->multiLvlStrCache->children($namespacesChartMeta['c']), 's');
			$seriesData['pointCount'] = count($seriesData['dataValues']);
			return new PHPExcel_Chart_DataSeriesValues('String', $seriesSource, $seriesData['formatCode'], $seriesData['pointCount'], $seriesData['dataValues'], $marker, $smoothLine);
		}

		if (isset($seriesDetail->multiLvlNumRef)) {
			$seriesSource = (string) $seriesDetail->multiLvlNumRef->f;
			$seriesData = self::_chartDataSeriesValuesMultiLevel($seriesDetail->multiLvlNumRef->multiLvlNumCache->children($namespacesChartMeta['c']), 's');
			$seriesData['pointCount'] = count($seriesData['dataValues']);
			return new PHPExcel_Chart_DataSeriesValues('String', $seriesSource, $seriesData['formatCode'], $seriesData['pointCount'], $seriesData['dataValues'], $marker, $smoothLine);
		}
	}

	static private function _chartDataSeriesValues($seriesValueSet, $dataType = 'n')
	{
		$seriesVal = array();
		$formatCode = '';
		$pointCount = 0;

		foreach ($seriesValueSet as $seriesValueIdx => $seriesValue) {
			switch ($seriesValueIdx) {
			case 'ptCount':
				$pointCount = self::_getAttribute($seriesValue, 'val', 'integer');
				break;

			case 'formatCode':
				$formatCode = (string) $seriesValue;
				break;

			case 'pt':
				$pointVal = self::_getAttribute($seriesValue, 'idx', 'integer');

				if ($dataType == 's') {
					$seriesVal[$pointVal] = (string) $seriesValue->v;
				}
				else {
					$seriesVal[$pointVal] = (double) $seriesValue->v;
				}

				break;
			}
		}

		if (empty($seriesVal)) {
			$seriesVal = NULL;
		}

		return array('formatCode' => $formatCode, 'pointCount' => $pointCount, 'dataValues' => $seriesVal);
	}

	static private function _chartDataSeriesValuesMultiLevel($seriesValueSet, $dataType = 'n')
	{
		$seriesVal = array();
		$formatCode = '';
		$pointCount = 0;

		foreach ($seriesValueSet->lvl as $seriesLevelIdx => $seriesLevel) {
			foreach ($seriesLevel as $seriesValueIdx => $seriesValue) {
				switch ($seriesValueIdx) {
				case 'ptCount':
					$pointCount = self::_getAttribute($seriesValue, 'val', 'integer');
					break;

				case 'formatCode':
					$formatCode = (string) $seriesValue;
					break;

				case 'pt':
					$pointVal = self::_getAttribute($seriesValue, 'idx', 'integer');

					if ($dataType == 's') {
						$seriesVal[$pointVal][] = (string) $seriesValue->v;
					}
					else {
						$seriesVal[$pointVal][] = (double) $seriesValue->v;
					}

					break;
				}
			}
		}

		return array('formatCode' => $formatCode, 'pointCount' => $pointCount, 'dataValues' => $seriesVal);
	}

	static private function _parseRichText($titleDetailPart = NULL)
	{
		$value = new PHPExcel_RichText();

		foreach ($titleDetailPart as $titleDetailElementKey => $titleDetailElement) {
			if (isset($titleDetailElement->t)) {
				$objText = $value->createTextRun((string) $titleDetailElement->t);
			}

			if (isset($titleDetailElement->rPr)) {
				if (isset($titleDetailElement->rPr->rFont['val'])) {
					$objText->getFont()->setName((string) $titleDetailElement->rPr->rFont['val']);
				}

				$fontSize = self::_getAttribute($titleDetailElement->rPr, 'sz', 'integer');

				if (!is_null($fontSize)) {
					$objText->getFont()->setSize(floor($fontSize / 100));
				}

				$fontColor = self::_getAttribute($titleDetailElement->rPr, 'color', 'string');

				if (!is_null($fontColor)) {
					$objText->getFont()->setColor(new PHPExcel_Style_Color(self::_readColor($fontColor)));
				}

				$bold = self::_getAttribute($titleDetailElement->rPr, 'b', 'boolean');

				if (!is_null($bold)) {
					$objText->getFont()->setBold($bold);
				}

				$italic = self::_getAttribute($titleDetailElement->rPr, 'i', 'boolean');

				if (!is_null($italic)) {
					$objText->getFont()->setItalic($italic);
				}

				$baseline = self::_getAttribute($titleDetailElement->rPr, 'baseline', 'integer');

				if (!is_null($baseline)) {
					if (0 < $baseline) {
						$objText->getFont()->setSuperScript(true);
					}
					else {
						if ($baseline < 0) {
							$objText->getFont()->setSubScript(true);
						}
					}
				}

				$underscore = self::_getAttribute($titleDetailElement->rPr, 'u', 'string');

				if (!is_null($underscore)) {
					if ($underscore == 'sng') {
						$objText->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
					}
					else if ($underscore == 'dbl') {
						$objText->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_DOUBLE);
					}
					else {
						$objText->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_NONE);
					}
				}

				$strikethrough = self::_getAttribute($titleDetailElement->rPr, 's', 'string');

				if (!is_null($strikethrough)) {
					if ($strikethrough == 'noStrike') {
						$objText->getFont()->setStrikethrough(false);
					}
					else {
						$objText->getFont()->setStrikethrough(true);
					}
				}
			}
		}

		return $value;
	}

	static private function _readChartAttributes($chartDetail)
	{
		$plotAttributes = array();

		if (isset($chartDetail->dLbls)) {
			if (isset($chartDetail->dLbls->howLegendKey)) {
				$plotAttributes['showLegendKey'] = self::_getAttribute($chartDetail->dLbls->showLegendKey, 'val', 'string');
			}

			if (isset($chartDetail->dLbls->showVal)) {
				$plotAttributes['showVal'] = self::_getAttribute($chartDetail->dLbls->showVal, 'val', 'string');
			}

			if (isset($chartDetail->dLbls->showCatName)) {
				$plotAttributes['showCatName'] = self::_getAttribute($chartDetail->dLbls->showCatName, 'val', 'string');
			}

			if (isset($chartDetail->dLbls->showSerName)) {
				$plotAttributes['showSerName'] = self::_getAttribute($chartDetail->dLbls->showSerName, 'val', 'string');
			}

			if (isset($chartDetail->dLbls->showPercent)) {
				$plotAttributes['showPercent'] = self::_getAttribute($chartDetail->dLbls->showPercent, 'val', 'string');
			}

			if (isset($chartDetail->dLbls->showBubbleSize)) {
				$plotAttributes['showBubbleSize'] = self::_getAttribute($chartDetail->dLbls->showBubbleSize, 'val', 'string');
			}

			if (isset($chartDetail->dLbls->showLeaderLines)) {
				$plotAttributes['showLeaderLines'] = self::_getAttribute($chartDetail->dLbls->showLeaderLines, 'val', 'string');
			}
		}

		return $plotAttributes;
	}

	static private function _setChartAttributes($plotArea, $plotAttributes)
	{
		foreach ($plotAttributes as $plotAttributeKey => $plotAttributeValue) {
			switch ($plotAttributeKey) {
			case 'showLegendKey':
				$plotArea->setShowLegendKey($plotAttributeValue);
				break;

			case 'showVal':
				$plotArea->setShowVal($plotAttributeValue);
				break;

			case 'showCatName':
				$plotArea->setShowCatName($plotAttributeValue);
				break;

			case 'showSerName':
				$plotArea->setShowSerName($plotAttributeValue);
				break;

			case 'showPercent':
				$plotArea->setShowPercent($plotAttributeValue);
				break;

			case 'showBubbleSize':
				$plotArea->setShowBubbleSize($plotAttributeValue);
				break;

			case 'showLeaderLines':
				$plotArea->setShowLeaderLines($plotAttributeValue);
				break;
			}
		}
	}
}


?>
