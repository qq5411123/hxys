<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_Shared_JAMA_QRDecomposition
{
	const MatrixRankException = 'Can only perform operation on full-rank matrix.';

	private $QR = array();
	private $m;
	private $n;
	private $Rdiag = array();

	public function __construct($A)
	{
		if ($A instanceof PHPExcel_Shared_JAMA_Matrix) {
			$this->QR = $A->getArrayCopy();
			$this->m = $A->getRowDimension();
			$this->n = $A->getColumnDimension();
			$k = 0;

			while ($k < $this->n) {
				$nrm = 0;
				$i = $k;

				while ($i < $this->m) {
					$nrm = hypo($nrm, $this->QR[$i][$k]);
					++$i;
				}

				if ($nrm != 0) {
					if ($this->QR[$k][$k] < 0) {
						$nrm = 0 - $nrm;
					}

					$i = $k;

					while ($i < $this->m) {
						$this->QR[$i][$k] /= $nrm;
						++$i;
					}

					$this->QR[$k][$k] += 1;
					$j = $k + 1;

					while ($j < $this->n) {
						$s = 0;
						$i = $k;

						while ($i < $this->m) {
							$s += $this->QR[$i][$k] * $this->QR[$i][$j];
							++$i;
						}

						$s = (0 - $s) / $this->QR[$k][$k];
						$i = $k;

						while ($i < $this->m) {
							$this->QR[$i][$j] += $s * $this->QR[$i][$k];
							++$i;
						}

						++$j;
					}
				}

				$this->Rdiag[$k] = 0 - $nrm;
				++$k;
			}
		}
		else {
			throw new PHPExcel_Calculation_Exception(PHPExcel_Shared_JAMA_Matrix::ArgumentTypeException);
		}
	}

	public function isFullRank()
	{
		$j = 0;

		while ($j < $this->n) {
			if ($this->Rdiag[$j] == 0) {
				return false;
			}

			++$j;
		}

		return true;
	}

	public function getH()
	{
		$i = 0;

		while ($i < $this->m) {
			$j = 0;

			while ($j < $this->n) {
				if ($j <= $i) {
					$H[$i][$j] = $this->QR[$i][$j];
				}
				else {
					$H[$i][$j] = 0;
				}

				++$j;
			}

			++$i;
		}

		return new PHPExcel_Shared_JAMA_Matrix($H);
	}

	public function getR()
	{
		$i = 0;

		while ($i < $this->n) {
			$j = 0;

			while ($j < $this->n) {
				if ($i < $j) {
					$R[$i][$j] = $this->QR[$i][$j];
				}
				else if ($i == $j) {
					$R[$i][$j] = $this->Rdiag[$i];
				}
				else {
					$R[$i][$j] = 0;
				}

				++$j;
			}

			++$i;
		}

		return new PHPExcel_Shared_JAMA_Matrix($R);
	}

	public function getQ()
	{
		$k = $this->n - 1;

		while (0 <= $k) {
			$i = 0;

			while ($i < $this->m) {
				$Q[$i][$k] = 0;
				++$i;
			}

			$Q[$k][$k] = 1;
			$j = $k;

			while ($j < $this->n) {
				if ($this->QR[$k][$k] != 0) {
					$s = 0;
					$i = $k;

					while ($i < $this->m) {
						$s += $this->QR[$i][$k] * $Q[$i][$j];
						++$i;
					}

					$s = (0 - $s) / $this->QR[$k][$k];
					$i = $k;

					while ($i < $this->m) {
						$Q[$i][$j] += $s * $this->QR[$i][$k];
						++$i;
					}
				}

				++$j;
			}

			--$k;
		}

		return new PHPExcel_Shared_JAMA_Matrix($Q);
	}

	public function solve($B)
	{
		if ($B->getRowDimension() == $this->m) {
			if ($this->isFullRank()) {
				$nx = $B->getColumnDimension();
				$X = $B->getArrayCopy();
				$k = 0;

				while ($k < $this->n) {
					$j = 0;

					while ($j < $nx) {
						$s = 0;
						$i = $k;

						while ($i < $this->m) {
							$s += $this->QR[$i][$k] * $X[$i][$j];
							++$i;
						}

						$s = (0 - $s) / $this->QR[$k][$k];
						$i = $k;

						while ($i < $this->m) {
							$X[$i][$j] += $s * $this->QR[$i][$k];
							++$i;
						}

						++$j;
					}

					++$k;
				}

				$k = $this->n - 1;

				while (0 <= $k) {
					$j = 0;

					while ($j < $nx) {
						$X[$k][$j] /= $this->Rdiag[$k];
						++$j;
					}

					$i = 0;

					while ($i < $k) {
						$j = 0;

						while ($j < $nx) {
							$X[$i][$j] -= $X[$k][$j] * $this->QR[$i][$k];
							++$j;
						}

						++$i;
					}

					--$k;
				}

				$X = new PHPExcel_Shared_JAMA_Matrix($X);
				return $X->getMatrix(0, $this->n - 1, 0, $nx);
			}

			throw new PHPExcel_Calculation_Exception(self::MatrixRankException);
			return NULL;
		}

		throw new PHPExcel_Calculation_Exception(PHPExcel_Shared_JAMA_Matrix::MatrixDimensionException);
	}
}


?>
