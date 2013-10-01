<?php
namespace Annoncea\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

/*
 * contient le lien avec la table message et execute les opérations dessus
*/

class MessageTable
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
            $select->order('date_mess DESC'); // tri par les date du message.
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;

    }

    public function getMessage($id_mess)
    {
        $id_mess  = (int) $id_mess;
        $rowset = $this->tableGateway->select(array('id_mess' => $id_mess));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id_mess");
        }
        return $row;
    }

    public function saveMessage(Message /*Utile de précisé le type*/ $message)
    {
        $data = array(
            'contenu'  => $message->contenu,
            'objet'  => $message->objet,
            'date_mess'  => $message->date_mess,
            'mail_dest'  => $message->mail_dest,
            'mail_exp'  => $message->mail_exp,
		);

        $id_mess = (int)$message->id_mess;
        if ($id_mess == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getMessage($id_mess)) {
                $this->tableGateway->update($data, array('id_mess' => $id_mess));
            } else {
                throw new \Exception('Form id does not exist');
            }
          
        }
       return $this->tableGateway->getLastInsertValue();
    }


    public function deleteMessage($id_mess)
    {
        $this->tableGateway->delete(array('id_mess' => $id_mess));
    }
}