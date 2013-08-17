<?php
namespace Annoncea\Model;

class Categorie
{
    
    public $id_cat;
    public $lib_cat;
 
    public function exchangeArray($data)
    {
        $this->id_cat  = (!empty($data['id_cat'])) ? $data['id_cat'] : null; /*si la clé id correspond à une valeur on prend cette valeurr là)*/
        $this->lib_cat = (!empty($data['lib_cat'])) ? $data['lib_cat'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
}