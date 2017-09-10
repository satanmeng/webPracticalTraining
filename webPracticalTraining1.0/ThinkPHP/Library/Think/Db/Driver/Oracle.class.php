<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace Think\Db\Driver;
use PDO;
use Think\Db\Driver;

/**
 * Oracle数据库驱动
 */
class Oracle extends Driver {

	private $table = '';
	protected $selectSql = 'SELECT * FROM (SELECT thinkphp.*, rownum AS numrow FROM (SELECT  %DISTINCT% %FIELD% FROM %TABLE%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%) thinkphp ) %LIMIT%%COMMENT%';

	/**
	 * 解析pdo连接的dsn信息
	 * @access public
	 * @param array $config 连接信息
	 * @return string
	 */
	protected function parseDsn($config) {
		$dsn = 'oci:dbname=//' . $config['hostname'] . ($config['hostport'] ? ':' . $config['hostport'] : '') . '/' . $config['database'];
		if (!empty($config['charset'])) {
			$dsn .= ';charset=' . $config['charset'];
		}
		return $dsn;
	}

	/**
	 * 执行语句
	 * @access public
	 * @param string $str  sql指令
	 * @param boolean $fetchSql  不执行只是获取SQL
	 * @return integer
	 */
	public function execute($str, $fetchSql = false) {
		$bind = $this->bind; //TODO 新增 修复bug 修复及支持获取自增Id的上次记录 by czw
		$this->initConnect(true);
		if (!$this->_linkID) {
			return false;
		}

		$this->queryStr = $str;
		if (!empty($this->bind)) {
			$that = $this;
			$this->queryStr = strtr($this->queryStr, array_map(function ($val) use ($that) {return '\'' . $that->escapeString($val) . '\'';}, $this->bind));
		}
		if ($fetchSql) {
			return $this->queryStr;
		}
		$flag = false;
		if (preg_match("/^\s*(INSERT\s+INTO)\s+(\w+)\s+/i", $str, $match)) {
			//$this->table = C("DB_SEQUENCE_PREFIX").str_ireplace(C("DB_PREFIX"), "", $match[2]);
			$this->table = C("DB_SEQUENCE_PREFIX") . str_ireplace(C("DB_PREFIX"), "", $match[2]) . C("DB_SEQUENCE_SUFFIX"); //TODO 新增 扩展队列名加后缀 修复bug 修复及支持获取自增Id的上次记录 by czw
			$flag = (boolean) $this->query("SELECT * FROM user_sequences WHERE sequence_name='" . strtoupper($this->table) . "'");
		}
		//释放前次的查询结果
		if (!empty($this->PDOStatement)) {
			$this->free();
		}

		$this->executeTimes++;
		N('db_write', 1); // 兼容代码
		// 记录开始执行时间
		$this->debug(true);
		$this->PDOStatement = $this->_linkID->prepare($str);
		if (false === $this->PDOStatement) {
			$this->error();
			return false;
		}
		$this->bind = $this->bind ? $this->bind : $bind; //TODO 新增 修复bug 修复及支持获取自增Id的上次记录 by czw
		foreach ($this->bind as $key => $val) {
			if (is_array($val)) {
				$this->PDOStatement->bindValue($key, $val[0], $val[1]);
			} else {
				$this->PDOStatement->bindValue($key, $val);
			}
		}
		$this->bind = array();
		$result = $this->PDOStatement->execute();
		$this->debug(false);
		if (false === $result) {
			$this->error();
			return false;
		} else {
			$this->numRows = $this->PDOStatement->rowCount();
			if ($flag || preg_match("/^\s*(INSERT\s+INTO|REPLACE\s+INTO)\s+/i", $str)) {
				//$this->lastInsID = $this->_linkID->lastInsertId(); //
				$this->lastInsID = $this->lastInsertId($this->table); //TODO  修复bug 修复及支持获取自增Id的上次记录 by czw
			}
			return $this->numRows;
		}
	}
	/**
	 * TODO
	 * 2016-05-09 新增方法 修复bug 修复及支持获取自增Id的上次记录 by czw
	 * 取得Oracle最近插入的ID
	 * @access public
	 */
	public function lastInsertId($sequence = '') {
		try {
			$lastInsID = $this->_linkID->lastInsertId();
		} catch (\PDOException $e) {
			//对于驱动不支持PDO::lastInsertId()的情况
			try {
				$lastInsID = 0;
				$seqPrefix = C("DB_SEQUENCE_PREFIX") ? C("DB_SEQUENCE_PREFIX") : 'seq_';
				$seqSuffix = C("DB_SEQUENCE_SUFFIX") ? C("DB_SEQUENCE_SUFFIX") : '';
				$sequence = strtoupper($sequence ? $sequence : $seqPrefix . $this->table . $seqSuffix);
				$q = $this->query("SELECT {$sequence}.CURRVAL as t FROM DUAL");
				if ($q) {
					$lastInsID = $q[0]['t'];
				}
			} catch (\Exception $e) {
				//print "Error!: " . $e->getMessage() . "</br>";
				//exit;
			}
		}
		return $lastInsID;
	}
	/**
	 * 取得数据表的字段信息
	 * @access public
	 */
	public function getFields($tableName) {
		list($tableName) = explode(' ', $tableName);
		$result = $this->query("select a.column_name,data_type,decode(nullable,'Y',0,1) notnull,data_default,decode(a.column_name,b.column_name,1,0) pk "
			. "from user_tab_columns a,(select column_name from user_constraints c,user_cons_columns col "
			. "where c.constraint_name=col.constraint_name and c.constraint_type='P'and c.table_name='" . strtoupper($tableName)
			. "') b where table_name='" . strtoupper($tableName) . "' and a.column_name=b.column_name(+)");
		$info = array();
		if ($result) {
			foreach ($result as $key => $val) {
				$info[strtolower($val['column_name'])] = array(
					'name' => strtolower($val['column_name']),
					'type' => strtolower($val['data_type']),
					'notnull' => $val['notnull'],
					'default' => $val['data_default'],
					'primary' => $val['pk'],
					'autoinc' => $val['pk'],
				);
			}
		}
		return $info;
	}

	/**
	 * 取得数据库的表信息（暂时实现取得用户表信息）
	 * @access public
	 */
	public function getTables($dbName = '') {
		$result = $this->query("select table_name from user_tables");
		$info = array();
		foreach ($result as $key => $val) {
			$info[$key] = current($val);
		}
		return $info;
	}

	/**
	 * SQL指令安全过滤
	 * @access public
	 * @param string $str  SQL指令
	 * @return string
	 */
	public function escapeString($str) {
		return str_ireplace("'", "''", $str);
	}

	/**
	 * limit
	 * @access public
	 * @return string
	 */
	public function parseLimit($limit) {
		$limitStr = '';
		if (!empty($limit)) {
			$limit = explode(',', $limit);
			if (count($limit) > 1) {
				$limitStr = "(numrow>" . $limit[0] . ") AND (numrow<=" . ($limit[0] + $limit[1]) . ")";
			} else {
				$limitStr = "(numrow>0 AND numrow<=" . $limit[0] . ")";
			}

		}
		return $limitStr ? ' WHERE ' . $limitStr : '';
	}

	/**
	 * 设置锁机制
	 * @access protected
	 * @return string
	 */
	protected function parseLock($lock = false) {
		if (!$lock) {
			return '';
		}

		return ' FOR UPDATE NOWAIT ';
	}

	/**
	 * 随机排序
	 * @access protected
	 * @return string
	 */
	protected function parseRand() {
		return 'DBMS_RANDOM.value';
	}
	/**
	 * 执行查询 返回数据集
	 * @access public
	 * @param string $str  sql指令
	 * @param boolean $fetchSql  不执行只是获取SQL
	 * @return mixed
	 */
	public function query($str, $fetchSql = false) {
		$this->initConnect(false);
		if (!$this->_linkID) {
			return false;
		}

		$this->queryStr = $str;
		if (!empty($this->bind)) {
			$that = $this;
			$this->queryStr = strtr($this->queryStr, array_map(function ($val) use ($that) {return '\'' . $that->escapeString($val) . '\'';}, $this->bind));
		}
		if ($fetchSql) {
			return $this->queryStr;
		}
		//释放前次的查询结果
		if (!empty($this->PDOStatement)) {
			$this->free();
		}

		$this->queryTimes++;
		N('db_query', 1); // 兼容代码
		// 调试开始
		$this->debug(true);
		$this->PDOStatement = $this->_linkID->prepare($str);
		if (false === $this->PDOStatement) {
			$this->error();
			return false;
		}
		foreach ($this->bind as $key => $val) {
			if (is_array($val)) {
				$this->PDOStatement->bindValue($key, $val[0], $val[1]);
			} else {
				$this->PDOStatement->bindValue($key, $val);
			}
		}
		$this->bind = array();
		$result = $this->PDOStatement->execute();
		// 调试结束
		$this->debug(false);
		if (false === $result) {
			$this->error();
			return false;
		} else {
			return $this->getResult();
		}
	}
	/**
	 * TODO 修复资源（clob）的查找 by czw
	 * 获得所有的查询数据
	 * @access private
	 * @return array
	 */
	private function getResult() {
		//返回数据集
		// $result =   $this->PDOStatement->fetchAll(PDO::FETCH_ASSOC);
		while ($row = $this->PDOStatement->fetch(PDO::FETCH_ASSOC)) {
			$clobFields = $this->detectResource($row);
			if (count($clobFields) > 0) {
				$this->retriveResourceRow($row, $clobFields);
			}
			$result[] = $row;
		}
		$this->numRows = count($result);
		return $result;
	}
	//将资源转换成内容 TODO 修复资源（clob）的查找 by czw
	protected function retriveResourceRow(&$row, $clobFields) {
		if (count($clobFields) > 0) {
			foreach ($clobFields as $colName) {
				$row[$colName] = stream_get_contents($row[$colName]);
			}
		}
	}
	//查找资源的字段 TODO 修复资源（clob）的查找 by czw
	protected function detectResource($row) {
		$colNames = array();
		foreach ($row as $key => $val) {
			if (is_resource($val)) {
				$colNames[] = $key;
			}
		}
		return $colNames;
	}
}