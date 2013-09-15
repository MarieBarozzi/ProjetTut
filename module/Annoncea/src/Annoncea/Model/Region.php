<?php
namespace Annoncea\Model;

class Region
{
	
	public $id_reg;
    public $lib_reg;

 
    public function exchangeArray($data)
    {
        $this->id_reg  = (!empty($data['id_reg'])) ? $data['id_reg'] : null; 
        $this->lib_reg = (!empty($data['lib_reg'])) ? $data['lib_reg'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
}