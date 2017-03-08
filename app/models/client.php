<?php 
class client extends Model{

	protected $db;

	public function __construct()
	{
		parent::__construct();
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function setClient($client)
	{
		$insert = $this->db->prepare("INSERT INTO clients (firstname, lastname, NIF, telephone, plafond, status)
	    VALUES (:firstname, :lastname, :NIF, :telephone, :plafond, :status)");

	    $insert->bindParam(':firstname', $client['firstname']);
	    $insert->bindParam(':lastname', $client['lastname']);
	    $insert->bindParam(':NIF', $client['nif']);
	    $insert->bindParam(':telephone', $client['telephone']);
	    $insert->bindParam(':plafond', $client['plafond']);
	    $insert->bindParam(':status', $client['status']);
	    if($insert->execute())
	    {
	    	return $this->db->lastInsertId();
	    }
	    else
	    {
	    	return false;
	    }
	}

	public function getClients()
	{
		$SQL = "SELECT cl.id, cl.firstname, cl.lastname, cl.telephone, cl.NIF, cl.plafond, cl.status, 
					(SELECT IFNULL(SUM(s.comission), 0) - IFNULL(SUM(s.debit), 0) FROM `synthese` s	
						WHERE s.client_id = cl.id) AS balance
				FROM clients cl ORDER BY lastname";
		$result = $this->db->query($SQL);
		return $result->fetchall(PDO::FETCH_BOTH);
	}

	public function getClient($id)
	{
		$SQL = "SELECT * FROM clients WHERE id = '".$id."'";
		$result = $this->db->query($SQL);
		return $result->fetch(PDO::FETCH_BOTH);
	}


	public function getClientPlafond($id)
	{
		$query = $this->db->prepare("SELECT plafond FROM clients WHERE id = ?");
		$result = $query->execute(array($id));
		return $query->fetch(PDO::FETCH_BOTH)['plafond'];
	}

	public function findClient($firstname, $lastname, $nif)
	{
		$SQL = "SELECT * FROM clients WHERE status = 1 AND (firstname LIKE '%".$firstname."%' 
		OR firstname LIKE '%".$lastname."%' 
		OR firstname LIKE '%".$nif."%' 
		OR lastname LIKE '%".$lastname."%' 
		OR lastname LIKE '%".$nif."%' 
		OR lastname LIKE '%".$firstname."%'
		OR id LIKE '%".$lastname."%' 
		OR id LIKE '%".$nif."%' 
		OR id LIKE '%".$firstname."%' 
		OR nif LIKE '%".$lastname."%' 
		OR nif LIKE '%".$nif."%' 
		OR nif LIKE '%".$firstname."%')";
		$result = $this->db->query($SQL);
		return $result->fetchall(PDO::FETCH_BOTH);
	}

	public function deleteClient($id)
	{
		$query = $this->db->prepare("DELETE FROM clients WHERE id = ?");
		$query->execute(array($id));
	}

	public function isUnique($nif)
	{
		$query = $this->db->prepare("SELECT * FROM clients WHERE NIF = :NIF", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$query->execute(array(':NIF' => $nif));
		return $query->fetch(PDO::FETCH_BOTH);
	}
}