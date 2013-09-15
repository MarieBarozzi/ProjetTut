<?php
namespace Annoncea\Model;

use Zend\Db\TableGateway\TableGateway;

class PhotoTable
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

  public function getPhoto($id_photo)
    {
        $id_photo  = (int) $id_photo;
        $rowset = $this->tableGateway->select(array('id_photo' => $id_photo));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id_photo");
        }
        return $row;
    }
    
     public function getByIdAnnonce($id_annonce)
    {
        $id_annonce  = (int) $id_annonce;
        $rowset = $this->tableGateway->select(array('id_annonce' => $id_annonce));
        return $rowset;
    }
    
       public function savePhoto(Photo $photo)
    {
        $data = array(
            'id_photo' => $photo->id_photo,
            'id_annonce'  => $photo->id_annonce,
        );

        $id_photo = (int)$photo->id_photo;
        if ($id_photo == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getPhoto($id_photo)) {
                $this->tableGateway->update($data, array('id_photo' => $id_photo));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
        return $this->tableGateway->getLastInsertValue();
    }


    public function deletePhoto($id_photo)
    {
        $this->tableGateway->delete(array('id_photo' => $id_photo));
    }
}