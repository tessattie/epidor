<?php

class user extends Model
{
	protected $db;

	public function __construct()
	{
		parent::__construct();
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function getUsers()
	{
		$SQL = "SELECT * FROM utilisateurs ORDER BY lastname";
		$result = $this->db->query($SQL);
		return $result->fetchall(PDO::FETCH_BOTH);
	}

	public function createView()
	{
		$query = $this->db->prepare("CREATE OR REPLACE VIEW synthese AS (
				select `t`.`id`, `t`.`client_id`,`t`.`utilisateur_id`, `t`.`date`, 
				`t`.`transaction_id`, `d`.`amount` AS `debit`,`d`.`description`,
				sum((`c`.`price` * `c`.`quantity`)) AS `total`,
				((sum((`c`.`price` * `c`.`quantity`)) * 15) / 100) AS `comission`,
				`cl`.`firstname` AS `client_firstname`,`cl`.`lastname` AS `client_lastname`,
				`u`.`firstName` AS `user_firstname`,`u`.`lastName` AS `user_lastname`,
				sum(`ca`.`price` * `ca`.`quantity`) AS `total_cash` 
				from `transactions` `t` 
				left join `debit` `d` on `d`.`transaction_id` = `t`.`id`
				left join `credit` `c` on `c`.`transaction_id` = `t`.`id`
				left join `utilisateurs` `u` on `u`.`id` = `t`.`utilisateur_id`
				left join `clients` `cl` on `cl`.`id` = `t`.`client_id`
				left join `cash` `ca` on `ca`.`transaction_id` = `t`.`id` 
				group by `c`.`transaction_id`,`ca`.`transaction_id`,`d`.`transaction_id`)");

		return $query->execute();
	}

	public function isUsername($username)
	{
		$query = $this->db->prepare("SELECT password FROM utilisateurs WHERE username = ? AND status = 1");
		$query->execute(array($username));
		$result = $query->fetch(PDO::FETCH_BOTH);
		return $result['password'];
	}

	public function isUnique($username)
	{
		$query = $this->db->prepare("SELECT * FROM utilisateurs WHERE username = :username", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$query->execute(array(':username' => $username));
		return $query->fetch(PDO::FETCH_BOTH);
	}

	public function getUser($username, $password)
	{
		$query = $this->db->prepare("SELECT * FROM utilisateurs WHERE username = ? AND password = ?");
		$result = $query->execute(array($username, $password));
		return $query->fetch(PDO::FETCH_BOTH);
	}

	public function getUseraccess($id)
	{
		$query = $this->db->prepare("SELECT * FROM utilisateurs WHERE id = ?");
		$query->execute(array($id));
		$result = $query->fetch(PDO::FETCH_BOTH);
		return $result['access'];
	}

	public function getUserById($id, $firstname, $lastname, $username, $access)
	{
		$query = $this->db->prepare("SELECT * FROM utilisateurs WHERE id = ? AND firstname = ? AND lastname = ? 
			   	AND access = ? AND username = ?");
		$query->execute(array($id, $firstname, $lastname, $username, $access));
		return $query->fetch(PDO::FETCH_BOTH);
	}

	public function setUser($user)
	{
		$insert = $this->db->prepare("INSERT INTO utilisateurs (firstname, lastname, username, password, access, status)
	    VALUES (:firstname, :lastname, :username, :password, :access, :status)");

	    $insert->bindParam(':firstname', $user['firstname']);
	    $insert->bindParam(':lastname', $user['lastname']);
	    $insert->bindParam(':username', $user['username']);
	    $insert->bindParam(':password', $user['password']);
	    $insert->bindParam(':access', $user['access']);
	    $insert->bindParam(':status', $user['status']);
	    if($insert->execute())
	    {
	    	return $this->db->lastInsertId();
	    }
	    else
	    {
	    	return false;
	    }
	}

	public function deleteUser($id)
	{
		$query = $this->db->prepare("DELETE FROM utilisateurs WHERE id = ?");
		return $query->execute(array($id));
	}

	public function updateUser($field, $value, $id)
	{
		
	}

	public function setPassword($id, $password)
	{
		$query = $this->db->prepare("UPDATE utilisateurs SET password = ? WHERE id =?");
		return $query->execute(array($password, $id));
	}
}