<?php

use Phalcon\Mvc\Controller;

class ApiController extends Controller
{
    public function initialize()
    {
        $this->product = new Products();
    }
    public function indexAction()
    {
        die("Api end point. read docs (they don't exist)");
    }
    public function itemAction($id)
    {
        if ($this->request->isPost()) {
            $prod = new Products();
            $prod->initialize();
            $data = $prod->mFindByID($id);
            return json_encode($data);
        }
    }
}
