<?php
// 唐上美联佳网络科技有限公司(技术支持)
class CholeskyDecomposition
{
	private $L = array();
	private $m;
	private $isspd = true;

	public function __construct($A = NULL)
	{
		if ($A instanceof Matrix) {
			$this->L = $A->getArray();
			$this->m = $A->getRowDimension();
			$i = 0;

			while ($i < $this->m) {
				$j = $i;

				while ($j < $this->m) {
					$sum = $this->L[$i][$j];
					$k = $i - 1;

					while (0 <= $k) {
						$sum -= $this->L[$i][$k] * $this->L[$j][$k];
						--$k;
					}

					if ($i == $j) {
						if (0 <= $sum) {
							$this->L[$i][$i] = sqrt($sum);
						}
						else {
							$this->isspd = false;
						}
					}
					else {
						if ($this->L[$i][$i] != 0) {
							$this->L[$j][$i] = $sum / $this->L[$i][$i];
						}
					}

					++$j;
				}

				$k = $i + 1;

				while ($k < $this->m) {
					$this->L[$i][$k] = 0;
					++$k;
				}

				++$i;
			}
		}
		else {
			throw new PHPExcel_Calculation_Exception(JAMAError(ArgumentTypeException));
		}
	}

	public function isSPD()
	{
		return $this->isspd;
	}

	public function getL()
	{
		return new Matrix($this->L);
	}

	public function solve($B = NULL)
	{
		if ($B instanceof Matrix) {
			if ($B->getRowDimension() == $this->m) {
				if ($this->isspd) {
					$X = $B->getArrayCopy();
					$nx = $B->getColumnDimension();
					$k = 0;

					while ($k < $this->m) {
						$i = $k + 1;

						while ($i < $this->m) {
							$j = 0;

							while ($j < $nx) {
								$X[$i][$j] -= $X[$k][$j] * $this->L[$i][$k];
								++$j;
							}

							++$i;
						}

						$j = 0;

						while ($j < $nx) {
							$X[$k][$j] /= $this->L[$k][$k];
							++$j;
						}

						++$k;
					}

					$k = $this->m - 1;

					while (0 <= $k) {
						$j = 0;

						while ($j < $nx) {
							$X[$k][$j] /= $this->L[$k][$k];
							++$j;
						}

						$i = 0;

						while ($i < $k) {
							$j = 0;

							while ($j < $nx) {
								$X[$i][$j] -= $X[$k][$j] * $this->L[$k][$i];
								++$j;
							}

							++$i;
						}

						--$k;
					}

					return new Matrix($X, $this->m, $nx);
				}

				throw new PHPExcel_Calculation_Exception(JAMAError(MatrixSPDException));
				return NULL;
			}

			throw new PHPExcel_Calculation_Exception(JAMAError(MatrixDimensionException));
			return NULL;
		}

		throw new PHPExcel_Calculation_Exception(JAMAError(ArgumentTypeException));
	}
}


?>
