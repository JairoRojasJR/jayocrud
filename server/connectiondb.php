<?php
class Connection
{
	private $sever = 'localhost';
	private $user = USER_SQL;
	private $password = PASS_SQL;
	private $dbname = DB_NAME;

	public function __construct()
	{
		try {
			$this->connection = new PDO("mysql:host=$this->sever;dbname=$this->dbname", $this->user, $this->password);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $execp) {
			echo $execp;
		}
	}

	public function query($sql)
	{
		$sentence = $this->connection->prepare($sql);
		$sentence->execute();
		return $sentence->fetchAll();
	}

	public function executedb($sql)
	{
		$sentence = $this->connection->prepare($sql);
		$sentence->execute();
		return $this->connection->lastInsertId();
	}
}
