<?php

namespace Admin\ModelAbstract;

use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

class TableAbstract
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getSql() {
        return $this->tableGateway->getSql();
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function fetchWith($sql) {
        if ($sql instanceof Select)
            $resultSet = $this->tableGateway->selectWith($sql);
        else
            $resultSet = $this->tableGateway->select($sql);
        return $resultSet;
    }

    public function get($id)
    {
        if (is_array($id)) {

            if (count($id) < 1) return [];

            $rowset = $this->tableGateway->select(array('id IN (' . implode(',',array_fill(0, count($id), '?')) . ')' => $id));
            $row = $rowset;
        } else {
            $id  = (int) $id;
            $rowset = $this->tableGateway->select(array('id' => $id));
            $row = $rowset->current();
        }

        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function save(EntityAbstract $object)
    {

        $id = (int) $object->id;
        if ($id == 0) {
            $object->setCreatedDate();
            $data = $object->toData();
            $data['created_at'] = $object->createdAt->format('Y-m-d H:i:s');
            $data['updated_at'] = null;
            $result = $this->tableGateway->insert($data);
            if ($result)
                $object->id = $this->tableGateway->getLastInsertValue();
        } else {
            $object->setUpdatedDate();
            $data = $object->toData();
            $data['updated_at'] = $object->updatedAt->format('Y-m-d H:i:s');
            if ($this->get($id)) {
                $id = $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Row id does not exist');
            }
        }
        return $object;
    }

    public function update($data, $where) {
        return $this->tableGateway->update($data, $where);
    }

    public function delete($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function deleteWith($where) {
        $this->tableGateway->delete($where);
    }

    public function getAdapter() {
        return $this->tableGateway->getAdapter();
    }

}