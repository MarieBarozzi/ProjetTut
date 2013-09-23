<?php
namespace Annoncea\Model;


class Favoris
{
    
    public $id_annonce;
    public $mail;
    
    
     public function exchangeArray($data)
    {
        $this->id_annonce  = (!empty($data['id_annonce'])) ? $data['id_annonce'] : null; 
        $this->mail = (!empty($data['mail'])) ? $data['mail'] : null;
        
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
}
