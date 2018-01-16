<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_DocumentProperties
{
	const PROPERTY_TYPE_BOOLEAN = 'b';
	const PROPERTY_TYPE_INTEGER = 'i';
	const PROPERTY_TYPE_FLOAT = 'f';
	const PROPERTY_TYPE_DATE = 'd';
	const PROPERTY_TYPE_STRING = 's';
	const PROPERTY_TYPE_UNKNOWN = 'u';

	private $_creator = 'Unknown Creator';
	private $_lastModifiedBy;
	private $_created;
	private $_modified;
	private $_title = 'Untitled Spreadsheet';
	private $_description = '';
	private $_subject = '';
	private $_keywords = '';
	private $_category = '';
	private $_manager = '';
	private $_company = 'Microsoft Corporation';
	private $_customProperties = array();

	public function __construct()
	{
		$this->_lastModifiedBy = $this->_creator;
		$this->_created = time();
		$this->_modified = time();
	}

	public function getCreator()
	{
		return $this->_creator;
	}

	public function setCreator($pValue = '')
	{
		$this->_creator = $pValue;
		return $this;
	}

	public function getLastModifiedBy()
	{
		return $this->_lastModifiedBy;
	}

	public function setLastModifiedBy($pValue = '')
	{
		$this->_lastModifiedBy = $pValue;
		return $this;
	}

	public function getCreated()
	{
		return $this->_created;
	}

	public function setCreated($pValue = NULL)
	{
		if ($pValue === NULL) {
			$pValue = time();
		}
		else {
			if (is_string($pValue)) {
				if (is_numeric($pValue)) {
					$pValue = intval($pValue);
				}
				else {
					$pValue = strtotime($pValue);
				}
			}
		}

		$this->_created = $pValue;
		return $this;
	}

	public function getModified()
	{
		return $this->_modified;
	}

	public function setModified($pValue = NULL)
	{
		if ($pValue === NULL) {
			$pValue = time();
		}
		else {
			if (is_string($pValue)) {
				if (is_numeric($pValue)) {
					$pValue = intval($pValue);
				}
				else {
					$pValue = strtotime($pValue);
				}
			}
		}

		$this->_modified = $pValue;
		return $this;
	}

	public function getTitle()
	{
		return $this->_title;
	}

	public function setTitle($pValue = '')
	{
		$this->_title = $pValue;
		return $this;
	}

	public function getDescription()
	{
		return $this->_description;
	}

	public function setDescription($pValue = '')
	{
		$this->_description = $pValue;
		return $this;
	}

	public function getSubject()
	{
		return $this->_subject;
	}

	public function setSubject($pValue = '')
	{
		$this->_subject = $pValue;
		return $this;
	}

	public function getKeywords()
	{
		return $this->_keywords;
	}

	public function setKeywords($pValue = '')
	{
		$this->_keywords = $pValue;
		return $this;
	}

	public function getCategory()
	{
		return $this->_category;
	}

	public function setCategory($pValue = '')
	{
		$this->_category = $pValue;
		return $this;
	}

	public function getCompany()
	{
		return $this->_company;
	}

	public function setCompany($pValue = '')
	{
		$this->_company = $pValue;
		return $this;
	}

	public function getManager()
	{
		return $this->_manager;
	}

	public function setManager($pValue = '')
	{
		$this->_manager = $pValue;
		return $this;
	}

	public function getCustomProperties()
	{
		return array_keys($this->_customProperties);
	}

	public function isCustomPropertySet($propertyName)
	{
		return isset($this->_customProperties[$propertyName]);
	}

	public function getCustomPropertyValue($propertyName)
	{
		if (isset($this->_customProperties[$propertyName])) {
			return $this->_customProperties[$propertyName]['value'];
		}
	}

	public function getCustomPropertyType($propertyName)
	{
		if (isset($this->_customProperties[$propertyName])) {
			return $this->_customProperties[$propertyName]['type'];
		}
	}

	public function setCustomProperty($propertyName, $propertyValue = '', $propertyType = NULL)
	{
		if (($propertyType === NULL) || !in_array($propertyType, array(self::PROPERTY_TYPE_INTEGER, self::PROPERTY_TYPE_FLOAT, self::PROPERTY_TYPE_STRING, self::PROPERTY_TYPE_DATE, self::PROPERTY_TYPE_BOOLEAN))) {
			if ($propertyValue === NULL) {
				$propertyType = self::PROPERTY_TYPE_STRING;
			}
			else if (is_float($propertyValue)) {
				$propertyType = self::PROPERTY_TYPE_FLOAT;
			}
			else if (is_int($propertyValue)) {
				$propertyType = self::PROPERTY_TYPE_INTEGER;
			}
			else if (is_bool($propertyValue)) {
				$propertyType = self::PROPERTY_TYPE_BOOLEAN;
			}
			else {
				$propertyType = self::PROPERTY_TYPE_STRING;
			}
		}

		$this->_customProperties[$propertyName] = array('value' => $propertyValue, 'type' => $propertyType);
		return $this;
	}

	public function __clone()
	{
		$vars = get_object_vars($this);

		foreach ($vars as $key => $value) {
			if (is_object($value)) {
				$this->$key = clone $value;
			}
			else {
				$this->$key = $value;
			}
		}
	}

	static public function convertProperty($propertyValue, $propertyType)
	{
		switch ($propertyType) {
		case 'empty':
			return '';
		case 'null':
			return NULL;
		case 'i1':
		case 'i2':
		case 'i4':
		case 'i8':
		case 'int':
			return (int) $propertyValue;
		case 'ui1':
		case 'ui2':
		case 'ui4':
		case 'ui8':
		case 'uint':
			return abs((int) $propertyValue);
		case 'r4':
		case 'r8':
		case 'decimal':
			return (double) $propertyValue;
		case 'lpstr':
		case 'lpwstr':
		case 'bstr':
			return $propertyValue;
		case 'date':
		case 'filetime':
			return strtotime($propertyValue);
		case 'bool':
			return $propertyValue == 'true' ? true : false;
		case 'cy':
		case 'error':
		case 'vector':
		case 'array':
		case 'blob':
		case 'oblob':
		case 'stream':
		case 'ostream':
		case 'storage':
		case 'ostorage':
		case 'vstream':
		case 'clsid':
		case 'cf':
			return $propertyValue;
		}

		return $propertyValue;
	}

	static public function convertPropertyType($propertyType)
	{
		switch ($propertyType) {
		case 'i1':
		case 'i2':
		case 'i4':
		case 'i8':
		case 'int':
		case 'ui1':
		case 'ui2':
		case 'ui4':
		case 'ui8':
		case 'uint':
			return self::PROPERTY_TYPE_INTEGER;
		case 'r4':
		case 'r8':
		case 'decimal':
			return self::PROPERTY_TYPE_FLOAT;
		case 'empty':
		case 'null':
		case 'lpstr':
		case 'lpwstr':
		case 'bstr':
			return self::PROPERTY_TYPE_STRING;
		case 'date':
		case 'filetime':
			return self::PROPERTY_TYPE_DATE;
		case 'bool':
			return self::PROPERTY_TYPE_BOOLEAN;
		case 'cy':
		case 'error':
		case 'vector':
		case 'array':
		case 'blob':
		case 'oblob':
		case 'stream':
		case 'ostream':
		case 'storage':
		case 'ostorage':
		case 'vstream':
		case 'clsid':
		case 'cf':
			return self::PROPERTY_TYPE_UNKNOWN;
		}

		return self::PROPERTY_TYPE_UNKNOWN;
	}
}


?>
