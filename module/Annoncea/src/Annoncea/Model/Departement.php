<?php
namespace Annoncea\Model;

class Departement
{
	
	public $id_dept;
    public $lib_dept;
    public $id_reg;

 
    public function exchangeArray($data)
    {
        $this->id_dept  = (!empty($data['id_dept'])) ? $data['id_dept'] : null; /*si la clé id correspond à une valeur on prend cette valeurr là)*/
        $this->lib_dept = (!empty($data['lib_dept'])) ? $data['lib_dept'] : null;
        $this->id_reg = (!empty($data['id_reg'])) ? $data['id_reg'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
}