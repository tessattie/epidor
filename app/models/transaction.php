<?php 
class transaction extends Model{

	protected $db;

	public function __construct()
	{
		parent::__construct();
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function setTransaction($client, $user, $id)
	{
		$date = date('Y-m-d H:i:s');
		$insert = $this->db->prepare("INSERT INTO transactions (client_id, utilisateur_id, date, transaction_id)
	    VALUES (:client_id, :utilisateur_id, :date, :transaction_id)");

	    $insert->bindParam(':client_id', $client);
	    $insert->bindParam(':utilisateur_id', $user);
	    $insert->bindParam(':date', $date);
	    $insert->bindParam(':transaction_id', $id);
	    
	    if($insert->execute())
	    {
	    	return $this->db->lastInsertId();
	    }
	    else
	    {
	    	return false;
	    }
	}

	public function getCreditDetails($transaction)
	{
		$query = $this->db->prepare("SELECT * FROM fiche
									  WHERE id = ?");

		$query->execute(array($transaction));
		return $query->fetchAll(PDO::FETCH_BOTH);

	}

	public function setDebit($id, $amount, $description)
	{
		$insert = $this->db->prepare("INSERT INTO debit (transaction_id, amount, description)
	    VALUES (:transaction_id, :amount, :description)");

	    $insert->bindParam(':transaction_id', $id);
	    $insert->bindParam(':amount', $amount);
	    $insert->bindParam(':description', $description);
	    
	    if($insert->execute())
	    {
	    	return $this->db->lastInsertId();
	    }
	    else
	    {
	    	return false;
	    }
	}

	public function setCredit($id, $product_id, $quantity, $price)
	{
		$insert = $this->db->prepare("INSERT INTO credit (transaction_id, product_id, quantity, price)
	    VALUES (:transaction_id, :product_id, :quantity, :price)");

	    $insert->bindParam(':transaction_id', $id);
	    $insert->bindParam(':product_id', $product_id);
	    $insert->bindParam(':quantity', $quantity);
	    $insert->bindParam(':price', $price);
	    
	    if($insert->execute())
	    {
	    	return $this->db->lastInsertId();
	    }
	    else
	    {
	    	return false;
	    }
	}

	public function setCash($id, $product_id, $quantity, $price)
	{
		$insert = $this->db->prepare("INSERT INTO cash (transaction_id, product_id, quantity, price)
	    VALUES (:transaction_id, :product_id, :quantity, :price)");

	    $insert->bindParam(':transaction_id', $id);
	    $insert->bindParam(':product_id', $product_id);
	    $insert->bindParam(':quantity', $quantity);
	    $insert->bindParam(':price', $price);
	    
	    if($insert->execute())
	    {
	    	return $this->db->lastInsertId();
	    }
	    else
	    {
	    	return false;
	    }
	}

	public function isUnique($transaction_id)
	{
		$query = $this->db->prepare("SELECT * FROM transactions WHERE transaction_id = :transaction_id", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$query->execute(array(':transaction_id' => $transaction_id));
		return $query->fetch(PDO::FETCH_BOTH);
	}

	public function getClientBalance($client)
	{
		$query = $this->db->prepare("SELECT IFNULL(SUM(s.comission), 0) - IFNULL(SUM(s.debit), 0) AS balance FROM `synthese` s	
						WHERE s.client_id = ?");
		$query->execute(array($client));
		return $query->fetch(PDO::FETCH_BOTH);
	}

	public function getTotalBalance()
	{
		$query = $this->db->prepare("SELECT IFNULL(SUM(s.comission), 0) - IFNULL(SUM(s.debit), 0) AS balance FROM `synthese` s");
		$query->execute();
		return $query->fetch(PDO::FETCH_BOTH);
	}

	public function getClientDebit($client)
	{
		$query = $this->db->prepare("SELECT * FROM `synthese` 
									 WHERE client_id = ? AND debit > 0
							 		 ORDER BY date DESC");
		$query->execute(array($client));
		return $query->fetchAll(PDO::FETCH_BOTH);
	}

	public function getClientDebitById($id)
	{
		$query = $this->db->prepare("SELECT * FROM `synthese` 
									 WHERE id = ?
							 		 ORDER BY date DESC");
		$query->execute(array($id));
		return $query->fetchAll(PDO::FETCH_BOTH);
	}

	public function getClientCredit($client)
	{
		$query = $this->db->prepare("SELECT * FROM `synthese` 
									 WHERE client_id = ? AND total > 0
							 		 ORDER BY date DESC");
		$query->execute(array($client));
		return $query->fetchAll(PDO::FETCH_BOTH);
	}

	public function getTransactions($from, $to)
	{
		$query = $this->db->prepare("SELECT id, client_id, utilisateur_id, date, transaction_id, total_cash,
									 debit, description, total, comission, client_firstname, client_lastname, 
									 user_firstname, user_lastname, (SELECT IFNULL(SUM(s.comission), 0) 
									 	- IFNULL(SUM(s.debit), 0) AS balance_from FROM `synthese` s WHERE date < ?) AS balance_from, 
									(SELECT IFNULL(SUM(s.comission), 0) 
									 	- IFNULL(SUM(s.debit), 0) AS balance_to FROM `synthese` s WHERE date < ?) AS balance_to
									 FROM `synthese` 
									 WHERE date BETWEEN ? AND ?
							 		 ORDER BY date DESC");
		$query->execute(array($from, $to, $from, $to));
		return $query->fetchAll(PDO::FETCH_BOTH);
	}

	public function getTransactionsByUser($from, $to, $user)
	{
		$query = $this->db->prepare("SELECT id, client_id, utilisateur_id, date, transaction_id, total_cash,
									 debit, description, total, comission, client_firstname, client_lastname, 
									 user_firstname, user_lastname, (SELECT IFNULL(SUM(s.comission), 0) 
									 	- IFNULL(SUM(s.debit), 0) AS balance_from FROM `synthese` s WHERE date < ? AND utilisateur_id = ?) AS balance_from, 
									(SELECT IFNULL(SUM(s.comission), 0) 
									 	- IFNULL(SUM(s.debit), 0) AS balance_to FROM `synthese` s WHERE date < ? AND utilisateur_id = ?) AS balance_to

									 FROM `synthese` 
									 WHERE date BETWEEN ? AND ? AND utilisateur_id = ?
							 		 ORDER BY date DESC");
		$query->execute(array($from, $user, $to, $user, $from, $to, $user));
		return $query->fetchAll(PDO::FETCH_BOTH);
	}

	public function getTransactionsByClient($from, $to, $client)
	{
		$query = $this->db->prepare("SELECT id, client_id, utilisateur_id, date, transaction_id, total_cash,
									 debit, description, total, comission, client_firstname, client_lastname, 
									 user_firstname, user_lastname, (SELECT IFNULL(SUM(s.comission), 0) 
									 	- IFNULL(SUM(s.debit), 0) AS balance_from FROM `synthese` s WHERE date < ? AND client_id = ?) AS balance_from, 
									(SELECT IFNULL(SUM(s.comission), 0) 
									 	- IFNULL(SUM(s.debit), 0) AS balance_to FROM `synthese` s WHERE date < ? AND client_id = ?) AS balance_to

									 FROM `synthese` 
									 WHERE date BETWEEN ? AND ? AND client_id = ?
							 		 ORDER BY date DESC");
		$query->execute(array($from, $client, $to, $client, $from, $to, $client));
		return $query->fetchAll(PDO::FETCH_BOTH);
	}

	public function getTransactionsByClientAndUser($from, $to, $client, $user)
	{
		$query = $this->db->prepare("SELECT id, client_id, utilisateur_id, date, transaction_id, total_cash,
									 debit, description, total, comission, client_firstname, client_lastname, 
									 user_firstname, user_lastname, (SELECT IFNULL(SUM(s.comission), 0) 
									 	- IFNULL(SUM(s.debit), 0) AS balance_from FROM `synthese` s WHERE date < ? AND client_id = ? AND utilisateur_id = ?) AS balance_from, 
									(SELECT IFNULL(SUM(s.comission), 0) 
									 	- IFNULL(SUM(s.debit), 0) AS balance_to FROM `synthese` s WHERE date < ? AND client_id = ? AND utilisateur_id = ?) AS balance_to

									 FROM `synthese` 
									 WHERE date BETWEEN ? AND ? AND client_id = ? AND utilisateur_id = ?
							 		 ORDER BY date DESC");
		$query->execute(array($from, $client, $user, $to, $client, $user, $from, $to, $client, $user));
		return $query->fetchAll(PDO::FETCH_BOTH);
	}

	public function delete($id)
	{
		$query = $this->db->prepare("DELETE FROM transactions WHERE id = ?");
		return $query->execute(array($id));
	}

	public function deleteCredit($id)
	{
		$query = $this->db->prepare("DELETE FROM credit WHERE transaction_id = ?");
		return $query->execute(array($id));
	}

	public function deleteCash($id)
	{
		$query = $this->db->prepare("DELETE FROM cash WHERE transaction_id = ?");
		return $query->execute(array($id));
	}
}