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
        $this->view->data=[];
    }
    public function removeAction()
    {
    }
    public function updateAction()
    {
    }
}
