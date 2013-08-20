<?php
namespace Annoncea\Model;

class Utilisateur
{
    
    public $mail; 
    public $pseudo; 
    public $rang; 
    public $mdp; 
    public $avatar; 
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
        $this->pseudo = (!empty($data['pseudo'])) ? $data['pseudo'] : null;
        $this->rang = (!empty($data['rang'])) ? $data['rang'] : null;
        $this->mdp = (!empty($data['mdp'])) ? $data['mdp'] : null;
        $this->avatar = (!empty($data['avatar'])) ? $data['avatar'] : null;
        $this->nom = (!empty($data['nom'])) ? $data['nom'] : null;
        $this->prenom = (!empty($data['prenom'])) ? $data['prenom'] : null;
        $this->statut = (!empty($data['statut'])) ? $data['statut'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
        $this->adresse = (!empty($data['adresse'])) ? $data['adresse'] : null;
        $this->cp = (!empty($data['cp'])) ? $data['cp'] : null;
        $this->ville = (!empty($data['ville'])) ? $data['ville'] : null;
        $this->id_dept = (!empty($data['id_dept'])) ? $data['id_dept'] : null;
        $this->tel = (!empty($data['tel'])) ? $data['tel'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
}