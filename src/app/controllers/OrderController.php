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
        $query=[];
        if ($q) {
            $dt   = new DateTime(date("Y-n-d",time()));               
            $today =intval($dt->getTimestamp());
            if (strtolower($q) == 'today') {
                $query=["time"=>['$gte'=>$today]];
            } elseif (strpos(strtolower($q),'week')!==false) {
                $weekStart=date("d",$today)-(date("w",$today)-1);
                $thisWeek=strtotime(date("$weekStart-n-Y",$today));
                $query=["time"=>['$gte'=>$thisWeek]];
            } elseif (strpos(strtolower($q),'month')!==false) {
                $thisMonth=strtotime(date("1-n-Y",$today));
                $query=["time"=>['$gte'=>$thisMonth]];
            } elseif (strpos(strtolower($q),'year')!==false) {
                $thisYear=strtotime(date("1-1-Y",$today));
                $query=["time"=>['$gte'=>$thisYear]];
            } else {
                $range=explode(":",$q);
                if(count($range)<=0){
                    $query=[];
                } elseif(count($range)==1) {
                    $timeStart=strtotime($range[0]);
                    $query=['time'=>['$lte'=>time(),'$gte'=>$timeStart]];
                } elseif(count($range)==2) {
                    $timeStart=strtotime($range[0]);
                    $timeEnd=strtotime($range[1]);
                    $query=['time'=>['$lte'=>$timeEnd,'$gte'=>$timeStart]];
                }
            }
        }
        $this->view->orderData = $this->order->mFind($query);// ?? [];
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
