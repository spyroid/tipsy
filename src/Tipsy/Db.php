<?php
	
namespace Tipsy;

class Db {
	private $_db;
	private $_fields;

	public function __construct($config = []) {
		$db = $this->connect($config);
		$this->_db = $db;
	}

	public function connect($args = null) {
		if (!$args) {
			throw new Exception('Invalid DB config.');
		}

		if (!$args['dsn']) {
			$args['dsn'] = 'mysql:host='.$args['host'].';dbname='.$args['database'].';charset=utf8';
		}
		$db = new \PDO($args['dsn'], $args['user'], $args['pass']);
		$this->_driver = $db->getAttribute(\PDO::ATTR_DRIVER_NAME);

		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		return $db;
	}
	
	public function exec($query) {
		return $this->db()->exec($query);
	}

	public function query($query, $args = []) {
		$stmt = $this->db()->prepare($query);
		$stmt->execute($args);
		//$db->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql'
		return $stmt;
	}
	
	public function get($query, $args = [], $type = 'object') {
		$stmt = $this->query($query, $args);
		return $stmt->fetchAll($type == 'object' ? \PDO::FETCH_OBJ : \PDO::FETCH_ASSOC);
	}
	
	public function db() {
		return $this->_db;
	}
	
	public function fields($table, $fields = null) {
		if ($table && $fields) {
			$this->_fields[$table] = $fields;
		}
		return $this->_fields[$table];
	}
	
	public function driver() {
		return $this->_driver;
	}
}