<?php

namespace wpView;

class Init {

    private $plugins = array();
    private $views = array();

    public function __construct() {
        $this->initPlugins();
        $this->initViews();
    }

    public function getPlugins() {
        return $this->plugins;
    }

    public function getViews() {
        return $this->views;
    }

    private function initPlugins() {
        $this->plugins['Custom_Field_Suite'] = '\wpView\plugins\CFSPlugin';
        $this->plugins['acf'] = '\wpView\plugins\ACFPlugin';
        $this->plugins['Toolset_Autoloader'] = '\wpView\plugins\TTPlugin';
        $this->plugins['CCTM'] = "\wpView\plugins\CCTMPlugin";
        $this->plugins['Wordpress_Creation_Kit'] = '\wpView\plugins\WCKPlugin';
    }

    private function initViews() {
        $this->views['Custom_Field_Suite'] = array('text' => '\wpView\views\TextView', 'textarea' => '\wpView\views\TextView', 'wysiwyg' => '\wpView\views\WYSIWYGView', 'hyperlink' => '\wpView\views\HyperlinkView', 'date' => '\wpView\views\DateView', 'color' => '\wpView\views\ColorView', 'true_false' => '\wpView\views\CheckboxView', 'select' => '\wpView\views\SelectView', 'relation' => '\wpView\views\RelationView', 'user' => '\wpView\views\UserView', 'image' => '\wpView\views\ImageView', 'audio' => '\wpView\views\AudioView', 'video' => '\wpView\views\VideoView', 'file' => '\wpView\views\FileView');
        $this->views['acf'] = array('text' => '\wpView\views\TextView', 'textarea' => '\wpView\views\TextView', 'number' => '\wpView\views\TextView', 'email' => '\wpView\views\TextView', 'wysiwyg' => '\wpView\views\WYSIWYGView', 'hyperlink' => '\wpView\views\HyperlinkView', 'date' => '\wpView\views\DateView', 'color' => '\wpView\views\ColorView', 'checkbox' => '\wpView\views\CheckboxView', 'true_false' => '\wpView\views\CheckboxView', 'select' => '\wpView\views\SelectView', 'relation' => '\wpView\views\RelationView', 'page_link' => '\wpView\views\RelationView', 'post_object' => '\wpView\views\RelationView', 'user' => '\wpView\views\UserView', 'image' => '\wpView\views\ImageView', 'audio' => '\wpView\views\AudioView', 'video' => '\wpView\views\VideoView', 'file' => '\wpView\views\FileView', 'google_map' => '\wpView\views\MapView', 'category' => '\wpView\views\TaxonomyView', 'post_tag' => '\wpView\views\TaxonomyView');
        $this->views['Toolset_Autoloader'] = array('text' => '\wpView\views\TextView', 'radio' => '\wpView\views\CheckboxView', 'email' => '\wpView\views\TextView', 'phone' => '\wpView\views\TextView', 'number' => '\wpView\views\TextView', 'textarea' => '\wpView\views\TextView', 'wysiwyg' => '\wpView\views\WYSIWYGView', 'hyperlink' => '\wpView\views\HyperlinkView', 'embed' => '\wpView\views\HyperlinkView', 'date' => '\wpView\views\DateView', 'color' => '\wpView\views\ColorView', 'checkbox' => '\wpView\views\CheckboxView', 'checkboxes' => '\wpView\views\CheckboxView', 'select' => '\wpView\views\SelectView', 'image' => '\wpView\views\ImageView', 'audio' => '\wpView\views\AudioView', 'video' => '\wpView\views\VideoView', 'file' => '\wpView\views\FileView');
        $this->views['CCTM'] = array('text' => '\wpView\views\TextView', 'textarea' => '\wpView\views\TextView', 'wysiwyg' => '\wpView\views\WYSIWYGView', 'date' => '\wpView\views\DateView', 'color' => '\wpView\views\ColorView', 'checkbox' => '\wpView\views\CheckboxView', 'select' => '\wpView\views\SelectView', 'relation' => '\wpView\views\RelationView', 'user' => '\wpView\views\UserView', 'image' => '\wpView\views\ImageView', 'audio' => '\wpView\views\AudioView', 'video' => '\wpView\views\VideoView', 'file' => '\wpView\views\FileView');
        $this->views['Wordpress_Creation_Kit'] = array('text' => '\wpView\views\TextView', 'phone' => '\wpView\views\TextView', 'number' => '\wpView\views\TextView', 'radio' => '\wpView\views\CheckboxView', 'currency' => '\wpView\views\TextView', 'time' => '\wpView\views\DateView', 'textarea' => '\wpView\views\TextView', 'wysiwyg' => '\wpView\views\WYSIWYGView', 'color' => '\wpView\views\ColorView', 'checkbox' => '\wpView\views\CheckboxView', 'select' => '\wpView\views\SelectView', 'image' => '\wpView\views\ImageView', 'audio' => '\wpView\views\AudioView', 'video' => '\wpView\views\VideoView', 'file' => '\wpView\views\FileView');
    }

}
