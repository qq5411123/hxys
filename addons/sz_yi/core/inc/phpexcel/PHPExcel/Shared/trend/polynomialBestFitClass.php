<?php
// 唐上美联佳网络科技有限公司(技术支持)
require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/trend/bestFitClass.php';
require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/JAMA/Matrix.php';
class PHPExcel_Polynomial_Best_Fit extends PHPExcel_Best_Fit
{
	protected $_bestFitType = 'polynomial';
	protected $_order = 0;

	public function getOrder()
	{
		return $this->_order;
	}

	public function getValueOfYForX($xValue)
	{
		$retVal = $this->getIntersect();
		$slope = $this->getSlope();

		foreach ($slope as $key => $value) {
			if ($value != 0) {
				$retVal += $value * pow($xValue, $key + 1);
			}
		}

		return $retVal;
	}

	public function getValueOfXForY($yValue)
	{
		return ($yValue - $this->getIntersect()) / $this->getSlope();
	}

	public function getEquation($dp = 0)
	{
		$slope = $this->getSlope($dp);
		$intersect = $this->getIntersect($dp);
		$equation = 'Y = ' . $intersect;

		foreach ($slope as $key => $value) {
			if ($value != 0) {
				$equation .= ' + ' . $value . ' * X';

				if (0 < $key) {
					$equation .= '^' . ($key + 1);
				}
			}
		}

		return $equation;
	}

	public function getSlope($dp = 0)
	{
		if ($dp != 0) {
			$coefficients = array();

			foreach ($this->_slope as $coefficient) {
				$coefficients[] = round($coefficient, $dp);
			}

			return $coefficients;
		}

		return $this->_slope;
	}

	public function getCoefficients($dp = 0)
	{
		return array_merge(array($this->getIntersect($dp)), $this->getSlope($dp));
	}

	private function _polynomial_regression($order, $yValues, $xValues, $const)
	{
		$x_sum = array_sum($xValues);
		$y_sum = array_sum($yValues);
		$xx_sum = $xy_sum = 0;
		$i = 0;

		while ($i < $this->_valueCount) {
			$xy_sum += $xValues[$i] * $yValues[$i];
			$xx_sum += $xValues[$i] * $xValues[$i];
			$yy_sum += $yValues[$i] * $yValues[$i];
			++$i;
		}

		$i = 0;

		while ($i < $this->_valueCount) {
			$j = 0;

			while ($j <= $order) {
				$A[$i][$j] = pow($xValues[$i], $j);
				++$j;
			}

			++$i;
		}

		$i = 0;

		while ($i < $this->_valueCount) {
			$B[$i] = array($yValues[$i]);
			++$i;
		}

		$matrixA = new Matrix($A);
		$matrixB = new Matrix($B);
		$C = $matrixA->solve($matrixB);
		$coefficients = array();
		$i = 0;

		while ($i < $C->m) {
			$r = $C->get($i, 0);

			if (abs($r) <= pow(10, -9)) {
				$r = 0;
			}

			$coefficients[] = $r;
			++$i;
		}

		$this->_intersect = array_shift($coefficients);
		$this->_slope = $coefficients;
		$this->_calculateGoodnessOfFit($x_sum, $y_sum, $xx_sum, $yy_sum, $xy_sum);

		foreach ($this->_xValues as $xKey => $xValue) {
			$this->_yBestFitValues[$xKey] = $this->getValueOfYForX($xValue);
		}
	}

	public function __construct($order, $yValues, $xValues = array(), $const = true)
	{
		if (parent::__construct($yValues, $xValues) !== false) {
			if ($order < $this->_valueCount) {
				$this->_bestFitType .= '_' . $order;
				$this->_order = $order;
				$this->_polynomial_regression($order, $yValues, $xValues, $const);
				if (($this->getGoodnessOfFit() < 0) || (1 < $this->getGoodnessOfFit())) {
					$this->_error = true;
					return NULL;
				}
			}
			else {
				$this->_error = true;
			}
		}
	}
}

?>
