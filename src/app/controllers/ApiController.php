<?php

use Phalcon\Mvc\Controller;

/**
 * Api for ajax calls to get products
 */

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
    /**
     * get product information id
     * request type: POST
     * request parameters: none
     * request end point: api/item/{product-id}
     *
     * @param string $id
     * @return void
     */
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
