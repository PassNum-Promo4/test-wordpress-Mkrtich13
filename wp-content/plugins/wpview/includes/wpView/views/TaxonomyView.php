<?php

namespace wpView\views;

class TaxonomyView extends View {

    private static $instance;

    protected function __construct() {
        parent::__construct();
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
                    $structure .= '<tr class="wpview_term_field">';
                    $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                    $structure .= '<td>';
                    foreach ($single_data['value'] as $key => $value) {
                        $term_link = get_term_link(intval($value));
                        $term = get_term(intval($value));
                        if ($key !== 0) {
                            $structure .= ',&nbsp;';
                        }
                        $structure .= '<a href="' . esc_url($term_link) . '" title="' . esc_attr($term->name) . '" />' . esc_html($term->name) . '</a>';
                    }
                    $structure .= '</td>';
                    $structure .= '</tr>';
                }
            } else if ($view === 'with_separator') {
                $structure .= '<div class="wpview_term_view">';
                foreach ($data['value'] as $key => $single_data) {
                    $structure .= '<div class="wpview_term_with_separator ' . ($data['type'] !== 'category' ? 'wpview_tag_parent' : 'wpview_category_parent') . '">';
                    $structure .= '<span class="wpview_field_label taxonomy_field_label ' . ($data['type'] !== 'category' ? 'wpview_tag' : 'wpview_category') . '">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= ' <strong>' . $single_data['title'] . ':</strong>&nbsp;';
                    }
                    $structure .= '</span>';
                    foreach ($single_data['value'] as $key => $value) {
                        $term_link = get_term_link(intval($value));
                        $term = get_term(intval($value));
                        if ($key !== 0) {
                            $structure .= ',&nbsp;';
                        }
                        $structure .= '<a href="' . esc_url($term_link) . '" title="' . esc_attr($term->name) . '" />' . esc_html($term->name) . '</a>';
                    }
                    $structure .= '</div>';
                }
                $structure .= '</div>';
            } else if ($view === 'without_separator') {
                $structure .= '<div class="wpview_left wpview_column wpview_term_view">';
                foreach ($data['value'] as $key => $single_data) {
                    foreach ($single_data['value'] as $key => $value) {
                        $term_link = get_term_link(intval($value));
                        $term = get_term(intval($value));
                        $structure .= '<div class="wpview_term_without_separator ' . ($data['type'] !== 'category' ? 'wpview_tag_parent' : 'wpview_category_parent') . '">';
                        $structure .= '<span class="wpview_field_label taxonomy_field_label ' . ($data['type'] !== 'category' ? 'wpview_tag' : 'wpview_category') . '">';
                        if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                            $structure .= ' <strong>' . $single_data['title'] . ':</strong>&nbsp;';
                        }
                        $structure .= '</span>';
                        $structure .= '<a href="' . esc_url($term_link) . '" title="' . esc_attr($term->name) . '" >';
                        $structure .= esc_html($term->name);
                        $structure .= '</a>';
                        $structure .= '</div>';
                    }
                }
                $structure .= '</div>';
            } else if ($view === 'with_table') {
                $structure .= '<div class="' . ($data['type'] !== 'category' ? 'wpview_tag_view' : 'wpview_category_view') . ' wpview_term_view">';
                $structure .= '<table class="wpview_table" cellspacing="0">';
                foreach ($data['value'] as $key => $single_data) {
                    foreach ($single_data['value'] as $key => $value) {
                        $term_link = get_term_link(intval($value));
                        $term = get_term(intval($value));
                        $structure .= '<tr>';
                        if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                            $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                        }
                        $structure .= '<td>';
                        $structure .= '<a href="' . esc_url($term_link) . '" title="' . esc_attr($term->name) . '">';
                        $structure .= esc_html($term->name);
                        $structure .= '</a>';
                        $structure .= '</td>';
                        $structure .= '</tr>';
                    }
                }
                $structure .= '</table>';
                $structure .= '</div>';
            } else if ($view === 'with_ul') {
                $structure .= '<div class="' . ($data['type'] !== 'category' ? 'wpview_tag_view' : 'wpview_category_view') . ' wpview_term_view">';
                $structure .= '<ul class="wpview_ul">';
                foreach ($data['value'] as $key => $single_data) {
                    foreach ($single_data['value'] as $key => $value) {
                        $term_link = get_term_link(intval($value));
                        $term = get_term(intval($value));
                        $structure .= '<li>';
                        if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                            $structure .= '<strong>' . esc_html($single_data['title']) . ': </strong>';
                        }
                        $structure .= '<a href="' . esc_url($term_link) . '" title="' . esc_attr($term->name) . '" >';
                        $structure .= esc_html($term->name);
                        $structure .= '</a>';
                        $structure .= '</li>';
                    }
                }
                $structure .= '</ul>';
                $structure .= '</div>';
            } else if ($view === 'with_ol') {
                $structure .= '<div class="' . ($data['type'] !== 'category' ? 'wpview_tag_view' : 'wpview_category_view') . 'wpview_term_view">';
                $structure .= '<ol class="wpview_ol">';
                foreach ($data['value'] as $key => $single_data) {
                    foreach ($single_data['value'] as $key => $value) {
                        $term_link = get_term_link(intval($value));
                        $term = get_term(intval($value));
                        $structure .= '<li>';
                        if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                            $structure .= '<strong>' . esc_html($single_data['title']) . ': </strong>';
                        }
                        $structure .= '<a href="' . esc_url($term_link) . '" title="' . esc_attr($term->name) . '" >';
                        $structure .= esc_html($term->name);
                        $structure .= '</a>';
                        $structure .= '</li>';
                    }
                }
                $structure .= '</ol>';
                $structure .= '</div>';
            }
        }
        return $structure;
    }

}
