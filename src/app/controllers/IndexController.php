<?php

use Phalcon\Mvc\Controller;

/**
 * Index Controller
 */

class IndexController extends Controller
{
    public function initialize()
    {
        $this->product = new Products();
    }
    /**
     * Index Action
     *
     * @return void
     */
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $arr = MainHelper::parseProduct($post);
            $this->product->mInsert($arr);
        }
        $searchParam = $this->request->getQuery("q") ? ["name" => $this->request->getQuery("q")] : [];
        $this->view->data =  $this->product->mFind($searchParam, ["sort" => ["name" => -1]]);
    }
    /**
     * Remove Product
     *
     * @return void
     */
    public function removeAction()
    {
        $id = base64_decode($this->request->getQuery("id"));
        $val =  $this->product->mFindByID($id);
        $this->product->mDelete($val);
        $this->response->redirect("index");
    }
    /**
     * Update Product by id
     *
     * @return void
     */
    public function updateAction()
    {
        $id = base64_decode($this->request->getQuery("id"));
        $dat = $this->product->mFindByID($id);
        $this->view->data = $dat;
        $this->view->meta = $dat["meta"] ?? [];
        $this->view->var = $dat["variations"] ?? [];

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $arr = MainHelper::parseProduct($post);
            $this->product->mUpdate($id, ['$set' => $arr]);
            $this->response->redirect("index");
        }
    }
}
