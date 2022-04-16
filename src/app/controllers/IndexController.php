<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function initialize()
    {
        $this->product = new Products();
    }
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $arr = [];
            foreach ($post as $k => $v) {
                if (is_array($v)) {
                    if ($k == "metaKey") {
                        foreach ($v as $K => $V) {
                            $arr["meta"][$V] = $post["metaVal"][$K];
                        }
                    } elseif ($k == "attrKey") {
                        foreach ($v as $K => $V) {
                            $arr["variations"][] = ["key" => $V, "value" => $post["attrVal"][$K], "price" => $post["attrPrice"][$K]];
                        }
                    }
                } else {
                    $arr[$k] = $v;
                }
            }
            $this->product->mInsert($arr);
        }
        $searchParam = $this->request->getQuery("q") ? ["name" => $this->request->getQuery("q")] : [];
        $this->view->data =  $this->product->mFind($searchParam, ["sort" => ["name" => -1]]);
    }
    public function removeAction()
    {
        $id = base64_decode($this->request->getQuery("id"));
        $val =  $this->product->mFindByID($id);
        $this->product->mDelete($val);
        $this->response->redirect("index");
    }
    public function updateAction()
    {
        $id = base64_decode($this->request->getQuery("id"));
        $dat = $this->product->mFindByID($id);
        $this->view->data = $dat;
        $this->view->meta = $dat["meta"] ?? [];
        $this->view->var = $dat["variations"] ?? [];
        //print_r($this->view->var);
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $arr = [];
            var_dump($post);
            foreach ($post as $k => $v) {
                if (is_array($v)) {
                    if ($k == "metaKey") {
                        foreach ($v as $K => $V) {
                            $arr["meta"][$V] = $post["metaVal"][$K];
                        }
                    } elseif ($k == "attrKey") {
                        foreach ($v as $K => $V) {
                            $arr["variations"][] = ["key" => $V, "value" => $post["attrVal"][$K], "price" => $post["attrPrice"][$K]];
                        }
                    } else {
                        $arr[$k]=array_merge($v,$arr[$k]??[]);
                    }
                } else {
                    $arr[$k] = $v;
                }
            }
            $this->product->mUpdate($id, ['$set' => $arr]);
            $this->response->redirect("index");
        }
    }
}
