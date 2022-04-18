<?php

use Phalcon\Mvc\Controller;

/**
 * Order Controller
 */
class OrderController extends Controller
{
    public function initialize()
    {
        $this->product = new Products();
        $this->order = new Orders();
    }
    /**
     * Order Read, Create
     *
     * @return void
     */
    public function indexAction()
    {
        // Create orders
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $arr = $post;
            $arr['products']["data"] = json_decode(base64_decode($post['products']["data"]), 1);
            $arr['time'] = time();
            $arr['status'] = 0;
            $this->order->mInsert($arr);
        }
        //Read Orders
        $q = $this->request->getQuery('q');
        $s = $this->request->getQuery('s');
        $query = [];
        //Parsing date values
        // Today, This Week, This Month, This Year ,Custom Date [D-M-Y:D-M-Y]
        if ($q) {
            $dt   = new DateTime(date("Y-n-d", time()));
            $today = intval($dt->getTimestamp());
            if (strtolower($q) == 'today') {
                $query = ["time" => ['$gte' => $today]];
            } elseif (strpos(strtolower($q), 'week') !== false) {
                $weekStart = date("d", $today) - (date("w", $today) - 1);
                $thisWeek = strtotime(date("$weekStart-n-Y", $today));
                $query = ["time" => ['$gte' => $thisWeek]];
            } elseif (strpos(strtolower($q), 'month') !== false) {
                $thisMonth = strtotime(date("1-n-Y", $today));
                $query = ["time" => ['$gte' => $thisMonth]];
            } elseif (strpos(strtolower($q), 'year') !== false) {
                $thisYear = strtotime(date("1-1-Y", $today));
                $query = ["time" => ['$gte' => $thisYear]];
            } else {
                $range = explode(":", $q);
                if (count($range) <= 0) {
                    $query = [];
                } elseif (count($range) == 1) {
                    $timeStart = strtotime($range[0]);
                    $query = ['time' => ['$lte' => time(), '$gte' => $timeStart]];
                } elseif (count($range) == 2) {
                    $timeStart = strtotime($range[0]);
                    $timeEnd = strtotime($range[1]);
                    $query = ['time' => ['$lte' => $timeEnd, '$gte' => $timeStart]];
                }
            }
        }
        //Search by status
        if ($s && $s>=0) {
            $query['status'] = intval($s);
        }
        $this->view->orderData = $this->order->mFind($query);
        $this->view->productData = $this->product->mFind() ?? [];
        $this->view->statuses = ["Paid", "Processing", "Dispatched", "Shipped", "Refunded"];
    }
    /**
     * Remove Order by id
     *
     * @return void
     */
    public function removeAction()
    {
        $id = base64_decode($this->request->getQuery("id"));
        $val =  $this->order->mFindByID($id);
        $this->order->mDelete($val);
        $this->response->redirect("order");
    }
    /**
     * Change order Status by id
     *
     * @return void
     */
    public function statusAction()
    {
        $id = base64_decode($this->request->getQuery("id"));
        $val =  $this->order->mFindByID($id);
        $status = ($val["status"] + 1) % 5;
        $this->order->mUpdate($id, ['$set' => ["status" => $status]]);
        $this->response->redirect("order");
    }
}
