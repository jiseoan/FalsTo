<?php include 'env.php'; ?>
<?PHP
class XDBClass {
  var $db_ip;
  var $db_port;
  var $db_user;
  var $db_pwd;
  var $db_name;
	var $conn;
	var $result;

	function XDBClass($db_ip, $db_port, $db_user, $db_pwd, $db_name) {
		$this->db_ip = $db_ip;
		$this->db_port = $db_port;
		$this->db_user = $db_user;
		$this->db_pwd = $db_pwd;
		$this->db_name = $db_name;
		$this->conn = null;
    $this->result = null;
	}

	function open() {
		$this->conn = new mysqli($this->db_ip, $this->db_user, $this->db_pwd, $this->db_name, $this->db_port);
		if(mysqli_connect_errno())
		{
			echo 'Error : Could not connect to database. please try again later';
			return false;
		}
		return true;
	}

	function close() {
		$this->conn->close();
    $this->conn = null;
	}
	
	function querySelect($str) {
		$this->result = $this->conn->query($str);
		return ($this->result == null) ? 0 : $this->result->num_rows;
	}

	function goNext() {
		return $this->result->fetch_assoc();
	}

	function free() {
		if ($this->result != null) {
			$this->result->free();
      $this->result = null;
		}
	}
	
	function query($str) {
		$this->conn->query($str);
		return $this->conn->affected_rows;
	}
	
	function queryCount($str, $name) {
		$n = $this->querySelect($str);
		if ($n <= 0) {
			return null;
		}

		$row = $this->goNext();
		$cnt = $row[$name];
		$this->free();
		return $cnt;
	}
	
	function autocommit($auto) {
		return $this->conn->autocommit($auto);
	}
	
	function commit() {
		return $this->conn->commit();
	}
	
	function rollback() {
		return $this->conn->rollback();
	}
	
	function getSingleList($str, $name) {
		$arr = array();
		$n = $this->querySelect($str);
		for ($i = 0 ; $i < $n ; $i++)
		{
			$row = $this->goNext();
			$arr[$i] = $row[$name];
		}
		$this->free();
		return $arr;
	}
	
	function getLangList() {
		$str = "SELECT idlang FROM t_lang order by idlang;";
		return $this->getSingleList($str, "idlang");
	}

	function lookupIndex($arr, $cnt, $dat) {
		for ($i = 0 ; $i < $cnt ; $i++) {
			if ($arr[$i] == $dat) {
				return $i;
			}
		}
		return -1;
	}
}

$db = new XDBClass($db_ip, $db_port, $db_user, $db_pwd, $db_name);
?>
