<?php
namespace Annoncea\Model;
/*
 * donne la structure d'une ligne de la table annonce 
 */


class Annonce
{
	
	public $id_annonce;
	public $titre;
	public $descr;	
	public $type_annonce;	
	public $prix;
	public $etat; 
	public $date_crea; 
	public $date_modif;
	public $visible; 
	public $id_cat; 
	public $id_dept; 
	public $mail_auteur; 
    public $id_reg;

 
    public function exchangeArray($data)
    {
        $this->id_annonce  = (!empty($data['id_annonce'])) ? $data['id_annonce'] : null; /*si la clé id correspond à une valeur on prend cette valeurr là)*/
        $this->titre = (!empty($data['titre'])) ? $data['titre'] : null;
        $this->descr = (!empty($data['descr'])) ? $data['descr'] : null;
        $this->type_annonce = (!empty($data['type_annonce'])) ? $data['type_annonce'] : null;
        $this->prix = (!empty($data['prix'])) ? $data['prix'] : null;
        $this->etat = (!empty($data['etat'])) ? $data['etat'] : null;
        $this->date_crea = (!empty($data['date_crea'])) ? $data['date_crea'] : null;
        $this->date_modif = (!empty($data['date_modif'])) ? $data['date_modif'] : null;
        $this->visible = (!empty($data['visible'])) ? $data['visible'] : null;
        $this->id_cat = (!empty($data['id_cat'])) ? $data['id_cat'] : null;
        $this->id_dept = (!empty($data['id_dept'])) ? $data['id_dept'] : null;
        $this->mail_auteur = (!empty($data['mail_auteur'])) ? $data['mail_auteur'] : null;
        $this->id_reg = (!empty($data['id_reg'])) ? $data['id_reg'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    
        
    public function pertinent($nom) {
        return true; 
    }
    
    
    
    
}