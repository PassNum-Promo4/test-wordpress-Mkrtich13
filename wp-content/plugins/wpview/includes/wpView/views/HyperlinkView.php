<?php

namespace wpView\views;

class HyperlinkView extends View {

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
                    $value = maybe_unserialize($single_data['value']);
                    $structure .= '<tr class="wpview_hyperlink_field">';
                    $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                    $structure .= '<td>';
                    if (is_array($value)) {
                        if ($value['url']) {
                            $structure .= '<a href="' . esc_url($value['url']) . '" ' . ($value['target'] ? ' target="' . esc_attr($value['target']) . '"' : '') . '>';
                            $structure .= $value['text'] ? esc_html($value['text']) : esc_url($value['url']);
                            $structure .= '</a>';
                        }
                    } else {
                        $structure .= '<a href="' . esc_url($value) . '" title="' . esc_attr($value) . '" target="_blank">';
                        $structure .= esc_html($value);
                        $structure .= '</a>';
                    }
                    $structure .= '</td>';
                    $structure .= '</tr>';
                }
            } else if ($view === 'hyperlink_icon') {
                $structure .= '<div class="wpview_hyperlink_view">';
                foreach ($data['value'] as $key => $single_data) {
                    $value = maybe_unserialize($single_data['value']);
                    $structure .= '<div class="wpview_hyperlink">';
                    $structure .= '<span class="wpview_field_label hyperlink_field_label">';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= ' <strong>' . $single_data['title'] . ':</strong>&nbsp;';
                    }
                    $structure .= '</span>';
                    if (is_array($value)) {
                        if ($value['url']) {
                            $target = $value['target'] ? ' target="' . esc_attr($value['target']) . '"' : '';
                            $structure .= '<a href="' . esc_url($value['url']) . '"' . $target . '>';
                            $structure .= $value['text'] ? esc_html($value['text']) : esc_url($value['url']);
                            $structure .= '</a>';
                        }
                    } else {
                        $structure .= '<a href="' . esc_url($value) . '" title="' . esc_attr($value) . '" target="_blank">';
                        $structure .= esc_html($value);
                        $structure .= '</a>';
                    }
                    $structure .= '</div>';
                }
                $structure .= '</div>';
            } else if ($view === 'with_table') {
                $structure .= '<div class="wpview_hyperlink_view">';
                $structure .= '<table class="wpview_table" cellspacing="0">';
                foreach ($data['value'] as $key => $single_data) {
                    $value = maybe_unserialize($single_data['value']);
                    $structure .= '<tr>';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                    }
                    $structure .= '<td>';
                    if (is_array($value)) {
                        if ($value['url']) {
                            $target = $value['target'] ? ' target="' . esc_attr($value['target']) . '"' : '';
                            $structure .= '<a href="' . esc_url($value['url']) . '"' . $target . '>';
                            $structure .= $value['text'] ? esc_html($value['text']) : esc_url($value['url']);
                            $structure .= '</a>';
                        }
                    } else {
                        $structure .= '<a href="' . esc_url($value) . '" title="' . esc_attr($value) . '" target="_blank">';
                        $structure .= esc_html($value);
                        $structure .= '</a>';
                    }
                    $structure .= '</td>';
                    $structure .= '</tr>';
                }
                $structure .= '</table>';
                $structure .= '</div>';
            } else if ($view === 'with_ul') {
                $structure .= '<div class="wpview_hyperlink_view">';
                $structure .= '<ul class="wpview_ul">';
                foreach ($data['value'] as $key => $single_data) {
                    $value = maybe_unserialize($single_data['value']);
                    $structure .= '<li>';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . esc_html($single_data['title']) . ': </strong>';
                    }
                    if (is_array($value)) {
                        if ($value['url']) {
                            $target = $value['target'] ? ' target="' . esc_attr($value['target']) . '"' : '';
                            $structure .= '<a href="' . esc_url($value['url']) . '"' . $target . '>';
                            $structure .= $value['text'] ? esc_html($value['text']) : esc_url($value['url']);
                            $structure .= '</a>';
                        }
                    } else {
                        $structure .= '<a href="' . esc_url($value) . '" title="' . esc_attr($value) . '" target="_blank">';
                        $structure .= esc_html($value);
                        $structure .= '</a>';
                    }
                    $structure .= '</li>';
                }
                $structure .= '</ul>';
                $structure .= '</div>';
            } else if ($view === 'with_ol') {
                $structure .= '<div class="wpview_hyperlink_view">';
                $structure .= '<ol class="wpview_ol">';
                foreach ($data['value'] as $key => $single_data) {
                    $value = maybe_unserialize($single_data['value']);
                    $structure .= '<li>';
                    if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                        $structure .= '<strong>' . esc_html($single_data['title']) . ': </strong>';
                    }
                    if (is_array($value)) {
                        if ($value['url']) {
                            $target = $value['target'] ? ' target="' . esc_attr($value['target']) . '"' : '';
                            $structure .= '<a href="' . esc_url($value['url']) . '"' . $target . '>';
                            $structure .= $value['text'] ? esc_html($value['text']) : esc_url($value['url']);
                            $structure .= '</a>';
                        }
                    } else {
                        $structure .= '<a href="' . esc_url($value) . '" title="' . esc_attr($value) . '" target="_blank">';
                        $structure .= esc_html($value);
                        $structure .= '</a>';
                    }
                    $structure .= '</li>';
                }
                $structure .= '</ol>';
                $structure .= '</div>';
            }
        }
        return $structure;
    }

}
