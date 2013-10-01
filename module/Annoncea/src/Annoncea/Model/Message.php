<?php
namespace Annoncea\Model;
/*
 * donne la structure d'une ligne de la table message
 */


class Message
{
	
	public $id_mess;
	public $contenu;
	public $objet;	
	public $date_mess;	
	public $mail_dest;
	public $mail_exp; 

 
    public function exchangeArray($data)
    {
        $this->id_mess  = (!empty($data['id_mess'])) ? $data['id_mess'] : null; /*si la clé id correspond à une valeur on prend cette valeurr là)*/
        $this->contenu = (!empty($data['contenu'])) ? $data['contenu'] : null;
        $this->objet = (!empty($data['objet'])) ? $data['objet'] : null;
        $this->date_mess = (!empty($data['date_mess'])) ? $data['date_mess'] : null;
        $this->mail_dest = (!empty($data['mail_dest'])) ? $data['mail_dest'] : null;
        $this->mail_exp = (!empty($data['mail_exp'])) ? $data['mail_exp'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
}
>