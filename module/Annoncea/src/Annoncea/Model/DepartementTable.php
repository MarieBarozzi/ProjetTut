<?php
namespace Annoncea\Model;

use Zend\Db\TableGateway\TableGateway;

class DepartementTable
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

    public function getDepartement($id_dept)
    {
        $id_dept  = $id_dept;
        $rowset = $this->tableGateway->select(array('id_dept' => $id_dept));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id_dept");
        }
        return $row;
    }
}