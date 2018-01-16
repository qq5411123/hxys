<?php
// 唐上美联佳网络科技有限公司(技术支持)
class LeanFileTest extends PHPUnit_Framework_TestCase
{
	static public function setUpBeforeClass()
	{
		\LeanCloud\LeanClient::initialize(getenv('LC_APP_ID'), getenv('LC_APP_KEY'), getenv('LC_APP_MASTER_KEY'));
		\LeanCloud\LeanClient::useRegion(getenv('LC_API_REGION'));
	}

	public function testInitializeEmptyFileName()
	{
		$file = new \LeanCloud\LeanFile('');
		$this->assertEquals('', $file->getName());
	}

	public function testInitializeMimeType()
	{
		$file = new \LeanCloud\LeanFile('test.txt');
		$this->assertEquals('text/plain', $file->getMimeType());
		$file = new \LeanCloud\LeanFile('test.txt', NULL, 'image/png');
		$this->assertEquals('image/png', $file->getMimeType());
	}

	public function testCreateWithURL()
	{
		$file = \LeanCloud\LeanFile::createWithUrl('blabla.png', 'https://leancloud.cn/favicon.png');
		$this->assertEquals('blabla.png', $file->getName());
		$this->assertEquals('https://leancloud.cn/favicon.png', $file->getUrl());
		$this->assertEquals('image/png', $file->getMimeType());
	}

	public function testSaveTextFile()
	{
		$file = \LeanCloud\LeanFile::createWithData('test.txt', 'Hello World!');
		$file->save();
		$this->assertNotEmpty($file->getObjectId());
		$this->assertNotEmpty($file->getUrl());
		$this->assertNotEmpty($file->getName());
		$this->assertEquals('text/plain', $file->getMimeType());
		$content = file_get_contents($file->getUrl());
		$this->assertEquals('Hello World!', $content);
		$file->destroy();
	}

	public function testSaveUTF8TextFile()
	{
		$file = \LeanCloud\LeanFile::createWithData('test.txt', '你好，中国!');
		$file->save();
		$this->assertNotEmpty($file->getUrl());
		$this->assertEquals('text/plain', $file->getMimeType());
		$content = file_get_contents($file->getUrl());
		$this->assertEquals('你好，中国!', $content);
		$file->destroy();
	}

	public function testFetchFile()
	{
		$file = \LeanCloud\LeanFile::createWithData('test.txt', '你好，中国!');
		$file->save();
		$file2 = \LeanCloud\LeanFile::fetch($file->getObjectId());
		$this->assertEquals($file->getUrl(), $file2->getUrl());
		$this->assertEquals($file->getName(), $file2->getName());
		$this->assertEquals($file->getSize(), $file2->getSize());
		$file->destroy();
	}

	public function testGetCreatedAtAndUpdatedAt()
	{
		$file = \LeanCloud\LeanFile::createWithData('test.txt', '你好，中国!');
		$file->save();
		$this->assertNotEmpty($file->getUrl());
		$this->assertNotEmpty($file->getCreatedAt());
		$this->assertTrue($file->getCreatedAt() instanceof DateTime);
		$file->destroy();
	}

	public function testMetaData()
	{
		$file = \LeanCloud\LeanFile::createWithData('test.txt', '你好，中国!');
		$file->setMeta('language', 'zh-CN');
		$file->setMeta('bool', false);
		$file->setMeta('downloads', 100);
		$file->save();
		$file2 = \LeanCloud\LeanFile::fetch($file->getObjectId());
		$this->assertEquals('zh-CN', $file2->getMeta('language'));
		$this->assertEquals(false, $file2->getMeta('bool'));
		$this->assertEquals(100, $file2->getMeta('downloads'));
		$file->destroy();
	}
}

?>
