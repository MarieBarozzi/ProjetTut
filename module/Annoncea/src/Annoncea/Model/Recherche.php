<?php
namespace Annoncea\Model;

class Recherche {
    
    public $id_rech; 
    public $mail;
    public $recherche; 
    public $rechtitre;
    public $id_reg; 
    public $id_dept; 
    public $id_cat; 
    public $prixmin; 
    public $prixmax;
    public $etat;
    public $type_annonce; 
    
    
    public function exchangeArray($data)
    {
        $this->id_rech  = (!empty($data['id_rech'])) ? $data['id_rech'] : null; 
        $this->mail = (!empty($data['mail'])) ? $data['mail'] : null;
        $this->recherche = (!empty($data['recherche'])) ? $data['recherche'] : null;
        $this->rechtitre = (!empty($data['rechtitre'])) ? $data['rechtitre'] : null;
        $this->id_reg = (!empty($data['id_reg'])) ? $data['id_reg'] : null;
        $this->id_dept = (!empty($data['id_dept'])) ? $data['id_dept'] : null;
        $this->id_cat = (!empty($data['id_cat'])) ? $data['id_cat'] : null;
        $this->prixmin = (!empty($data['prixmin'])) ? $data['prixmin'] : null;
        $this->prixmax = (!empty($data['prixmax'])) ? $data['prixmax'] : null;
        $this->etat = (!empty($data['etat'])) ? $data['etat'] : null;
        $this->type_annonce = (!empty($data['type_annonce'])) ? $data['type_annonce'] : null;
    
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
}