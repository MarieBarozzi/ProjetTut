<?php
namespace Annoncea\Model;

use Zend\Db\TableGateway\TableGateway;

class RechercheTable
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

  public function getRecherche($id_rech)
    {
        $id_rech = (int) $id_rech;
        $rowset = $this->tableGateway->select(array('id_rech' => $id_rech));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id_rech");
        }
        return $row;
    }
    
     public function getByMail($mail)
    {
        $id_rech  = (int) $id_rech;
        $rowset = $this->tableGateway->select(array('id_rech' => $id_rech));
        return $rowset;
    }
    
       public function saveRecherche(Recherche $recherche)
    {
        $data = array(
            'id_rech' => $recherche->id_rech,
            'mail'  => $recherche->mail,
            'recherche' => $recherche->recherche,
            'rechtitre' => $recherche->rechtitre,
            'id_reg' => $recherche->id_reg,
            'id_dept' => $recherche->id_dept,
            'id_cat' => $recherche->id_cat, 
            'prixmin' => $recherche->prixmin,
            'prixmax' => $recherche->prixmax, 
            'etat' => $recherche->etat, 
            'type_annonce' => $recherche->type_annonce,
            
        );

        $id_rech = (int)$recherche->id_rech;
        if ($id_rech == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getRecherche($id_rech)) {
                $this->tableGateway->update($data, array('id_rech' => $id_rech));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
        return $this->tableGateway->getLastInsertValue();
    }


    public function deleteRecherche($id_rech)
    {
        $this->tableGateway->delete(array('id_rech' => $id_rech));
    }
}