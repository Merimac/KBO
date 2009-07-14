<?php

class schueler extends Db implements Dmlable { 
	
	/*
	 * Prim�rschl�ssel 
	 */
	private $schueler_id;
	/*
	 * Name des Schulfachs
	 * string
	 * 45 Zeichen erlaubt
	 */
	private $vorname;
	private $nachname;
	private $klasse_id;
	private $passwd;

        public $notens;

        public $anwesenheits;
	
	/*
	 * Name des Schulfachs
	 * string
	 * 45 Zeichen erlaubt
	 */
	public function __construct() {
		try {
			parent::__construct();
		}
		catch (MysqlException $e) {
			Html::showAll($e);
	    }
            //Noten alles Schueler werden �bergeben
            $notens = Noten::getAllAsObject();
	}
	/**
	 * @return int
	 */
	public function getId() {
		return $this->schueler_id;
	}
	public function setId($schueler_id) {
		$this->schueler_id = $schueler_id;
	}
	/**
	 * @return string
	 */
	public function getVorname() {
		return $this->vorname;
	}
    /**
	 * @param tring $name
	 */
	public function setVorname($vorname) {
		$this->vorname = $vorname;
	}
	
	public function getNachname() {
		return $this->nachname;
	}

	public function setNachname($nachname) {
		$this->nachname = $nachname;
	}
	
	public function getPasswd() {
		return $this->passwd;
	}

	public function setPasswd($passwd) {
		$this->passwd = $passwd;
	}
	

	/*
	 * speichert das aktuelle Objekt
	 * falls kein Prim�rschl�ssel existiert
	 * wird ein neuer Datensatz erzeugt
	 * anderfalls ein update durchgef�hrt
	 */
	public function save() {
		
		if (isset($this->schueler_id)) {
			$this->update();
		} else {
			$this->insert();
		}
		
	}
	
	public function insert() {
		
		$sql = "INSERT INTO schueler 
					   (schueler_id, name) 
				VALUES ('' 
					   , '" .$this->vorname."');";

		try {
			$success_insert = mysql_query($sql);
			if (!$success_insert) {
				throw new MysqlException();
			}
				
			$insert_id = mysql_insert_id();
			$this->fach_id = $insert_id;	
		} catch (MysqlException $e) {
			Html::showAll($e);
		}
	}
	
	public function update() {
		
		$sql = "UPDATE fach 
				   SET =vorname'" .$this->vorname. "' 
				 WHERE schueler_id=" .$this->schueler_id. ";";
		
		try {
			$success_update = mysql_query($sql);
			if (!$success_update) {
				throw new MysqlException();
			}
		} catch (MysqlException $e) {
			Html::showAll($e);
		}
		
	}
	
	public function delete($id) {
		$sql = "DELETE FROM fach 
			     WHERE schueler_id=" .$id. ";";
		
		try {
			$success_delete = mysql_query($sql);
			if (!$success_delete) {
				throw new MysqlException();
			}
		} catch (MysqlException $e) {
			Html::showAll($e);
		}
	}
	
	public function getAllAsArray($restriction = '') {
		$sql = "SELECT schueler_id
					   ,vorname  
			      FROM schueler 
			     WHERE 1=1 ";
		$sql .= $restriction. ";";
		
		try {
			$result = mysql_query($sql);
			
			if (!$result) {
				throw new MysqlException();
			}
			
			$fachs = array();
			while ($row = mysql_fetch_assoc($result)) {
				$fachs[$row['schueler_id']]['schueler_id'] = $row['schueler_id'];
				$fachs[$row['schueler_id']]["vorname"] = $row['vorname'];
			}		
		} catch (MysqlException $e) {
			Html::showAll($e);
		}
		
		return $fachs;
	}
	
	public function load($id) {
		
		$sql = "SELECT * 
				 FROM schueler
				 WHERE schueler_id="  .$id. ";";
		
		try {
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);
			
			if (empty($row['schueler_id'])) {
				throw new Exception("Datensatz leer: ". $sql);
			}
			
			$this->schueler_id = $row['schueler_id'];
			$this->name = $row['vorname']." ".$row['nachname'];
			
		} catch (MysqlException $e) {
			Html::showAll($e);
		} catch (Exception $e) {
			Html::showAll($e);
		}

	}

        public function loadAnwesenheits() {
            $von='1970-01-01';
            $bis='2070-01-01';
            $a=new Anwesenheit();
            $this->anwesenheits = $a->getAllAsObject($von, $bis, $this);
        }

        public function loadNotens() {
            $n=new Noten();
            $this->notens = $n->getNoten($this->getId());
        }

}

?>