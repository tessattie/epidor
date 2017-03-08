<?php 
class debit extends Model{

	protected $db;

	public function __construct()
	{
		parent::__construct();
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function update($model, $field, $id, $value)
	{
		$query = $this->db->prepare("UPDATE debit SET ".$field." = ? WHERE transaction_id =?");
		return $query->execute(array($value, $id));
	}
}