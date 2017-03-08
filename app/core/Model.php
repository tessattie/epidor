<?php

Class Model{

	protected $db;

	public function __construct()
	{
		$this->db = new PDO('mysql:host=localhost;dbname=epidor','root','');
		// $this->db = new PDO('mysql:host=localhost;dbname=espaceep_epidor','espaceep_tess','olbUdces1');
	}

	public function update($model, $field, $id, $value)
	{
		$query = $this->db->prepare("UPDATE ".$model." SET ".$field." = ? WHERE id =?");
		return $query->execute(array($value, $id));
	}
}