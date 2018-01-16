<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_Shared_JAMA_Matrix
{
	const PolymorphicArgumentException = 'Invalid argument pattern for polymorphic function.';
	const ArgumentTypeException = 'Invalid argument type.';
	const ArgumentBoundsException = 'Invalid argument range.';
	const MatrixDimensionException = 'Matrix dimensions are not equal.';
	const ArrayLengthException = 'Array length must be a multiple of m.';

	public $A = array();
	private $m;
	private $n;

	public function __construct()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'array':
				$this->m = count($args[0]);
				$this->n = count($args[0][0]);
				$this->A = $args[0];
				break;

			case 'integer':
				$this->m = $args[0];
				$this->n = $args[0];
				$this->A = array_fill(0, $this->m, array_fill(0, $this->n, 0));
				break;

			case 'integer,integer':
				$this->m = $args[0];
				$this->n = $args[1];
				$this->A = array_fill(0, $this->m, array_fill(0, $this->n, 0));
				break;

			case 'array,integer':
				$this->m = $args[1];

				if ($this->m != 0) {
					$this->n = count($args[0]) / $this->m;
				}
				else {
					$this->n = 0;
				}

				if (($this->m * $this->n) == count($args[0])) {
					$i = 0;

					while ($i < $this->m) {
						$j = 0;

						while ($j < $this->n) {
							$this->A[$i][$j] = $args[0][$i + ($j * $this->m)];
							++$j;
						}

						++$i;
					}
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArrayLengthException);
				}

				break;

			default:
				throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
				break;
			}

			return NULL;
		}

		throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
	}

	public function getArray()
	{
		return $this->A;
	}

	public function getRowDimension()
	{
		return $this->m;
	}

	public function getColumnDimension()
	{
		return $this->n;
	}

	public function get($i = NULL, $j = NULL)
	{
		return $this->A[$i][$j];
	}

	public function getMatrix()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'integer,integer':
				list($i0, $j0) = $args;

				if (0 <= $i0) {
					$m = $this->m - $i0;
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentBoundsException);
				}

				if (0 <= $j0) {
					$n = $this->n - $j0;
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentBoundsException);
				}

				$R = new PHPExcel_Shared_JAMA_Matrix($m, $n);
				$i = $i0;

				while ($i < $this->m) {
					$j = $j0;

					while ($j < $this->n) {
						$R->set($i, $j, $this->A[$i][$j]);
						++$j;
					}

					++$i;
				}

				return $R;
			case 'integer,integer,integer,integer':
				list($i0, $iF, $j0, $jF) = $args;
				if (($i0 < $iF) && ($iF <= $this->m) && (0 <= $i0)) {
					$m = $iF - $i0;
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentBoundsException);
				}

				if (($j0 < $jF) && ($jF <= $this->n) && (0 <= $j0)) {
					$n = $jF - $j0;
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentBoundsException);
				}

				$R = new PHPExcel_Shared_JAMA_Matrix($m + 1, $n + 1);
				$i = $i0;

				while ($i <= $iF) {
					$j = $j0;

					while ($j <= $jF) {
						$R->set($i - $i0, $j - $j0, $this->A[$i][$j]);
						++$j;
					}

					++$i;
				}

				return $R;
			case 'array,array':
				list($RL, $CL) = $args;

				if (0 < count($RL)) {
					$m = count($RL);
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentBoundsException);
				}

				if (0 < count($CL)) {
					$n = count($CL);
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentBoundsException);
				}

				$R = new PHPExcel_Shared_JAMA_Matrix($m, $n);
				$i = 0;

				while ($i < $m) {
					$j = 0;

					while ($j < $n) {
						$R->set($i - $i0, $j - $j0, $this->A[$RL[$i]][$CL[$j]]);
						++$j;
					}

					++$i;
				}

				return $R;
			case 'array,array':
				list($RL, $CL) = $args;

				if (0 < count($RL)) {
					$m = count($RL);
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentBoundsException);
				}

				if (0 < count($CL)) {
					$n = count($CL);
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentBoundsException);
				}

				$R = new PHPExcel_Shared_JAMA_Matrix($m, $n);
				$i = 0;

				while ($i < $m) {
					$j = 0;

					while ($j < $n) {
						$R->set($i, $j, $this->A[$RL[$i]][$CL[$j]]);
						++$j;
					}

					++$i;
				}

				return $R;
			case 'integer,integer,array':
				list($i0, $iF, $CL) = $args;
				if (($i0 < $iF) && ($iF <= $this->m) && (0 <= $i0)) {
					$m = $iF - $i0;
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentBoundsException);
				}

				if (0 < count($CL)) {
					$n = count($CL);
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentBoundsException);
				}

				$R = new PHPExcel_Shared_JAMA_Matrix($m, $n);
				$i = $i0;

				while ($i < $iF) {
					$j = 0;

					while ($j < $n) {
						$R->set($i - $i0, $j, $this->A[$RL[$i]][$j]);
						++$j;
					}

					++$i;
				}

				return $R;
			case 'array,integer,integer':
				list($RL, $j0, $jF) = $args;

				if (0 < count($RL)) {
					$m = count($RL);
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentBoundsException);
				}

				if (($j0 <= $jF) && ($jF <= $this->n) && (0 <= $j0)) {
					$n = $jF - $j0;
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentBoundsException);
				}

				$R = new PHPExcel_Shared_JAMA_Matrix($m, $n + 1);
				$i = 0;

				while ($i < $m) {
					$j = $j0;

					while ($j <= $jF) {
						$R->set($i, $j - $j0, $this->A[$RL[$i]][$j]);
						++$j;
					}

					++$i;
				}

				return $R;
			default:
				throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
				break;
			}

			return NULL;
		}

		throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
	}

	public function checkMatrixDimensions($B = NULL)
	{
		if ($B instanceof PHPExcel_Shared_JAMA_Matrix) {
			if (($this->m == $B->getRowDimension()) && ($this->n == $B->getColumnDimension())) {
				return true;
			}

			throw new PHPExcel_Calculation_Exception(self::MatrixDimensionException);
			return NULL;
		}

		throw new PHPExcel_Calculation_Exception(self::ArgumentTypeException);
	}

	public function set($i = NULL, $j = NULL, $c = NULL)
	{
		$this->A[$i][$j] = $c;
	}

	public function identity($m = NULL, $n = NULL)
	{
		return $this->diagonal($m, $n, 1);
	}

	public function diagonal($m = NULL, $n = NULL, $c = 1)
	{
		$R = new PHPExcel_Shared_JAMA_Matrix($m, $n);
		$i = 0;

		while ($i < $m) {
			$R->set($i, $i, $c);
			++$i;
		}

		return $R;
	}

	public function getMatrixByRow($i0 = NULL, $iF = NULL)
	{
		if (is_int($i0)) {
			if (is_int($iF)) {
				return $this->getMatrix($i0, 0, $iF + 1, $this->n);
			}

			return $this->getMatrix($i0, 0, $i0 + 1, $this->n);
		}

		throw new PHPExcel_Calculation_Exception(self::ArgumentTypeException);
	}

	public function getMatrixByCol($j0 = NULL, $jF = NULL)
	{
		if (is_int($j0)) {
			if (is_int($jF)) {
				return $this->getMatrix(0, $j0, $this->m, $jF + 1);
			}

			return $this->getMatrix(0, $j0, $this->m, $j0 + 1);
		}

		throw new PHPExcel_Calculation_Exception(self::ArgumentTypeException);
	}

	public function transpose()
	{
		$R = new PHPExcel_Shared_JAMA_Matrix($this->n, $this->m);
		$i = 0;

		while ($i < $this->m) {
			$j = 0;

			while ($j < $this->n) {
				$R->set($j, $i, $this->A[$i][$j]);
				++$j;
			}

			++$i;
		}

		return $R;
	}

	public function trace()
	{
		$s = 0;
		$n = min($this->m, $this->n);
		$i = 0;

		while ($i < $n) {
			$s += $this->A[$i][$i];
			++$i;
		}

		return $s;
	}

	public function uminus()
	{
	}

	public function plus()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
					$M = $args[0];
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentTypeException);
				}

				break;

			case 'array':
				$M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
				break;

			default:
				throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
				break;
			}

			$this->checkMatrixDimensions($M);
			$i = 0;

			while ($i < $this->m) {
				$j = 0;

				while ($j < $this->n) {
					$M->set($i, $j, $M->get($i, $j) + $this->A[$i][$j]);
					++$j;
				}

				++$i;
			}

			return $M;
		}

		throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
	}

	public function plusEquals()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
					$M = $args[0];
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentTypeException);
				}

				break;

			case 'array':
				$M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
				break;

			default:
				throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
				break;
			}

			$this->checkMatrixDimensions($M);
			$i = 0;

			while ($i < $this->m) {
				$j = 0;

				while ($j < $this->n) {
					$validValues = true;
					$value = $M->get($i, $j);
					if (is_string($this->A[$i][$j]) && (0 < strlen($this->A[$i][$j])) && !is_numeric($this->A[$i][$j])) {
						$this->A[$i][$j] = trim($this->A[$i][$j], '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($this->A[$i][$j]);
					}

					if (is_string($value) && (0 < strlen($value)) && !is_numeric($value)) {
						$value = trim($value, '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($value);
					}

					if ($validValues) {
						$this->A[$i][$j] += $value;
					}
					else {
						$this->A[$i][$j] = PHPExcel_Calculation_Functions::NaN();
					}

					++$j;
				}

				++$i;
			}

			return $this;
		}

		throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
	}

	public function minus()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
					$M = $args[0];
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentTypeException);
				}

				break;

			case 'array':
				$M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
				break;

			default:
				throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
				break;
			}

			$this->checkMatrixDimensions($M);
			$i = 0;

			while ($i < $this->m) {
				$j = 0;

				while ($j < $this->n) {
					$M->set($i, $j, $M->get($i, $j) - $this->A[$i][$j]);
					++$j;
				}

				++$i;
			}

			return $M;
		}

		throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
	}

	public function minusEquals()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
					$M = $args[0];
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentTypeException);
				}

				break;

			case 'array':
				$M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
				break;

			default:
				throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
				break;
			}

			$this->checkMatrixDimensions($M);
			$i = 0;

			while ($i < $this->m) {
				$j = 0;

				while ($j < $this->n) {
					$validValues = true;
					$value = $M->get($i, $j);
					if (is_string($this->A[$i][$j]) && (0 < strlen($this->A[$i][$j])) && !is_numeric($this->A[$i][$j])) {
						$this->A[$i][$j] = trim($this->A[$i][$j], '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($this->A[$i][$j]);
					}

					if (is_string($value) && (0 < strlen($value)) && !is_numeric($value)) {
						$value = trim($value, '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($value);
					}

					if ($validValues) {
						$this->A[$i][$j] -= $value;
					}
					else {
						$this->A[$i][$j] = PHPExcel_Calculation_Functions::NaN();
					}

					++$j;
				}

				++$i;
			}

			return $this;
		}

		throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
	}

	public function arrayTimes()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
					$M = $args[0];
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentTypeException);
				}

				break;

			case 'array':
				$M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
				break;

			default:
				throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
				break;
			}

			$this->checkMatrixDimensions($M);
			$i = 0;

			while ($i < $this->m) {
				$j = 0;

				while ($j < $this->n) {
					$M->set($i, $j, $M->get($i, $j) * $this->A[$i][$j]);
					++$j;
				}

				++$i;
			}

			return $M;
		}

		throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
	}

	public function arrayTimesEquals()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
					$M = $args[0];
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentTypeException);
				}

				break;

			case 'array':
				$M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
				break;

			default:
				throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
				break;
			}

			$this->checkMatrixDimensions($M);
			$i = 0;

			while ($i < $this->m) {
				$j = 0;

				while ($j < $this->n) {
					$validValues = true;
					$value = $M->get($i, $j);
					if (is_string($this->A[$i][$j]) && (0 < strlen($this->A[$i][$j])) && !is_numeric($this->A[$i][$j])) {
						$this->A[$i][$j] = trim($this->A[$i][$j], '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($this->A[$i][$j]);
					}

					if (is_string($value) && (0 < strlen($value)) && !is_numeric($value)) {
						$value = trim($value, '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($value);
					}

					if ($validValues) {
						$this->A[$i][$j] *= $value;
					}
					else {
						$this->A[$i][$j] = PHPExcel_Calculation_Functions::NaN();
					}

					++$j;
				}

				++$i;
			}

			return $this;
		}

		throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
	}

	public function arrayRightDivide()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
					$M = $args[0];
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentTypeException);
				}

				break;

			case 'array':
				$M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
				break;

			default:
				throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
				break;
			}

			$this->checkMatrixDimensions($M);
			$i = 0;

			while ($i < $this->m) {
				$j = 0;

				while ($j < $this->n) {
					$validValues = true;
					$value = $M->get($i, $j);
					if (is_string($this->A[$i][$j]) && (0 < strlen($this->A[$i][$j])) && !is_numeric($this->A[$i][$j])) {
						$this->A[$i][$j] = trim($this->A[$i][$j], '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($this->A[$i][$j]);
					}

					if (is_string($value) && (0 < strlen($value)) && !is_numeric($value)) {
						$value = trim($value, '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($value);
					}

					if ($validValues) {
						if ($value == 0) {
							$M->set($i, $j, '#DIV/0!');
						}
						else {
							$M->set($i, $j, $this->A[$i][$j] / $value);
						}
					}
					else {
						$M->set($i, $j, PHPExcel_Calculation_Functions::NaN());
					}

					++$j;
				}

				++$i;
			}

			return $M;
		}

		throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
	}

	public function arrayRightDivideEquals()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
					$M = $args[0];
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentTypeException);
				}

				break;

			case 'array':
				$M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
				break;

			default:
				throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
				break;
			}

			$this->checkMatrixDimensions($M);
			$i = 0;

			while ($i < $this->m) {
				$j = 0;

				while ($j < $this->n) {
					$this->A[$i][$j] = $this->A[$i][$j] / $M->get($i, $j);
					++$j;
				}

				++$i;
			}

			return $M;
		}

		throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
	}

	public function arrayLeftDivide()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
					$M = $args[0];
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentTypeException);
				}

				break;

			case 'array':
				$M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
				break;

			default:
				throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
				break;
			}

			$this->checkMatrixDimensions($M);
			$i = 0;

			while ($i < $this->m) {
				$j = 0;

				while ($j < $this->n) {
					$M->set($i, $j, $M->get($i, $j) / $this->A[$i][$j]);
					++$j;
				}

				++$i;
			}

			return $M;
		}

		throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
	}

	public function arrayLeftDivideEquals()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
					$M = $args[0];
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentTypeException);
				}

				break;

			case 'array':
				$M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
				break;

			default:
				throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
				break;
			}

			$this->checkMatrixDimensions($M);
			$i = 0;

			while ($i < $this->m) {
				$j = 0;

				while ($j < $this->n) {
					$this->A[$i][$j] = $M->get($i, $j) / $this->A[$i][$j];
					++$j;
				}

				++$i;
			}

			return $M;
		}

		throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
	}

	public function times()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
					$B = $args[0];
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentTypeException);
				}

				if ($this->n == $B->m) {
					$C = new PHPExcel_Shared_JAMA_Matrix($this->m, $B->n);
					$j = 0;

					while ($j < $B->n) {
						$k = 0;

						while ($k < $this->n) {
							$Bcolj[$k] = $B->A[$k][$j];
							++$k;
						}

						$i = 0;

						while ($i < $this->m) {
							$Arowi = $this->A[$i];
							$s = 0;
							$k = 0;

							while ($k < $this->n) {
								$s += $Arowi[$k] * $Bcolj[$k];
								++$k;
							}

							$C->A[$i][$j] = $s;
							++$i;
						}

						++$j;
					}

					return $C;
				}

				throw new PHPExcel_Calculation_Exception(JAMAError(MatrixDimensionMismatch));
				break;

			case 'array':
				$B = new PHPExcel_Shared_JAMA_Matrix($args[0]);

				if ($this->n == $B->m) {
					$C = new PHPExcel_Shared_JAMA_Matrix($this->m, $B->n);
					$i = 0;

					while ($i < $C->m) {
						$j = 0;

						while ($j < $C->n) {
							$s = '0';
							$k = 0;

							while ($k < $C->n) {
								$s += $this->A[$i][$k] * $B->A[$k][$j];
								++$k;
							}

							$C->A[$i][$j] = $s;
							++$j;
						}

						++$i;
					}

					return $C;
				}

				throw new PHPExcel_Calculation_Exception(JAMAError(MatrixDimensionMismatch));
				return $M;
			case 'integer':
				$C = new PHPExcel_Shared_JAMA_Matrix($this->A);
				$i = 0;

				while ($i < $C->m) {
					$j = 0;

					while ($j < $C->n) {
						$C->A[$i][$j] *= $args[0];
						++$j;
					}

					++$i;
				}

				return $C;
			case 'double':
				$C = new PHPExcel_Shared_JAMA_Matrix($this->m, $this->n);
				$i = 0;

				while ($i < $C->m) {
					$j = 0;

					while ($j < $C->n) {
						$C->A[$i][$j] = $args[0] * $this->A[$i][$j];
						++$j;
					}

					++$i;
				}

				return $C;
			case 'float':
				$C = new PHPExcel_Shared_JAMA_Matrix($this->A);
				$i = 0;

				while ($i < $C->m) {
					$j = 0;

					while ($j < $C->n) {
						$C->A[$i][$j] *= $args[0];
						++$j;
					}

					++$i;
				}

				return $C;
			default:
				throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
				break;
			}

			return NULL;
		}

		throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
	}

	public function power()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
					$M = $args[0];
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentTypeException);
				}

				break;

			case 'array':
				$M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
				break;

			default:
				throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
				break;
			}

			$this->checkMatrixDimensions($M);
			$i = 0;

			while ($i < $this->m) {
				$j = 0;

				while ($j < $this->n) {
					$validValues = true;
					$value = $M->get($i, $j);
					if (is_string($this->A[$i][$j]) && (0 < strlen($this->A[$i][$j])) && !is_numeric($this->A[$i][$j])) {
						$this->A[$i][$j] = trim($this->A[$i][$j], '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($this->A[$i][$j]);
					}

					if (is_string($value) && (0 < strlen($value)) && !is_numeric($value)) {
						$value = trim($value, '"');
						$validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($value);
					}

					if ($validValues) {
						$this->A[$i][$j] = pow($this->A[$i][$j], $value);
					}
					else {
						$this->A[$i][$j] = PHPExcel_Calculation_Functions::NaN();
					}

					++$j;
				}

				++$i;
			}

			return $this;
		}

		throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
	}

	public function concat()
	{
		if (0 < func_num_args()) {
			$args = func_get_args();
			$match = implode(',', array_map('gettype', $args));

			switch ($match) {
			case 'object':
				if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
					$M = $args[0];
				}
				else {
					throw new PHPExcel_Calculation_Exception(self::ArgumentTypeException);
				}
			case 'array':
				$M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
				break;

			default:
				throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
				break;
			}

			$this->checkMatrixDimensions($M);
			$i = 0;

			while ($i < $this->m) {
				$j = 0;

				while ($j < $this->n) {
					$this->A[$i][$j] = trim($this->A[$i][$j], '"') . trim($M->get($i, $j), '"');
					++$j;
				}

				++$i;
			}

			return $this;
		}

		throw new PHPExcel_Calculation_Exception(self::PolymorphicArgumentException);
	}

	public function solve($B)
	{
		if ($this->m == $this->n) {
			$LU = new PHPExcel_Shared_JAMA_LUDecomposition($this);
			return $LU->solve($B);
		}

		$QR = new QRDecomposition($this);
		return $QR->solve($B);
	}

	public function inverse()
	{
		return $this->solve($this->identity($this->m, $this->m));
	}

	public function det()
	{
		$L = new PHPExcel_Shared_JAMA_LUDecomposition($this);
		return $L->det();
	}
}

if (!defined('PHPEXCEL_ROOT')) {
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../../../');
	require PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php';
}

?>
