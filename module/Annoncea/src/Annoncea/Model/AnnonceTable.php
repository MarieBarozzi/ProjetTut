<?php
namespace Annoncea\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

/*
 * contient le lien avec la table annonce et execute les opérations dessus
*/

class AnnonceTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($orderByDate = false)
    {
        $select = new Select();
        $select->from($this->tableGateway->table);
        if($orderByDate)
            $select->order('date_modif DESC');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;

    }

    public function getAnnonceAuteur($mail_auteur, $orderByDate = false){
        
        $select = new Select();
        $select->from($this->tableGateway->table);
        if($orderByDate)
            $select->order('date_modif DESC');
        $select->where(array('mail_auteur' => $mail_auteur));
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getAnnonce($id_annonce)
    {
        $id_annonce  = (int) $id_annonce;
        $rowset = $this->tableGateway->select(array('id_annonce' => $id_annonce));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id_annonce");
        }
        return $row;
    }

    public function saveAnnonce(Annonce $annonce)
    {
        $data = array(
            'titre' => $annonce->titre,
            'descr'  => $annonce->descr,
            'type_annonce'  => $annonce->type_annonce,
            'prix'  => $annonce->prix,
            'etat'  => $annonce->etat,
            'date_crea'  => $annonce->date_crea,
		    'date_modif'  => $annonce->date_modif,
		    'visible'  => $annonce->visible,
		    'id_cat'  => $annonce->id_cat,
		    'id_dept'  => $annonce->id_dept,
		    'mail_auteur'  => $annonce->mail_auteur,
		    'id_reg'  => $annonce->id_reg,
		);

        $id_annonce = (int)$annonce->id_annonce;
        if ($id_annonce == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getAnnonce($id_annonce)) {
                $this->tableGateway->update($data, array('id_annonce' => $id_annonce));
            } else {
                throw new \Exception('Form id does not exist');
            }
          
        }
        
		/*
		 * Si ce n'était pas un auto-increment
		 if ($this->getAnnonce($id_annonce)) {
                $this->tableGateway->update($data, array('id_annonce' => $id_annonce));
            } else {
                $this->tableGateway->insert($data);
            }
		 */
       return $this->tableGateway->getLastInsertValue();
    }


    public function deleteAnnonce($id_annonce)
    {
        $this->tableGateway->delete(array('id_annonce' => $id_annonce));
    }
    
    
    public function filtrageStrict($prixmin, $prixmax, $id_cat, $id_dept, $type_annonce, $id_reg, $etat) {
        
        $requete = new Select();
        
        $requete->from($this->tableGateway->table);  
        
        $where = new Where();
        if($id_cat != null)
            $where->equalTo('id_cat', $id_cat);

        if($id_dept != null)
            $where->equalTo('id_dept', $id_dept);

        if($type_annonce != null)
            $where->equalTo('type_annonce', $type_annonce);
            
        if($prixmin != null)
            $where->greaterThanOrEqualTo('prix', $prixmin);    
        
        if($prixmax != null)
            $where->lessThanOrEqualTo('prix', $prixmax); 
            
         if($id_reg != null)
            $where->equalTo('id_reg', $id_reg);  
         
         if($etat != null)
            $where->greaterThanOrEqualTo('etat', $etat);
         
        $requete->where($where);
        $requete->order('date_modif DESC');
        
        $resultSet = $this->tableGateway->selectWith($requete);
        
        return $resultSet;
        
        /*
        $requete = new Select();
        $requete->from($this->tableGateway->table);   
        $conditions = array();
        if($id_cat != null)
            $conditions['id_cat'] = $id_cat;

        if($id_dept != null)
            $conditions['id_dept'] = $id_dept;

        if($type_annonce != null)
            $conditions['type_annonce'] = $type_annonce;

        $requete->where($conditions);
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
         */

    }
    
    
    
}