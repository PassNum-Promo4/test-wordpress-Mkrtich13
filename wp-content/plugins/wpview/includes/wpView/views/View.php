<?php

namespace wpView\views;

abstract class View {

    private $data;
    private $view;

    protected function __construct($data = '') {
        $this->data = $data;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }

    public function setView($view) {
        $this->view = $view;
    }

    public function getView() {
        return $this->view;
    }

    abstract protected function draw();
}
