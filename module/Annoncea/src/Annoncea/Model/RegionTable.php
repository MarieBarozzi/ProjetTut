<?php
namespace Annoncea\Model;

use Zend\Db\TableGateway\TableGateway;

class RegionTable
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

    public function getRegion($id_reg)
    {
        $id_reg  = (int) $id_reg;
        $rowset = $this->tableGateway->select(array('id_reg' => $id_reg));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id_reg");
        }
        return $row;
    }
}