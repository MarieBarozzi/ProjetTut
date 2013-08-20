<?php
namespace Annoncea\Model;

use Zend\Db\TableGateway\TableGateway;

class UtilisateurTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getUtilisateur($mail)
    {
        $mail = (string) $mail; //utile car NULL sera casté en ''
        $rowset = $this->tableGateway->select(array('mail' => $mail));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $mail");
        }
        return $row;
    }

    public function saveUtilisateur(Utilisateur $utilisateur)
    {      
        $data = array(
            'mail' => $utilisateur->mail,
            'pseudo' => $utilisateur->pseudo,
            'rang' => $utilisateur->rang,
            'mdp' => $utilisateur->mdp,
            'avatar' => $utilisateur->avatar,
            'nom' => $utilisateur->nom,
            'prenom' => $utilisateur->prenom,
            'statut' => $utilisateur->statut,
            'description' => $utilisateur->description,
            'adresse' => $utilisateur->adresse,
            'cp' => $utilisateur->cp, 
            'ville' => $utilisateur->ville,
            'id_dept' => $utilisateur->id_dept,
            'tel' => $utilisateur->tel,  
        );

        $mail = (string) $utilisateur->mail;
        if ($mail == '') {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUtilisateur($mail)) {
                $this->tableGateway->update($data, array('mail' => $mail));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }


    public function deleteUtilisateur($mail)
    {
        $this->tableGateway->delete(array('mail' => $mail));
    }
}