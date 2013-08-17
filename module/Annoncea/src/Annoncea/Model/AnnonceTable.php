<?php
namespace Annoncea\Model;

use Zend\Db\TableGateway\TableGateway;

class AnnonceTable
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
    }


    public function deleteAnnonce($id_annonce)
    {
        $this->tableGateway->delete(array('id_annonce' => $id_annonce));
    }
}