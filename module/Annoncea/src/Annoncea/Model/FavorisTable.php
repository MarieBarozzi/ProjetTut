<?php
namespace Annoncea\Model;

use Zend\Db\TableGateway\TableGateway;


class FavorisTable
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
    
    public function getFavoris($mail, $id_annonce){
        $mail = $mail = (string) $mail;
        $id_annonce = (int) $id_annonce; 
        $rowset = $this->tableGateway->select(array('mail'=>$mail, 'id_annonce'=>$id_annonce));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $mail and $id_annonce");
        }
        return $row;
    }
    
    
    public function getByMail($mail){
         $mail = (string) $mail; //utile car NULL sera casté en ''
         $resultSet = $this->tableGateway->select(array('mail' => $mail));
         return $resultSet;
    }
    
    public function saveFavoris(Favoris $favoris)
    {      
        $data = array(
            'id_annonce' => $favoris->id_annonce,
            'mail' => $favoris->mail,
 
        );      
        
       // $this->tableGateway->insert($data);
        
        $mail = (string) $favoris->mail;
        $id_annonce = (int) $favoris->id_annonce;
         try {
             $this->getFavoris($mail, $id_annonce);        
         } catch (\Exception $e){
             $this->tableGateway->insert($data);
         }
    }
    
    
    
    public function deleteFavoris($id_annonce /*+$mail ?*/)
    {
        $this->tableGateway->delete(array('id_annonce' => $id_annonce));
    }
 
}