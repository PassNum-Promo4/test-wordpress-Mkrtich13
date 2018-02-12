<?php

namespace wpView\views;

class MapView extends View {

    private static $instance;
    private $localize;

    protected function __construct() {
        parent::__construct();
        add_action('wp_enqueue_scripts', array($this, 'mapScript'), 19);
    }

    static public function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function draw() {
        $data = $this->getData();
        $view = $this->getView();
        $structure = '';
        if ($data) {
            $options = get_option(WPVIEW_VIEWS);
            if ($view === 'all_together_in_table') {
                foreach ($data['value'] as $key => $single_data) {
                    $structure .= '<tr class="wpview_map_field">';
                    $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                    $structure .= '<td>';
                    $this->localize[$single_data['value']['post_id']][$single_data['value']['id']]['lat'] = $single_data['value']['lat'];
                    $this->localize[$single_data['value']['post_id']][$single_data['value']['id']]['lng'] = $single_data['value']['lng'];
                    $this->localize[$single_data['value']['post_id']][$single_data['value']['id']]['zoom'] = $single_data['value']['zoom'];
                    $this->localize[$single_data['value']['post_id']][$single_data['value']['id']]['field_id'] = $single_data['value']['field_id'];
                    $structure .= '<div id="wpview_map-' . $single_data['value']['field_id'] . '"></div>';
                    $structure .= '</td>';
                    $structure .= '</tr>';
                }
            } else if ($view === 'google_map') {
                global $post;
                $structure .= '<div class="wpview_column wpview_map_view">';
                foreach ($data['value'] as $key => $single_data) {
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . $single_data['title'] . ':</strong> ';
                    }
                    $this->localize[$single_data['value']['post_id']][$single_data['value']['id']]['lat'] = $single_data['value']['lat'];
                    $this->localize[$single_data['value']['post_id']][$single_data['value']['id']]['lng'] = $single_data['value']['lng'];
                    $this->localize[$single_data['value']['post_id']][$single_data['value']['id']]['zoom'] = $single_data['value']['zoom'];
                    $this->localize[$single_data['value']['post_id']][$single_data['value']['id']]['field_id'] = $single_data['value']['field_id'];
                    $structure .= '<div id="wpview_map-' . $single_data['value']['field_id'] . '"></div>';
                }
                $structure .= '</div>';
            }
        }
        wp_localize_script('wpview_google_map', 'wpview_map_coord', $this->localize);
        return $structure;
    }

    public function mapScript() {
        $options = get_option(WPVIEW_VIEWS);
        if (isset($options['wpview_plugin_names']['acf']) && ($options['wpview_choose_views'] === 'display_together' || (isset($options['views_for_all']['google_map']) && $options['views_for_all']['google_map']['view'] !== 'none'))) {
            wp_register_script('wpview_google_map_api', 'http://maps.google.com/maps/api/js', array('jquery'), null, true);
            wp_register_script('wpview_google_map', plugins_url('wpview/assets/js/google_map.js'), array('wpview_google_map_api'), null, true);
            wp_enqueue_script('wpview_google_map_api');
            wp_enqueue_script('wpview_google_map');
        }
    }

}
