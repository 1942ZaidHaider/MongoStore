<?php

use Phalcon\Mvc\Controller;

class OrderController extends Controller
{
    public function initialize()
    {
        $this->product = new Products();
        $this->order = new Orders();
    }
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $arr = $post;
            $arr['products']["data"] = json_decode(base64_decode($post['products']["data"]), 1);
            $arr['time'] = time();
            $arr['status'] = 0;
            $this->order->mInsert($arr);
        }
        $q = $this->request->getQuery('q');
        if ($q) {
            if (strtolower($q) == 'today') {
                $dt   = new DateTime(date("Y-n-d",time()));               
                $query =strval($dt->getTimestamp());
            }
        }
        //die;
        $this->view->orderData = $this->order->mFind(["time"=>['$gte'=>$query]]);// ?? [];
        $this->view->productData = $this->product->mFind() ?? [];
        $this->view->statuses = ["Paid", "Processing", "Dispatched", "Shipped", "Refunded"];
    }
    public function removeAction()
    {
        $id = base64_decode($this->request->getQuery("id"));
        $val =  $this->order->mFindByID($id);
        $this->order->mDelete($val);
        $this->response->redirect("order");
    }
    public function statusAction()
    {
        $id = base64_decode($this->request->getQuery("id"));
        $val =  $this->order->mFindByID($id);
        $status = ($val["status"] + 1) % 5;
        $this->order->mUpdate($id, ['$set' => ["status" => $status]]);
        $this->response->redirect("order");
    }
}
