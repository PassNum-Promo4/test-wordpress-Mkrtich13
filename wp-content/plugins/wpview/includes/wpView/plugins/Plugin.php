<?php

namespace wpView\plugins;

abstract class Plugin {

    private $fieldTypes;

    public function __construct() {
        $this->initFieldTypes();
    }

    public function getFieldTypes() {
        return $this->fieldTypes;
    }

    public function setFieldTypes($fieldTypes) {
        $this->fieldTypes = $fieldTypes;
    }

    abstract public function getFields($postId, $fieldType = 'all', $fieldTitle = '');

    abstract protected function initFieldTypes();
}
