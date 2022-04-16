<?php

use Phalcon\Mvc\Model;
use MongoDB\BSON\ObjectId;

class Orders extends Model
{
    public $col;
    public function initialize()
    {
        $this->col = $this->di->get('mongo')->orders;
    }
    public function mFind($filter = null, $opt = null)
    {
        $values = $this->col->find($filter ?? [], $opt ?? []);
        $ret = [];
        foreach ($values as $k => $v) {
            $ret[$k] = $v;
        }
        $ret = json_decode(json_encode($ret), 1);
        $finalRet = $ret;
        foreach ($finalRet as $x=>$d) {
            $finalRet[$x]['_id'] = new ObjectId($d['_id']['$oid']);
        }
        return $finalRet;
    }
    public function mFindFirst($filter = null, $opt = null)
    {
        return $this->mFind($filter, $opt)[0];
    }
    public function mFindLast($filter = null, $opt = null)
    {
        $c = count($this->mFind($filter, $opt));
        return $this->mFind($filter, $opt)[$c - 1];
    }
    public function mInsert($value = null)
    {
        return $this->col->insertOne($value);
    }
    public function mUpdate($id, $value)
    {
        $val = $this->mFindByID($id);
        return $this->col->updateOne($val, $value,['$upsert'=>true]);
    }
    public function mDelete($filter = null)
    {
        return $this->col->deleteOne($filter);
    }
    public function mFindByID($id)
    {
        return $this->mFindFirst(["_id" => (new ObjectId($id))]);
        //var_dump($id);
    }
}
