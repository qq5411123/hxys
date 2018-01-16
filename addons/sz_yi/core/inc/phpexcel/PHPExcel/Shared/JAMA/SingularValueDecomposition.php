<?php
// 唐上美联佳网络科技有限公司(技术支持)
class SingularValueDecomposition
{
	private $U = array();
	private $V = array();
	private $s = array();
	private $m;
	private $n;

	public function __construct($Arg)
	{
		$A = $Arg->getArrayCopy();
		$this->m = $Arg->getRowDimension();
		$this->n = $Arg->getColumnDimension();
		$nu = min($this->m, $this->n);
		$e = array();
		$work = array();
		$wantu = true;
		$wantv = true;
		$nct = min($this->m - 1, $this->n);
		$nrt = max(0, min($this->n - 2, $this->m));
		$k = 0;

		while ($k < max($nct, $nrt)) {
			if ($k < $nct) {
				$this->s[$k] = 0;
				$i = $k;

				while ($i < $this->m) {
					$this->s[$k] = hypo($this->s[$k], $A[$i][$k]);
					++$i;
				}

				if ($this->s[$k] != 0) {
					if ($A[$k][$k] < 0) {
						$this->s[$k] = 0 - $this->s[$k];
					}

					$i = $k;

					while ($i < $this->m) {
						$A[$i][$k] /= $this->s[$k];
						++$i;
					}

					$A[$k][$k] += 1;
				}

				$this->s[$k] = 0 - $this->s[$k];
			}

			$j = $k + 1;

			while ($j < $this->n) {
				if (($k < $nct) & ($this->s[$k] != 0)) {
					$t = 0;
					$i = $k;

					while ($i < $this->m) {
						$t += $A[$i][$k] * $A[$i][$j];
						++$i;
					}

					$t = (0 - $t) / $A[$k][$k];
					$i = $k;

					while ($i < $this->m) {
						$A[$i][$j] += $t * $A[$i][$k];
						++$i;
					}

					$e[$j] = $A[$k][$j];
				}

				++$j;
			}

			if ($wantu && ($k < $nct)) {
				$i = $k;

				while ($i < $this->m) {
					$this->U[$i][$k] = $A[$i][$k];
					++$i;
				}
			}

			if ($k < $nrt) {
				$e[$k] = 0;
				$i = $k + 1;

				while ($i < $this->n) {
					$e[$k] = hypo($e[$k], $e[$i]);
					++$i;
				}

				if ($e[$k] != 0) {
					if ($e[$k + 1] < 0) {
						$e[$k] = 0 - $e[$k];
					}

					$i = $k + 1;

					while ($i < $this->n) {
						$e[$i] /= $e[$k];
						++$i;
					}

					$e[$k + 1] += 1;
				}

				$e[$k] = 0 - $e[$k];
				if ((($k + 1) < $this->m) && ($e[$k] != 0)) {
					$i = $k + 1;

					while ($i < $this->m) {
						$work[$i] = 0;
						++$i;
					}

					$j = $k + 1;

					while ($j < $this->n) {
						$i = $k + 1;

						while ($i < $this->m) {
							$work[$i] += $e[$j] * $A[$i][$j];
							++$i;
						}

						++$j;
					}

					$j = $k + 1;

					while ($j < $this->n) {
						$t = (0 - $e[$j]) / $e[$k + 1];
						$i = $k + 1;

						while ($i < $this->m) {
							$A[$i][$j] += $t * $work[$i];
							++$i;
						}

						++$j;
					}
				}

				if ($wantv) {
					$i = $k + 1;

					while ($i < $this->n) {
						$this->V[$i][$k] = $e[$i];
						++$i;
					}
				}
			}

			++$k;
		}

		$p = min($this->n, $this->m + 1);

		if ($nct < $this->n) {
			$this->s[$nct] = $A[$nct][$nct];
		}

		if ($this->m < $p) {
			$this->s[$p - 1] = 0;
		}

		if (($nrt + 1) < $p) {
			$e[$nrt] = $A[$nrt][$p - 1];
		}

		$e[$p - 1] = 0;

		if ($wantu) {
			$j = $nct;

			while ($j < $nu) {
				$i = 0;

				while ($i < $this->m) {
					$this->U[$i][$j] = 0;
					++$i;
				}

				$this->U[$j][$j] = 1;
				++$j;
			}

			$k = $nct - 1;

			while (0 <= $k) {
				if ($this->s[$k] != 0) {
					$j = $k + 1;

					while ($j < $nu) {
						$t = 0;
						$i = $k;

						while ($i < $this->m) {
							$t += $this->U[$i][$k] * $this->U[$i][$j];
							++$i;
						}

						$t = (0 - $t) / $this->U[$k][$k];
						$i = $k;

						while ($i < $this->m) {
							$this->U[$i][$j] += $t * $this->U[$i][$k];
							++$i;
						}

						++$j;
					}

					$i = $k;

					while ($i < $this->m) {
						$this->U[$i][$k] = 0 - $this->U[$i][$k];
						++$i;
					}

					$this->U[$k][$k] = 1 + $this->U[$k][$k];
					$i = 0;

					while ($i < ($k - 1)) {
						$this->U[$i][$k] = 0;
						++$i;
					}
				}
				else {
					$i = 0;

					while ($i < $this->m) {
						$this->U[$i][$k] = 0;
						++$i;
					}

					$this->U[$k][$k] = 1;
				}

				--$k;
			}
		}

		if ($wantv) {
			$k = $this->n - 1;

			while (0 <= $k) {
				if (($k < $nrt) && ($e[$k] != 0)) {
					$j = $k + 1;

					while ($j < $nu) {
						$t = 0;
						$i = $k + 1;

						while ($i < $this->n) {
							$t += $this->V[$i][$k] * $this->V[$i][$j];
							++$i;
						}

						$t = (0 - $t) / $this->V[$k + 1][$k];
						$i = $k + 1;

						while ($i < $this->n) {
							$this->V[$i][$j] += $t * $this->V[$i][$k];
							++$i;
						}

						++$j;
					}
				}

				$i = 0;

				while ($i < $this->n) {
					$this->V[$i][$k] = 0;
					++$i;
				}

				$this->V[$k][$k] = 1;
				--$k;
			}
		}

		$pp = $p - 1;
		$iter = 0;
		$eps = pow(2, -52);

		while (0 < $p) {
			$k = $p - 2;

			while (-1 <= $k) {
				if ($k == -1) {
					break;
				}

				if (abs($e[$k]) <= $eps * (abs($this->s[$k]) + abs($this->s[$k + 1]))) {
					$e[$k] = 0;
					break;
				}

				--$k;
			}

			if ($k == ($p - 2)) {
				$kase = 4;
			}
			else {
				$ks = $p - 1;

				while ($k <= $ks) {
					if ($ks == $k) {
						break;
					}

					$t = ($ks != $p ? abs($e[$ks]) : 0) + ($ks != ($k + 1) ? abs($e[$ks - 1]) : 0);

					if (abs($this->s[$ks]) <= $eps * $t) {
						$this->s[$ks] = 0;
						break;
					}

					--$ks;
				}

				if ($ks == $k) {
					$kase = 3;
				}
				else if ($ks == ($p - 1)) {
					$kase = 1;
				}
				else {
					$kase = 2;
					$k = $ks;
				}
			}

			++$k;

			switch ($kase) {
			case 1:
				$f = $e[$p - 2];
				$e[$p - 2] = 0;
				$j = $p - 2;

				while ($k <= $j) {
					$t = hypo($this->s[$j], $f);
					$cs = $this->s[$j] / $t;
					$sn = $f / $t;
					$this->s[$j] = $t;

					if ($j != $k) {
						$f = (0 - $sn) * $e[$j - 1];
						$e[$j - 1] = $cs * $e[$j - 1];
					}

					if ($wantv) {
						$i = 0;

						while ($i < $this->n) {
							$t = ($cs * $this->V[$i][$j]) + ($sn * $this->V[$i][$p - 1]);
							$this->V[$i][$p - 1] = ((0 - $sn) * $this->V[$i][$j]) + ($cs * $this->V[$i][$p - 1]);
							$this->V[$i][$j] = $t;
							++$i;
						}
					}

					--$j;
				}

				break;

			case 2:
				$f = $e[$k - 1];
				$e[$k - 1] = 0;
				$j = $k;

				while ($j < $p) {
					$t = hypo($this->s[$j], $f);
					$cs = $this->s[$j] / $t;
					$sn = $f / $t;
					$this->s[$j] = $t;
					$f = (0 - $sn) * $e[$j];
					$e[$j] = $cs * $e[$j];

					if ($wantu) {
						$i = 0;

						while ($i < $this->m) {
							$t = ($cs * $this->U[$i][$j]) + ($sn * $this->U[$i][$k - 1]);
							$this->U[$i][$k - 1] = ((0 - $sn) * $this->U[$i][$j]) + ($cs * $this->U[$i][$k - 1]);
							$this->U[$i][$j] = $t;
							++$i;
						}
					}

					++$j;
				}

				break;

			case 3:
				$scale = max(max(max(max(abs($this->s[$p - 1]), abs($this->s[$p - 2])), abs($e[$p - 2])), abs($this->s[$k])), abs($e[$k]));
				$sp = $this->s[$p - 1] / $scale;
				$spm1 = $this->s[$p - 2] / $scale;
				$epm1 = $e[$p - 2] / $scale;
				$sk = $this->s[$k] / $scale;
				$ek = $e[$k] / $scale;
				$b = ((($spm1 + $sp) * ($spm1 - $sp)) + ($epm1 * $epm1)) / 2;
				$c = $sp * $epm1 * $sp * $epm1;
				$shift = 0;
				if (($b != 0) || ($c != 0)) {
					$shift = sqrt(($b * $b) + $c);

					if ($b < 0) {
						$shift = 0 - $shift;
					}

					$shift = $c / ($b + $shift);
				}

				$f = (($sk + $sp) * ($sk - $sp)) + $shift;
				$g = $sk * $ek;
				$j = $k;

				while ($j < ($p - 1)) {
					$t = hypo($f, $g);
					$cs = $f / $t;
					$sn = $g / $t;

					if ($j != $k) {
						$e[$j - 1] = $t;
					}

					$f = ($cs * $this->s[$j]) + ($sn * $e[$j]);
					$e[$j] = ($cs * $e[$j]) - ($sn * $this->s[$j]);
					$g = $sn * $this->s[$j + 1];
					$this->s[$j + 1] = $cs * $this->s[$j + 1];

					if ($wantv) {
						$i = 0;

						while ($i < $this->n) {
							$t = ($cs * $this->V[$i][$j]) + ($sn * $this->V[$i][$j + 1]);
							$this->V[$i][$j + 1] = ((0 - $sn) * $this->V[$i][$j]) + ($cs * $this->V[$i][$j + 1]);
							$this->V[$i][$j] = $t;
							++$i;
						}
					}

					$t = hypo($f, $g);
					$cs = $f / $t;
					$sn = $g / $t;
					$this->s[$j] = $t;
					$f = ($cs * $e[$j]) + ($sn * $this->s[$j + 1]);
					$this->s[$j + 1] = ((0 - $sn) * $e[$j]) + ($cs * $this->s[$j + 1]);
					$g = $sn * $e[$j + 1];
					$e[$j + 1] = $cs * $e[$j + 1];
					if ($wantu && ($j < ($this->m - 1))) {
						$i = 0;

						while ($i < $this->m) {
							$t = ($cs * $this->U[$i][$j]) + ($sn * $this->U[$i][$j + 1]);
							$this->U[$i][$j + 1] = ((0 - $sn) * $this->U[$i][$j]) + ($cs * $this->U[$i][$j + 1]);
							$this->U[$i][$j] = $t;
							++$i;
						}
					}

					++$j;
				}

				$e[$p - 2] = $f;
				$iter = $iter + 1;
				break;

			case 4:
				if ($this->s[$k] <= 0) {
					$this->s[$k] = $this->s[$k] < 0 ? 0 - $this->s[$k] : 0;

					if ($wantv) {
						$i = 0;

						while ($i <= $pp) {
							$this->V[$i][$k] = 0 - $this->V[$i][$k];
							++$i;
						}
					}
				}

				while ($k < $pp) {
					if ($this->s[$k + 1] <= $this->s[$k]) {
						break;
					}

					$t = $this->s[$k];
					$this->s[$k] = $this->s[$k + 1];
					$this->s[$k + 1] = $t;
					if ($wantv && ($k < ($this->n - 1))) {
						$i = 0;

						while ($i < $this->n) {
							$t = $this->V[$i][$k + 1];
							$this->V[$i][$k + 1] = $this->V[$i][$k];
							$this->V[$i][$k] = $t;
							++$i;
						}
					}

					if ($wantu && ($k < ($this->m - 1))) {
						$i = 0;

						while ($i < $this->m) {
							$t = $this->U[$i][$k + 1];
							$this->U[$i][$k + 1] = $this->U[$i][$k];
							$this->U[$i][$k] = $t;
							++$i;
						}
					}

					++$k;
				}

				$iter = 0;
				--$p;
				break;
			}
		}
	}

	public function getU()
	{
		return new Matrix($this->U, $this->m, min($this->m + 1, $this->n));
	}

	public function getV()
	{
		return new Matrix($this->V);
	}

	public function getSingularValues()
	{
		return $this->s;
	}

	public function getS()
	{
		$i = 0;

		while ($i < $this->n) {
			$j = 0;

			while ($j < $this->n) {
				$S[$i][$j] = 0;
				++$j;
			}

			$S[$i][$i] = $this->s[$i];
			++$i;
		}

		return new Matrix($S);
	}

	public function norm2()
	{
		return $this->s[0];
	}

	public function cond()
	{
		return $this->s[0] / $this->s[min($this->m, $this->n) - 1];
	}

	public function rank()
	{
		$eps = pow(2, -52);
		$tol = max($this->m, $this->n) * $this->s[0] * $eps;
		$r = 0;
		$i = 0;

		while ($i < count($this->s)) {
			if ($tol < $this->s[$i]) {
				++$r;
			}

			++$i;
		}

		return $r;
	}
}


?>
