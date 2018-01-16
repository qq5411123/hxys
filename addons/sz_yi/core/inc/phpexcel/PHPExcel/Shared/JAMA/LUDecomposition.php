<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_Shared_JAMA_LUDecomposition
{
	const MatrixSingularException = 'Can only perform operation on singular matrix.';
	const MatrixSquareException = 'Mismatched Row dimension';

	private $LU = array();
	private $m;
	private $n;
	private $pivsign;
	private $piv = array();

	public function __construct($A)
	{
		if ($A instanceof PHPExcel_Shared_JAMA_Matrix) {
			$this->LU = $A->getArray();
			$this->m = $A->getRowDimension();
			$this->n = $A->getColumnDimension();
			$i = 0;

			while ($i < $this->m) {
				$this->piv[$i] = $i;
				++$i;
			}

			$this->pivsign = 1;
			$LUrowi = $LUcolj = array();
			$j = 0;

			while ($j < $this->n) {
				$i = 0;

				while ($i < $this->m) {
					$LUcolj[$i] = &$this->LU[$i][$j];
					++$i;
				}

				$i = 0;

				while ($i < $this->m) {
					$LUrowi = $this->LU[$i];
					$kmax = min($i, $j);
					$s = 0;
					$k = 0;

					while ($k < $kmax) {
						$s += $LUrowi[$k] * $LUcolj[$k];
						++$k;
					}

					$LUrowi[$j] = $LUcolj[$i] -= $s;
					++$i;
				}

				$p = $j;
				$i = $j + 1;

				while ($i < $this->m) {
					if (abs($LUcolj[$p]) < abs($LUcolj[$i])) {
						$p = $i;
					}

					++$i;
				}

				if ($p != $j) {
					$k = 0;

					while ($k < $this->n) {
						$t = $this->LU[$p][$k];
						$this->LU[$p][$k] = $this->LU[$j][$k];
						$this->LU[$j][$k] = $t;
						++$k;
					}

					$k = $this->piv[$p];
					$this->piv[$p] = $this->piv[$j];
					$this->piv[$j] = $k;
					$this->pivsign = $this->pivsign * -1;
				}

				if (($j < $this->m) && ($this->LU[$j][$j] != 0)) {
					$i = $j + 1;

					while ($i < $this->m) {
						$this->LU[$i][$j] /= $this->LU[$j][$j];
						++$i;
					}
				}

				++$j;
			}
		}
		else {
			throw new PHPExcel_Calculation_Exception(PHPExcel_Shared_JAMA_Matrix::ArgumentTypeException);
		}
	}

	public function getL()
	{
		$i = 0;

		while ($i < $this->m) {
			$j = 0;

			while ($j < $this->n) {
				if ($j < $i) {
					$L[$i][$j] = $this->LU[$i][$j];
				}
				else if ($i == $j) {
					$L[$i][$j] = 1;
				}
				else {
					$L[$i][$j] = 0;
				}

				++$j;
			}

			++$i;
		}

		return new PHPExcel_Shared_JAMA_Matrix($L);
	}

	public function getU()
	{
		$i = 0;

		while ($i < $this->n) {
			$j = 0;

			while ($j < $this->n) {
				if ($i <= $j) {
					$U[$i][$j] = $this->LU[$i][$j];
				}
				else {
					$U[$i][$j] = 0;
				}

				++$j;
			}

			++$i;
		}

		return new PHPExcel_Shared_JAMA_Matrix($U);
	}

	public function getPivot()
	{
		return $this->piv;
	}

	public function getDoublePivot()
	{
		return $this->getPivot();
	}

	public function isNonsingular()
	{
		$j = 0;

		while ($j < $this->n) {
			if ($this->LU[$j][$j] == 0) {
				return false;
			}

			++$j;
		}

		return true;
	}

	public function det()
	{
		if ($this->m == $this->n) {
			$d = $this->pivsign;
			$j = 0;

			while ($j < $this->n) {
				$d *= $this->LU[$j][$j];
				++$j;
			}

			return $d;
		}

		throw new PHPExcel_Calculation_Exception(PHPExcel_Shared_JAMA_Matrix::MatrixDimensionException);
	}

	public function solve($B)
	{
		if ($B->getRowDimension() == $this->m) {
			if ($this->isNonsingular()) {
				$nx = $B->getColumnDimension();
				$X = $B->getMatrix($this->piv, 0, $nx - 1);
				$k = 0;

				while ($k < $this->n) {
					$i = $k + 1;

					while ($i < $this->n) {
						$j = 0;

						while ($j < $nx) {
							$X->A[$i][$j] -= $X->A[$k][$j] * $this->LU[$i][$k];
							++$j;
						}

						++$i;
					}

					++$k;
				}

				$k = $this->n - 1;

				while (0 <= $k) {
					$j = 0;

					while ($j < $nx) {
						$X->A[$k][$j] /= $this->LU[$k][$k];
						++$j;
					}

					$i = 0;

					while ($i < $k) {
						$j = 0;

						while ($j < $nx) {
							$X->A[$i][$j] -= $X->A[$k][$j] * $this->LU[$i][$k];
							++$j;
						}

						++$i;
					}

					--$k;
				}

				return $X;
			}

			throw new PHPExcel_Calculation_Exception(self::MatrixSingularException);
			return NULL;
		}

		throw new PHPExcel_Calculation_Exception(self::MatrixSquareException);
	}
}


?>
