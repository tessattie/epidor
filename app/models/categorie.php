<?php 
class Categorie extends Model{

	protected $db;

	public function __construct()
	{
		parent::__construct();
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function setCategory($categorie)
	{
		$insert = $this->db->prepare("INSERT INTO categories (name, status)
	    VALUES (:name, :status)");

	    $insert->bindParam(':name', $categorie['name']);
	    $insert->bindParam(':status', $categorie['status']);
	    if($insert->execute())
	    {
	    	return $this->db->lastInsertId();
	    }
	    else
	    {
	    	return false;
	    }
	}

	public function isUnique($name)
	{
		$query = $this->db->prepare("SELECT * FROM categories WHERE name = :name", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$query->execute(array(':name' => $name));
		return $query->fetch(PDO::FETCH_BOTH);
	}

	public function getActiveCategories()
	{
		$SQL = "SELECT * FROM categories WHERE status = 1 ORDER BY name";
		$result = $this->db->query($SQL);
		return $result->fetchall(PDO::FETCH_BOTH);
	}

	public function getCategories()
	{
		$SQL = "SELECT * FROM categories ORDER BY name";
		$result = $this->db->query($SQL);
		return $result->fetchall(PDO::FETCH_BOTH);
	}

	public function getCategoryName($id)
	{
		$SQL = "SELECT * FROM categories WHERE id='".$id."'";
		$result = $this->db->query($SQL);
		return $result->fetch(PDO::FETCH_BOTH)['name'];
	}

	public function deleteCategory($id)
	{
		$query = $this->db->prepare("DELETE FROM categories WHERE id = ?");
		return $query->execute(array($id));
	}


}