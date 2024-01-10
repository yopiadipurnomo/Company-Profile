<?php

// Enqueue styles and scripts
function company_profile_enqueue_scripts() {
    wp_enqueue_style('style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'company_profile_enqueue_scripts');

// Fungsi untuk memeriksa apakah plugin Elementor terinstal dan aktif
function company_profile_check_elementor_plugin() {
    if (!class_exists('Elementor\Plugin') || !did_action('elementor/loaded')) {
        add_action('admin_notices', 'company_profile_elementor_notice');
    }
}
add_action('admin_init', 'company_profile_check_elementor_plugin'); // Ubah hook ke admin_init

// Fungsi untuk menampilkan pemberitahuan jika Elementor tidak diinstal atau aktif
function company_profile_elementor_notice() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e('Tema ini merekomendasikan plugin Elementor untuk fungsionalitas penuh. Silakan instal dan aktifkan Elementor.', 'company-profile-theme'); ?></p>
    </div>
    <?php
}

// Fungsi untuk mengimpor template Elementor dan membuatnya sebagai halaman utama
function company_profile_import_elementor_template_and_set_homepage() {
    if (class_exists('Elementor\Plugin') && did_action('elementor/loaded')) {
        $template_path = get_template_directory() . '/elementor-templates/company-profile.json';

        if (file_exists($template_path)) {
            $template_data = json_decode(file_get_contents($template_path), true);
            if (!empty($template_data)) {
                // Impor template Elementor menggunakan fungsi Elementor
                $template_id = \Elementor\Plugin::$instance->templates_manager->import_template($template_data);

                // Tetapkan template sebagai halaman utama
                if (!is_wp_error($template_id)) {
                    update_option('page_on_front', $template_id);
                    update_option('show_on_front', 'page');
                }
            }
        }
    } else {
        // Jika Elementor tidak aktif, tampilkan pemberitahuan
        add_action('admin_notices', 'company_profile_elementor_not_active_notice');
    }
}
add_action('init', 'company_profile_import_elementor_template_and_set_homepage'); // Ubah hook ke init

// Fungsi untuk menampilkan pemberitahuan jika Elementor tidak aktif
function company_profile_elementor_not_active_notice() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e('Tema ini memerlukan plugin Elementor yang diaktifkan untuk dapat mengimpor template dan mengatur sebagai halaman utama.', 'company-profile-theme'); ?></p>
    </div>
    <?php
}
