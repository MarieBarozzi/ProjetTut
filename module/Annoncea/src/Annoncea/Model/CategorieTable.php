<?php
namespace Annoncea\Model;

use Zend\Db\TableGateway\TableGateway;

class CategorieTable
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

    public function getCategorie($id_cat)
    {
        $id_cat  = (int) $id_cat;
        $rowset = $this->tableGateway->select(array('id_cat' => $id_cat));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id_cat");
        }
        return $row;
    }
}