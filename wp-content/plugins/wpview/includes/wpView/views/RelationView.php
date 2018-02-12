<?php

namespace wpView\views;

class RelationView extends View {

    private static $instance;

    protected function __construct() {
        parent::__construct();
        add_action('wp_enqueue_scripts', array($this, 'relationScript'), 999);
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
                    if (!is_array($single_data['value'])) {
                        $post = get_post($single_data['value']);
                        $post_title = $post->post_title;
                        $get_permalink = get_permalink($single_data['value']);
                        $structure .= '<tr class="wpview_relation_field">';
                        $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                        $structure .= '<td>';
                        $structure .= '<div>';
                        $structure .= '<div class="wpview_relation">';
                        $get_thumbnail = get_the_post_thumbnail($single_data['value'], array(80, 80));
                        $structure .= $get_thumbnail ? $get_thumbnail : '<img width="80" src="' . plugins_url('wpview/assets/images/relation.jpg') . '" />';
                        $structure .= '<div class="relation-icons-group">';
                        if (get_the_post_thumbnail_url($single_data['value'], 'full')) {
                            $structure .= '<a class="wpview_relation_thumbnail-zoom wpview_relation_icon" href="' . get_the_post_thumbnail_url($single_data['value'], 'full') . '"><span class="wpview-icon-zoom-in"></span></a>';
                        } else {
                            $structure .= '<span class="wpview_relation_thumbnail-zoom wpview_relation_icon wpview-icon-zoom-in"></span>';
                        }
                        $structure .= '<a class="wpview_relation_icon" href="' . esc_url($get_permalink) . '"><span class="wpview-icon-link"></span></a>';
                        $structure .= '</div>';
                        $structure .= '</div>';
                        $structure .= '<div class="wpview_relation_title">';
                        $structure .= '<a class="wpview_relation_icon" href="' . esc_url($get_permalink) . '">';
                        $structure .= esc_html($post_title);
                        $structure .= '</a>';
                        $structure .= '</div>';
                        $structure .= '</div>';
                        $structure .= '</td>';
                        $structure .= '</tr>';
                    } else {
                        foreach ($single_data['value'] as $k => $value) {
                            $post = get_post($value);
                            $post_title = $post->post_title;
                            $get_permalink = get_permalink($value);
                            $structure .= '<tr class="wpview_relation_field">';
                            $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                            $structure .= '<td>';
                            $structure .= '<div>';
                            $structure .= '<div class="wpview_relation">';
                            $get_thumbnail = get_the_post_thumbnail($value, array(80, 80));
                            $structure .= $get_thumbnail ? $get_thumbnail : '<img width="80" src="' . plugins_url('wpview/assets/images/relation.jpg') . '" />';
                            $structure .= '<div class="relation-icons-group">';
                            if (get_the_post_thumbnail_url($value, 'full')) {
                                $structure .= '<a class="wpview_relation_thumbnail-zoom wpview_relation_icon" href="' . get_the_post_thumbnail_url($value, 'full') . '"><span class="wpview-icon-zoom-in"></span></a>';
                            } else {
                                $structure .= '<span class="wpview_relation_thumbnail-zoom wpview_relation_icon wpview-icon-zoom-in"></span>';
                            }
                            $structure .= '<a class="wpview_relation_icon" href="' . esc_url($get_permalink) . '"><span class="wpview-icon-link"></span></a>';
                            $structure .= '</div>';
                            $structure .= '</div>';
                            $structure .= '<div class="wpview_relation_title">';
                            $structure .= '<a class="wpview_relation_icon" href="' . esc_url($get_permalink) . '">';
                            $structure .= esc_html($post_title);
                            $structure .= '</a>';
                            $structure .= '</div>';
                            $structure .= '</div>';
                            $structure .= '</td>';
                            $structure .= '</tr>';
                        }
                    }
                }
            } else if ($view === 'with_thumbnail') {
                $structure .= '<div class="wpview_relation_view">';
                foreach ($data['value'] as $key => $single_data) {
                    if (!is_array($single_data['value'])) {
                        $post = get_post($single_data['value']);
                        $post_title = $post->post_title;
                        $get_permalink = get_permalink($single_data['value']);
                        $structure .= '<div>';
                        $structure .= '<div class="wpview_rel_wrap">';
                        $structure .= '<span class="wpview_field_label">';
                        if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                            $structure .= ' <strong>' . $single_data['title'] . ':</strong>&nbsp;';
                        }
                        $structure .= '</span>';
                        $structure .= '<div class="wpview_relation wpview_relation_with_thumbnail">';
                        $get_thumbnail = get_the_post_thumbnail($single_data['value'], array(80, 80));
                        $structure .= $get_thumbnail ? $get_thumbnail : '<img width="80" src="' . plugins_url('wpview/assets/images/relation.jpg') . '" />';
                        $structure .= '<div class="relation-icons-group">';
                        if (get_the_post_thumbnail_url($single_data['value'], 'full')) {
                            $structure .= '<a class="wpview_relation_thumbnail-zoom wpview_relation_icon" href="' . get_the_post_thumbnail_url($single_data['value'], 'full') . '"><span class="wpview-icon-zoom-in"></span></a> ';
                        } else {
                            $structure .= '<span class="wpview_relation_thumbnail-zoom wpview_relation_icon wpview-icon-zoom-in"></span>';
                        }
                        $structure .= '<a class="wpview_relation_icon" href="' . esc_url($get_permalink) . '"><span class="wpview-icon-link"></span></a>';
                        $structure .= '</div>';
                        $structure .= '</div>';
                        $structure .= '</div>';
                        $structure .= '<div class="wpview_relation_title wpview_relation_with_thumbnail_title">';
                        $structure .= '<a class="wpview_relation_icon" href="' . esc_url($get_permalink) . '">';
                        $structure .= esc_html($post_title);
                        $structure .= '</a>';
                        $structure .= '</div>';
                        $structure .= '</div>';
                    } else {
                        foreach ($single_data['value'] as $k => $value) {
                            $post = get_post($value);
                            $post_title = $post->post_title;
                            $get_permalink = get_permalink($value);
                            $structure .= '<div>';
                            $structure .= '<div class="wpview_rel_wrap">';
                            $structure .= '<span class="wpview_field_label">';
                            if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                                $structure .= ' <strong>' . $single_data['title'] . ':</strong>&nbsp;';
                            }
                            $structure .= '</span>';
                            $structure .= '<div class="wpview_relation wpview_relation_with_thumbnail">';
                            $get_thumbnail = get_the_post_thumbnail($value, array(80, 80));
                            $structure .= $get_thumbnail ? $get_thumbnail : '<img width="80" src="' . plugins_url('wpview/assets/images/relation.jpg') . '" />';
                            $structure .= '<div class="relation-icons-group">';
                            if (get_the_post_thumbnail_url($value, 'full')) {
                                $structure .= '<a class="wpview_relation_thumbnail-zoom wpview_relation_icon" href="' . get_the_post_thumbnail_url($value, 'full') . '"><span class="wpview-icon-zoom-in"></span></a>';
                            } else {
                                $structure .= '<span class="wpview_relation_thumbnail-zoom wpview_relation_icon wpview-icon-zoom-in"></span>';
                            }
                            $structure .= '<a class="wpview_relation_icon" href="' . esc_url($get_permalink) . '"><span class="wpview-icon-link"></span></a>';
                            $structure .= '</div>';
                            $structure .= '</div>';
                            $structure .= '</div>';
                            $structure .= '<div class="wpview_relation_title">';
                            $structure .= '<a class="wpview_relation_icon" href="' . esc_url($get_permalink) . '">';
                            $structure .= esc_html($post_title);
                            $structure .= '</a>';
                            $structure .= '</div>';
                            $structure .= '</div>';
                        }
                    }
                }
                $structure .= '</div>';
            } else if ($view === 'without_thumbnail') {
                $structure .= '<div class="wpview_relation_view">';
                foreach ($data['value'] as $key => $single_data) {
                    if (!is_array($single_data['value'])) {
                        $get_permalink = get_permalink($single_data['value']);
                        $post = get_post($single_data['value']);
                        $post_title = $post->post_title;
                        $structure .= '<div class="wpview_relation wpview_relation_without_thumbnail">';
                        $structure .= '<span class="wpview_field_label relation_without_thumb">';
                        if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                            $structure .= ' <strong>' . $single_data['title'] . ':</strong>&nbsp;';
                        }
                        $structure .= '</span>';
                        $structure .= '<a href="' . esc_url($get_permalink) . '" title="' . esc_attr($post_title) . '">';
                        $structure .= esc_html($post_title);
                        $structure .= '</a>';
                        $structure .= '</div>';
                    } else {
                        foreach ($single_data['value'] as $k => $value) {
                            $get_permalink = get_permalink($single_data['value']);
                            $post = get_post($single_data['value']);
                            $post_title = $post->post_title;
                            $structure .= '<div class="wpview_relation wpview_relation_without_thumbnail">';
                            $structure .= '<span class="wpview_field_label relation_without_thumb">';
                            if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                                $structure .= ' <strong>' . $single_data['title'] . ':</strong>&nbsp;';
                            }
                            $structure .= '</span>';
                            $structure .= '<a href="' . esc_url($get_permalink) . '" title="' . esc_attr($post_title) . '">';
                            $structure .= esc_html($post_title);
                            $structure .= '</a>';
                            $structure .= '</div>';
                        }
                    }
                }
                $structure .= '</div>';
            } else if ($view === 'with_table') {
                $structure .= '<div class="wpview_relation_view">';
                $structure .= '<table class="wpview_table" cellspacing="0">';
                foreach ($data['value'] as $key => $single_data) {
                    if (!is_array($single_data['value'])) {
                        $post = get_post($single_data['value']);
                        $post_title = $post->post_title;
                        $get_permalink = get_permalink($single_data['value']);
                        $structure .= '<tr>';
                        if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                            $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                        }
                        $structure .= '<td>';
                        $structure .= '<div>';
                        $structure .= '<div class="wpview_relation">';
                        $get_thumbnail = get_the_post_thumbnail($single_data['value'], array(80, 80));
                        $structure .= $get_thumbnail ? $get_thumbnail : '<img width="80" src="' . plugins_url('wpview/assets/images/relation.jpg') . '" />';
                        $structure .= '<div class="relation-icons-group">';
                        if (get_the_post_thumbnail_url($single_data['value'], 'full')) {
                            $structure .= '<a class="wpview_relation_thumbnail-zoom wpview_relation_icon" href="' . get_the_post_thumbnail_url($single_data['value'], 'full') . '"><span class="wpview-icon-zoom-in"></span></a>';
                        } else {
                            $structure .= '<span class="wpview_relation_thumbnail-zoom wpview_relation_icon wpview-icon-zoom-in"></span>';
                        }
                        $structure .= '<a class="wpview_relation_icon" href="' . esc_url($get_permalink) . '"><span class="wpview-icon-link"></span></a>';
                        $structure .= '</div>';
                        $structure .= '</div>';
                        $structure .= '<div class="wpview_relation_title">';
                        $structure .= '<a class="wpview_relation_icon" href="' . esc_url($get_permalink) . '">';
                        $structure .= esc_html($post_title);
                        $structure .= '</a>';
                        $structure .= '</div>';
                        $structure .= '</div>';
                        $structure .= '</td>';
                        $structure .= '</tr>';
                    } else {
                        foreach ($single_data['value'] as $k => $value) {
                            $post = get_post($single_data['value']);
                            $post_title = $post->post_title;
                            $get_permalink = get_permalink($single_data['value']);
                            $structure .= '<tr>';
                            if ($options['views_for_all'][$data['type']]['show_titles'] === 'yes') {
                                $structure .= '<td><strong>' . esc_html($single_data['title']) . '</strong></td>';
                            }
                            $structure .= '<td>';
                            $structure .= '<div>';
                            $structure .= '<div class="wpview_relation">';
                            $get_thumbnail = get_the_post_thumbnail($single_data['value'], array(80, 80));
                            $structure .= $get_thumbnail ? $get_thumbnail : '<img width="80" src="' . plugins_url('wpview/assets/images/relation.jpg') . '" />';
                            $structure .= '<div class="relation-icons-group">';
                            if (get_the_post_thumbnail_url($single_data['value'], 'full')) {
                                $structure .= '<a class="wpview_relation_thumbnail-zoom wpview_relation_icon" href="' . get_the_post_thumbnail_url($single_data['value'], 'full') . '"><span class="wpview-icon-zoom-in"></span></a>';
                            } else {
                                $structure .= '<span class="wpview_relation_thumbnail-zoom wpview_relation_icon wpview-icon-zoom-in"></span>';
                            }
                            $structure .= '<a class="wpview_relation_icon" href="' . esc_url($get_permalink) . '"><span class="wpview-icon-link"></span></a>';
                            $structure .= '</div>';
                            $structure .= '</div>';
                            $structure .= '<div class="wpview_relation_title">';
                            $structure .= '<a class="wpview_relation_icon" href="' . esc_url($get_permalink) . '">';
                            $structure .= esc_html($post_title);
                            $structure .= '</a>';
                            $structure .= '</div>';
                            $structure .= '</div>';
                            $structure .= '</td>';
                            $structure .= '</tr>';
                        }
                    }
                }
                $structure .= '</table>';
                $structure .= '</div>';
            }
        }
        return $structure;
    }

    public function relationScript() {
        $options = get_option(WPVIEW_VIEWS);
        if (isset($options['views_for_all']['relation']['view']) && $options['views_for_all']['relation']['view'] !== 'none') {
            wp_register_script('wpview_relation_script', plugins_url('wpview/assets/js/relation.js'), array('jquery'), null, true);
            wp_enqueue_script('wpview_relation_script');
        }
    }

}
