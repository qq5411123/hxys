<?php
// ��������������Ƽ����޹�˾(����֧��)
function Qiniu_RS_Put($self, $bucket, $key, $body, $putExtra)
{
	$putPolicy = new Qiniu_RS_PutPolicy($bucket . ':' . $key);
	$upToken = $putPolicy->Token($self->Mac);
	return Qiniu_Put($upToken, $key, $body, $putExtra);
}

function Qiniu_RS_PutFile($self, $bucket, $key, $localFile, $putExtra)
{
	$putPolicy = new Qiniu_RS_PutPolicy($bucket . ':' . $key);
	$upToken = $putPolicy->Token($self->Mac);
	return Qiniu_PutFile($upToken, $key, $localFile, $putExtra);
}

function Qiniu_RS_Rput($self, $bucket, $key, $body, $fsize, $putExtra)
{
	$putPolicy = new Qiniu_RS_PutPolicy($bucket . ':' . $key);
	$upToken = $putPolicy->Token($self->Mac);

	if ($putExtra == NULL) {
		$putExtra = new Qiniu_Rio_PutExtra($bucket);
	}
	else {
		$putExtra->Bucket = $bucket;
	}

	return Qiniu_Rio_Put($upToken, $key, $body, $fsize, $putExtra);
}

function Qiniu_RS_RputFile($self, $bucket, $key, $localFile, $putExtra)
{
	$putPolicy = new Qiniu_RS_PutPolicy($bucket . ':' . $key);
	$upToken = $putPolicy->Token($self->Mac);

	if ($putExtra == NULL) {
		$putExtra = new Qiniu_Rio_PutExtra($bucket);
	}
	else {
		$putExtra->Bucket = $bucket;
	}

	return Qiniu_Rio_PutFile($upToken, $key, $localFile, $putExtra);
}

require_once 'rs.php';
require_once 'io.php';
require_once 'resumable_io.php';

?>
