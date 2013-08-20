<?php
namespace Annoncea\Model;

class Utilisateur
{
    
    public $mail; 
    public $pseudo; 
    public $rang; //?
    public $mdp; 
    public $avatar; //?
    public $nom; 
    public $prenom; 
    public $statut;
    public $description; 
    public $adresse; 
    public $cp;
    public $ville; 
    public $id_dept;
    public $tel;

 
    public function exchangeArray($data)
    {
        $this->mail  = (!empty($data['mail'])) ? $data['mail'] : null; 
        $this->titre = (!empty($data['pseudo'])) ? $data['pseudo'] : null;
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
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
}