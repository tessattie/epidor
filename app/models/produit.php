<?php 
class produit extends Model{

	protected $db;

	public function __construct()
	{
		parent::__construct();
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function setProduct($product)
	{
		$insert = $this->db->prepare("INSERT INTO products (name, category_id, price, status)
	    VALUES (:name, :category_id, :price, :status)");

	    $insert->bindParam(':name', $product['name']);
	    $insert->bindParam(':category_id', $product['category_id']);
	    $insert->bindParam(':price', $product['price']);
	    $insert->bindParam(':status', $product['status']);
	    if($insert->execute())
	    {
	    	return $this->db->lastInsertId();
	    }
	    else
	    {
	    	return false;
	    }
	}


	public function getProduits()
	{
		$SQL = "SELECT p.name, p.price, p.category_id, p.status, c.name AS cat_name, p.id
		FROM products p
		LEFT JOIN categories c on c.id = p.category_id
		ORDER BY p.category_id, p.name";
		$result = $this->db->query($SQL);
		return $result->fetchall(PDO::FETCH_BOTH);
	}

	public function getProduitsActif()
	{
		$SQL = "SELECT p.name, p.price, p.category_id, p.status, c.name AS cat_name, p.id
		FROM products p
		LEFT JOIN categories c on c.id = p.category_id
		WHERE p.status = 1
		ORDER BY p.category_id, p.name";
		$result = $this->db->query($SQL);
		return $result->fetchall(PDO::FETCH_BOTH);
	}

	public function updateStatus($status, $category)
	{
		$query = $this->db->prepare("UPDATE products SET status = ? WHERE category_id =?");
		return $query->execute(array($status, $category));
	}

	public function deleteProduct($id)
	{
		$query = $this->db->prepare("DELETE FROM products WHERE id = ?");
		return $query->execute(array($id));
	}

	public function deleteCategory($id)
	{
		$query = $this->db->prepare("DELETE FROM categories WHERE id = ?");
		return $query->execute(array($id));
	}

	public function isUnique($nif)
	{
		$query = $this->db->prepare("SELECT * FROM clients WHERE NIF = :NIF", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$query->execute(array(':NIF' => $nif));
		return $query->fetch(PDO::FETCH_BOTH);
	}

	public function isProductUnique($name, $category_id)
	{
		$query = $this->db->prepare("SELECT * FROM products WHERE name = :name AND category_id = :category_id", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$query->execute(array(':name' => $name, ':category_id' => $category_id));
		return $query->fetch(PDO::FETCH_BOTH);
	}
}