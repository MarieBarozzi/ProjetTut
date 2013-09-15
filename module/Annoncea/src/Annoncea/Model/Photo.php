<?php
namespace Annoncea\Model;

class Photo {
    
    public $id_photo; 
    public $id_annonce; 
    
    
    public function exchangeArray($data)
    {
        $this->id_photo  = (!empty($data['id_photo'])) ? $data['id_photo'] : null; 
        $this->id_annonce = (!empty($data['id_annonce'])) ? $data['id_annonce'] : null;
    
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
}
   








