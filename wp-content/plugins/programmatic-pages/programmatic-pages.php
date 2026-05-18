<?php
/*
Plugin Name: CPT Pages
Description: Manage programmatically created pages via CPT and settings.
Version: 1.0.0
Author: CT
*/

if ( ! defined( 'ABSPATH' ) ) {
exit;
}

function ct_is_programmatic_cpt_enabled() {
return 'yes' === get_option( 'ct_programmatic_cpt_enabled', 'yes' );
}

function ct_get_programmatic_post_types() {
$stored = get_option( 'ct_programmatic_post_types', array() );
$post_types = array();

if ( is_array( $stored ) ) {
foreach ( $stored as $slug => $label ) {
$clean_slug = sanitize_key( (string) $slug );
if ( '' === $clean_slug ) {
continue;
}

$clean_label = trim( sanitize_text_field( (string) $label ) );
if ( '' === $clean_label ) {
$clean_label = ucwords( str_replace( '_', ' ', $clean_slug ) );
}

$post_types[ $clean_slug ] = $clean_label;
}
}

if ( empty( $post_types ) ) {
$legacy_slug = sanitize_key( (string) get_option( 'ct_programmatic_post_type', '' ) );
$legacy_label = trim( (string) get_option( 'ct_programmatic_post_type_label', '' ) );
if ( '' !== $legacy_slug ) {
if ( '' === $legacy_label ) {
$legacy_label = ucwords( str_replace( '_', ' ', $legacy_slug ) );
}
$post_types[ $legacy_slug ] = $legacy_label;
}
}

return $post_types;
}

function ct_get_programmatic_post_type() {
$post_types = ct_get_programmatic_post_types();
$selected = sanitize_key( (string) get_option( 'ct_programmatic_post_type', '' ) );

if ( '' !== $selected && isset( $post_types[ $selected ] ) ) {
return $selected;
}

return (string) key( $post_types );
}

function ct_get_programmatic_post_type_label() {
$post_types = ct_get_programmatic_post_types();
$current = ct_get_programmatic_post_type();

if ( isset( $post_types[ $current ] ) ) {
return $post_types[ $current ];
}

return 'CPT Pages';
}

function ct_generate_programmatic_cpt_slug( $name ) {
$slug = strtolower( sanitize_text_field( (string) $name ) );
$slug = preg_replace( '/[^a-z0-9]+/', '_', $slug );
$slug = trim( (string) $slug, '_' );

// WordPress post type keys are limited to 20 characters.
if ( strlen( (string) $slug ) > 20 ) {
$compact_slug = str_replace( '_', '', (string) $slug );
if ( '' !== $compact_slug ) {
$slug = $compact_slug;
}
}

$slug = substr( (string) $slug, 0, 20 );

if ( '' === $slug ) {
return 'programmatic_page';
}

return $slug;
}

function ct_get_programmatic_fields() {
return array(
array(
'label' => 'City',
'key'   => 'ct_city',
'type'  => 'text',
),
array(
'label' => 'Page URL Slug',
'key'   => 'ct_page_url_slug',
'type'  => 'text',
),
array(
'label' => 'Meta Title',
'key'   => 'ct_meta_title',
'type'  => 'text',
),
array(
'label' => 'Meta Description',
'key'   => 'ct_meta_description',
'type'  => 'text',
),
array(
'label' => 'Hero H1',
'key'   => 'ct_hero_h1',
'type'  => 'text',
),
array(
'label' => 'Hero Intro Paragraph',
'key'   => 'ct_hero_intro_paragraph',
'type'  => 'text',
),
array(
'label' => 'Why Section H2',
'key'   => 'ct_why_section_h2',
'type'  => 'text',
),
array(
'label' => 'Why Point 1 Title',
'key'   => 'ct_why_point_1_title',
'type'  => 'text',
),
array(
'label' => 'Why Point 1 Description',
'key'   => 'ct_why_point_1_description',
'type'  => 'text',
),
array(
'label' => 'Why Point 2 Title',
'key'   => 'ct_why_point_2_title',
'type'  => 'text',
),
array(
'label' => 'Why Point 2 Description',
'key'   => 'ct_why_point_2_description',
'type'  => 'text',
),
array(
'label' => 'Why Point 3 Title',
'key'   => 'ct_why_point_3_title',
'type'  => 'text',
),
array(
'label' => 'Why Point 3 Description',
'key'   => 'ct_why_point_3_description',
'type'  => 'text',
),
array(
'label' => 'Why Point 4 Title',
'key'   => 'ct_why_point_4_title',
'type'  => 'text',
),
array(
'label' => 'Why Point 4 Description',
'key'   => 'ct_why_point_4_description',
'type'  => 'text',
),
array(
'label' => 'Why Point 5 Title',
'key'   => 'ct_why_point_5_title',
'type'  => 'text',
),
array(
'label' => 'Why Point 5 Description',
'key'   => 'ct_why_point_5_description',
'type'  => 'text',
),
array(
'label' => 'Services Section H2',
'key'   => 'ct_services_section_h2',
'type'  => 'text',
),
array(
'label' => 'Service 1 Name',
'key'   => 'ct_service_1_name',
'type'  => 'text',
),
array(
'label' => 'Service 1 Description',
'key'   => 'ct_service_1_description',
'type'  => 'text',
),
array(
'label' => 'Service 2 Name',
'key'   => 'ct_service_2_name',
'type'  => 'text',
),
array(
'label' => 'Service 2 Description',
'key'   => 'ct_service_2_description',
'type'  => 'text',
),
array(
'label' => 'Process Section H2',
'key'   => 'ct_process_section_h2',
'type'  => 'text',
),
array(
'label' => 'Process Section Sub-text',
'key'   => 'ct_process_section_sub_text',
'type'  => 'text',
),
array(
'label' => 'Step 1 Title',
'key'   => 'ct_step_1_title',
'type'  => 'text',
),
array(
'label' => 'Step 1 Description',
'key'   => 'ct_step_1_description',
'type'  => 'text',
),
array(
'label' => 'Step 2 Title',
'key'   => 'ct_step_2_title',
'type'  => 'text',
),
array(
'label' => 'Step 2 Description',
'key'   => 'ct_step_2_description',
'type'  => 'text',
),
array(
'label' => 'Step 3 Title',
'key'   => 'ct_step_3_title',
'type'  => 'text',
),
array(
'label' => 'Step 3 Description',
'key'   => 'ct_step_3_description',
'type'  => 'text',
),
array(
'label' => 'Step 4 Title',
'key'   => 'ct_step_4_title',
'type'  => 'text',
),
array(
'label' => 'Step 4 Description',
'key'   => 'ct_step_4_description',
'type'  => 'text',
),
array(
'label' => 'Cost Section H2',
'key'   => 'ct_cost_section_h2',
'type'  => 'text',
),
array(
'label' => 'Cost Intro Text',
'key'   => 'ct_cost_intro_text',
'type'  => 'text',
),
array(
'label' => 'Locations Section H2',
'key'   => 'ct_locations_section_h2',
'type'  => 'text',
),
array(
'label' => 'Nearby Area 1',
'key'   => 'ct_nearby_area_1',
'type'  => 'text',
),
array(
'label' => 'Nearby Area 2',
'key'   => 'ct_nearby_area_2',
'type'  => 'text',
),
array(
'label' => 'Nearby Area 3',
'key'   => 'ct_nearby_area_3',
'type'  => 'text',
),
array(
'label' => 'Nearby Area 4',
'key'   => 'ct_nearby_area_4',
'type'  => 'text',
),
array(
'label' => 'Service Area Paragraph',
'key'   => 'ct_service_area_paragraph',
'type'  => 'text',
),
array(
'label' => 'Bottom CTA Heading',
'key'   => 'ct_bottom_cta_heading',
'type'  => 'text',
),
array(
'label' => 'Bottom CTA Text',
'key'   => 'ct_bottom_cta_text',
'type'  => 'text',
),
array(
'label' => 'FAQ Section H2',
'key'   => 'ct_faq_section_h2',
'type'  => 'text',
),
array(
'label' => 'FAQ 1 Question',
'key'   => 'ct_faq_1_question',
'type'  => 'text',
),
array(
'label' => 'FAQ 1 Answer',
'key'   => 'ct_faq_1_answer',
'type'  => 'text',
),
array(
'label' => 'FAQ 2 Question',
'key'   => 'ct_faq_2_question',
'type'  => 'text',
),
array(
'label' => 'FAQ 2 Answer',
'key'   => 'ct_faq_2_answer',
'type'  => 'text',
),
array(
'label' => 'FAQ 3 Question',
'key'   => 'ct_faq_3_question',
'type'  => 'text',
),
array(
'label' => 'FAQ 3 Answer',
'key'   => 'ct_faq_3_answer',
'type'  => 'text',
),
array(
'label' => 'FAQ 4 Question',
'key'   => 'ct_faq_4_question',
'type'  => 'text',
),
array(
'label' => 'FAQ 4 Answer',
'key'   => 'ct_faq_4_answer',
'type'  => 'text',
),
array(
'label' => 'FAQ 5 Question',
'key'   => 'ct_faq_5_question',
'type'  => 'text',
),
array(
'label' => 'FAQ 5 Answer',
'key'   => 'ct_faq_5_answer',
'type'  => 'text',
),
array(
'label' => 'FAQ 6 Question',
'key'   => 'ct_faq_6_question',
'type'  => 'text',
),
array(
'label' => 'FAQ 6 Answer',
'key'   => 'ct_faq_6_answer',
'type'  => 'text',
),
array(
'label' => 'FAQ 7 Question',
'key'   => 'ct_faq_7_question',
'type'  => 'text',
),
array(
'label' => 'FAQ 7 Answer',
'key'   => 'ct_faq_7_answer',
'type'  => 'text',
),
);
}

function ct_register_programmatic_page_cpt() {
if ( ! ct_is_programmatic_cpt_enabled() ) {
return;
}

$post_types = ct_get_programmatic_post_types();
foreach ( $post_types as $post_type => $plural_label ) {
$singular_label = rtrim( $plural_label, 's' );
if ( '' === $singular_label ) {
$singular_label = $plural_label;
}

$labels = array(
'name'               => $plural_label,
'singular_name'      => $singular_label,
'add_new'            => 'Add New',
'add_new_item'       => 'Add New ' . $singular_label,
'edit_item'          => 'Edit ' . $singular_label,
'new_item'           => 'New ' . $singular_label,
'view_item'          => 'View ' . $singular_label,
'search_items'       => 'Search ' . $plural_label,
'not_found'          => 'No ' . $plural_label . ' found',
'not_found_in_trash' => 'No ' . $plural_label . ' found in Trash',
);

$args = array(
'labels'       => $labels,
'public'       => true,
'has_archive'  => true,
'show_in_menu' => true,
'supports'     => array( 'title', 'editor' ),
);

register_post_type( $post_type, $args );
}
}
add_action( 'init', 'ct_register_programmatic_page_cpt' );

function ct_schedule_programmatic_rewrite_flush() {
update_option( 'ct_programmatic_flush_rewrite', 'yes' );
}

function ct_maybe_flush_programmatic_rewrite_rules() {
if ( 'yes' !== get_option( 'ct_programmatic_flush_rewrite', 'no' ) ) {
return;
}

flush_rewrite_rules();
delete_option( 'ct_programmatic_flush_rewrite' );
}
add_action( 'init', 'ct_maybe_flush_programmatic_rewrite_rules', 99 );

function ct_programmatic_pages_activate() {
if ( false === get_option( 'ct_programmatic_cpt_enabled', false ) ) {
add_option( 'ct_programmatic_cpt_enabled', 'no' );
add_option( 'ct_programmatic_setup_needed', 'yes' );
}

if ( false === get_option( 'ct_programmatic_post_types', false ) ) {
add_option( 'ct_programmatic_post_types', array() );
}

add_option( 'ct_programmatic_activation_redirect', 'yes' );

if ( ct_is_programmatic_cpt_enabled() ) {
ct_register_programmatic_page_cpt();
}

flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ct_programmatic_pages_activate' );

function ct_programmatic_maybe_redirect_to_settings() {
if ( ! current_user_can( 'manage_options' ) ) {
return;
}

if ( 'yes' !== get_option( 'ct_programmatic_activation_redirect', 'no' ) ) {
return;
}

if ( wp_doing_ajax() ) {
return;
}

delete_option( 'ct_programmatic_activation_redirect' );
wp_safe_redirect( admin_url( 'admin.php?page=ct_programmatic_settings' ) );
exit;
}
add_action( 'admin_init', 'ct_programmatic_maybe_redirect_to_settings' );

function ct_programmatic_plugin_action_links( $links ) {
$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=ct_programmatic_settings' ) ) . '">Settings</a>';
array_unshift( $links, $settings_link );
return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ct_programmatic_plugin_action_links' );

function ct_programmatic_pages_deactivate() {
// Remove all generated Programmatic Page entries when plugin is deactivated.
$post_types = ct_get_programmatic_post_types();
foreach ( array_keys( $post_types ) as $post_type ) {
$programmatic_posts = get_posts(
array(
'post_type' => $post_type,
'post_status' => 'any',
'numberposts' => -1,
'fields' => 'ids',
)
);

foreach ( $programmatic_posts as $programmatic_post_id ) {
wp_delete_post( (int) $programmatic_post_id, true );
}
}

delete_option( 'ct_programmatic_cpt_enabled' );
delete_option( 'ct_programmatic_setup_needed' );
delete_option( 'ct_programmatic_activation_redirect' );
delete_option( 'ct_programmatic_post_type' );
delete_option( 'ct_programmatic_post_type_label' );
delete_option( 'ct_programmatic_post_types' );

flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ct_programmatic_pages_deactivate' );

function ct_add_admin_menu() {
add_menu_page(
'CPT Pages',
'CPT Pages',
'manage_options',
'ct_programmatic_settings',
'ct_settings_page_html',
'dashicons-admin-generic',
59
);

if ( ct_is_programmatic_cpt_enabled() ) {
$post_types = ct_get_programmatic_post_types();
foreach ( $post_types as $post_type => $label ) {
add_submenu_page(
'edit.php?post_type=' . $post_type,
'Import CSV',
'Import CSV',
'manage_options',
'ct_programmatic_import_' . $post_type,
'ct_render_programmatic_import_page'
);
}
}
}
add_action( 'admin_menu', 'ct_add_admin_menu' );

function ct_settings_page_html() {
if ( ! current_user_can( 'manage_options' ) ) {
return;
}

$enabled = ct_is_programmatic_cpt_enabled();
$post_type = ct_get_programmatic_post_type();
$cpt_label = ct_get_programmatic_post_type_label();
$post_types = ct_get_programmatic_post_types();
$deleted_cpt = isset( $_GET['deleted_cpt'] ) ? sanitize_key( wp_unslash( $_GET['deleted_cpt'] ) ) : '';
?>
<div class="wrap">
<h1>CPT Pages Settings</h1>
<?php if ( '' !== $deleted_cpt ) : ?>
<div class="notice notice-success is-dismissible"><p><?php echo esc_html( 'Deleted CPT: ' . $deleted_cpt ); ?></p></div>
<?php endif; ?>
<?php
$updated_cpt = isset( $_GET['updated_cpt'] ) ? sanitize_key( wp_unslash( $_GET['updated_cpt'] ) ) : '';
$edit_cpt = isset( $_GET['edit_cpt'] ) ? sanitize_key( wp_unslash( $_GET['edit_cpt'] ) ) : '';
?>
<?php if ( '' !== $updated_cpt ) : ?>
<div class="notice notice-success is-dismissible"><p><?php echo esc_html( 'Updated CPT: ' . $updated_cpt ); ?></p></div>
<?php endif; ?>
<div class="notice notice-info" style="padding:12px 14px; margin: 14px 0 18px;">
<p><strong>CPT Pages Setup</strong></p>
<p>Create a custom CPT now to start generating pages with the default layout.</p>
<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:10px;">
<?php wp_nonce_field( 'ct_programmatic_setup_cpt' ); ?>
<input type="hidden" name="action" value="ct_programmatic_setup_cpt" />
<input type="hidden" name="choice" value="create" />
<label for="ct-programmatic-cpt-name"><strong>CPT Name</strong></label>
<input id="ct-programmatic-cpt-name" name="cpt_name" type="text" class="regular-text" value="" maxlength="40" placeholder="CPT Pages" required />
<p class="description">Enter a CPT name. A valid CPT slug will be generated automatically.</p>
<p><button type="submit" class="button button-primary">Create CPT</button></p>
</form>
</div>
<?php if ( $enabled ) : ?>
<?php if ( ! empty( $post_types ) ) : ?>
<h2>Created CPTs</h2>
<table class="wp-list-table widefat fixed striped table-view-list pages" style="max-width:1100px;">
<thead>
<tr>
<td id="cb" class="manage-column column-cb check-column"><input type="checkbox" disabled /></td>
<th scope="col" class="manage-column column-title column-primary">CPT Name</th>
<th scope="col" class="manage-column">CPT Slug</th>
<th scope="col" class="manage-column">Posts</th>
</tr>
</thead>
<tbody id="the-list">
<?php foreach ( $post_types as $slug => $label ) : ?>
<?php
$delete_url = wp_nonce_url(
admin_url( 'admin-post.php?action=ct_programmatic_delete_cpt&cpt=' . $slug ),
'ct_programmatic_delete_cpt_' . $slug
);
$edit_url = admin_url( 'admin.php?page=ct_programmatic_settings&edit_cpt=' . $slug );
$list_url = admin_url( 'edit.php?post_type=' . $slug );
$import_url = admin_url( 'admin.php?page=ct_programmatic_import_' . $slug );
$count_object = wp_count_posts( $slug );
$total_posts = 0;
foreach ( (array) $count_object as $status_count ) {
$total_posts += (int) $status_count;
}
?>
<tr>
<th scope="row" class="check-column"><input type="checkbox" disabled /></th>
<td class="title column-title has-row-actions column-primary page-title">
<strong><a href="<?php echo esc_url( $list_url ); ?>" class="row-title"><?php echo esc_html( $label ); ?></a></strong>
<div class="row-actions">
<span class="edit"><a href="<?php echo esc_url( $list_url ); ?>">All</a> | </span>
<span class="view"><a href="<?php echo esc_url( $import_url ); ?>">Import CSV</a> | </span>
<span class="edit"><a href="<?php echo esc_url( $edit_url ); ?>">Edit CPT</a> | </span>
<span class="trash"><a href="<?php echo esc_url( $delete_url ); ?>" class="submitdelete" onclick="return confirm('Delete CPT <?php echo esc_js( $slug ); ?> and all its posts?');">Delete</a></span>
</div>
<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
</td>
<td><code><?php echo esc_html( $slug ); ?></code></td>
<td><?php echo esc_html( (string) $total_posts ); ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php if ( '' !== $edit_cpt && isset( $post_types[ $edit_cpt ] ) ) : ?>
<h2 style="margin-top:20px;">Edit CPT</h2>
<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="max-width:700px;">
<?php wp_nonce_field( 'ct_programmatic_edit_cpt_' . $edit_cpt ); ?>
<input type="hidden" name="action" value="ct_programmatic_edit_cpt" />
<input type="hidden" name="cpt" value="<?php echo esc_attr( $edit_cpt ); ?>" />
<table class="form-table" role="presentation">
<tbody>
<tr>
<th scope="row"><label for="ct-edit-cpt-name">CPT Name</label></th>
<td><input id="ct-edit-cpt-name" name="cpt_name" type="text" class="regular-text" value="<?php echo esc_attr( $post_types[ $edit_cpt ] ); ?>" maxlength="40" required /></td>
</tr>
</tbody>
</table>
<?php submit_button( 'Update CPT' ); ?>
</form>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
</div>
<?php
}

function ct_programmatic_setup_admin_notice() {
if ( ! current_user_can( 'manage_options' ) ) {
return;
}

if ( isset( $_GET['page'] ) && 'ct_programmatic_settings' === sanitize_key( wp_unslash( $_GET['page'] ) ) ) {
return;
}

if ( 'yes' !== get_option( 'ct_programmatic_setup_needed', 'no' ) ) {
return;
}

$create_url = admin_url( 'admin.php?page=ct_programmatic_settings' );
$skip_url = wp_nonce_url(
admin_url( 'admin-post.php?action=ct_programmatic_setup_cpt&choice=skip' ),
'ct_programmatic_setup_cpt'
);

echo '<div class="notice notice-info"><p><strong>CPT Pages Setup</strong></p><p>Open CPT Pages settings to enter your CPT name and create it.</p><p><a class="button button-primary" href="' . esc_url( $create_url ) . '">Open Settings</a> <a class="button" href="' . esc_url( $skip_url ) . '">Not now</a></p></div>';
}
add_action( 'admin_notices', 'ct_programmatic_setup_admin_notice' );

function ct_programmatic_handle_setup_cpt() {
if ( ! current_user_can( 'manage_options' ) ) {
wp_die( 'Unauthorized request.' );
}

check_admin_referer( 'ct_programmatic_setup_cpt' );

$choice = '';
if ( isset( $_REQUEST['choice'] ) ) {
$choice = sanitize_text_field( wp_unslash( $_REQUEST['choice'] ) );
}

if ( 'create' === $choice ) {
$requested_name = isset( $_REQUEST['cpt_name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cpt_name'] ) ) : '';
if ( '' === $requested_name ) {
$requested_name = 'CPT Pages';
}

$requested_post_type = ct_generate_programmatic_cpt_slug( $requested_name );

$post_types = ct_get_programmatic_post_types();
$post_types[ $requested_post_type ] = $requested_name;
update_option( 'ct_programmatic_post_types', $post_types );
update_option( 'ct_programmatic_post_type', $requested_post_type );
update_option( 'ct_programmatic_post_type_label', $requested_name );
update_option( 'ct_programmatic_cpt_enabled', 'yes' );
update_option( 'ct_programmatic_setup_needed', 'no' );
ct_register_programmatic_page_cpt();
ct_schedule_programmatic_rewrite_flush();
wp_safe_redirect( admin_url( 'edit.php?post_type=' . ct_get_programmatic_post_type() ) );
exit;
}

if ( 'skip' === $choice ) {
update_option( 'ct_programmatic_setup_needed', 'no' );
wp_safe_redirect( admin_url( 'admin.php?page=ct_programmatic_settings' ) );
exit;
}

wp_safe_redirect( admin_url() );
exit;
}
add_action( 'admin_post_ct_programmatic_setup_cpt', 'ct_programmatic_handle_setup_cpt' );

function ct_programmatic_delete_cpt() {
if ( ! current_user_can( 'manage_options' ) ) {
wp_die( 'Unauthorized request.' );
}

$cpt = isset( $_GET['cpt'] ) ? sanitize_key( wp_unslash( $_GET['cpt'] ) ) : '';
if ( '' === $cpt ) {
wp_safe_redirect( admin_url( 'admin.php?page=ct_programmatic_settings' ) );
exit;
}

check_admin_referer( 'ct_programmatic_delete_cpt_' . $cpt );

$post_types = ct_get_programmatic_post_types();
if ( isset( $post_types[ $cpt ] ) ) {
$posts = get_posts(
array(
'post_type' => $cpt,
'post_status' => 'any',
'numberposts' => -1,
'fields' => 'ids',
)
);

foreach ( $posts as $post_id ) {
wp_delete_post( (int) $post_id, true );
}

unset( $post_types[ $cpt ] );
}

if ( empty( $post_types ) ) {
update_option( 'ct_programmatic_post_types', array() );
update_option( 'ct_programmatic_cpt_enabled', 'no' );
update_option( 'ct_programmatic_setup_needed', 'yes' );
update_option( 'ct_programmatic_post_type', '' );
update_option( 'ct_programmatic_post_type_label', '' );
} else {
update_option( 'ct_programmatic_post_types', $post_types );
$next_slug = (string) key( $post_types );
update_option( 'ct_programmatic_post_type', $next_slug );
update_option( 'ct_programmatic_post_type_label', (string) $post_types[ $next_slug ] );
update_option( 'ct_programmatic_cpt_enabled', 'yes' );
}

ct_schedule_programmatic_rewrite_flush();
wp_safe_redirect( admin_url( 'admin.php?page=ct_programmatic_settings&deleted_cpt=' . $cpt ) );
exit;
}
add_action( 'admin_post_ct_programmatic_delete_cpt', 'ct_programmatic_delete_cpt' );

function ct_programmatic_edit_cpt() {
if ( ! current_user_can( 'manage_options' ) ) {
wp_die( 'Unauthorized request.' );
}

$cpt = isset( $_POST['cpt'] ) ? sanitize_key( wp_unslash( $_POST['cpt'] ) ) : '';
if ( '' === $cpt ) {
wp_safe_redirect( admin_url( 'admin.php?page=ct_programmatic_settings' ) );
exit;
}

check_admin_referer( 'ct_programmatic_edit_cpt_' . $cpt );

$new_name = isset( $_POST['cpt_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cpt_name'] ) ) : '';
if ( '' === $new_name ) {
$new_name = ucwords( str_replace( '_', ' ', $cpt ) );
}

$post_types = ct_get_programmatic_post_types();
if ( isset( $post_types[ $cpt ] ) ) {
$new_slug = ct_generate_programmatic_cpt_slug( $new_name );

if ( $new_slug !== $cpt && isset( $post_types[ $new_slug ] ) ) {
$new_slug = $cpt;
}

if ( $new_slug !== $cpt ) {
global $wpdb;
$wpdb->update(
$wpdb->posts,
array( 'post_type' => $new_slug ),
array( 'post_type' => $cpt ),
array( '%s' ),
array( '%s' )
);

unset( $post_types[ $cpt ] );
$post_types[ $new_slug ] = $new_name;
update_option( 'ct_programmatic_post_types', $post_types );

if ( ct_get_programmatic_post_type() === $cpt ) {
update_option( 'ct_programmatic_post_type', $new_slug );
update_option( 'ct_programmatic_post_type_label', $new_name );
}

ct_schedule_programmatic_rewrite_flush();
wp_safe_redirect( admin_url( 'admin.php?page=ct_programmatic_settings&updated_cpt=' . $new_slug ) );
exit;
}

$post_types[ $cpt ] = $new_name;
update_option( 'ct_programmatic_post_types', $post_types );

if ( ct_get_programmatic_post_type() === $cpt ) {
update_option( 'ct_programmatic_post_type_label', $new_name );
}
}

wp_safe_redirect( admin_url( 'admin.php?page=ct_programmatic_settings&updated_cpt=' . $cpt ) );
exit;
}
add_action( 'admin_post_ct_programmatic_edit_cpt', 'ct_programmatic_edit_cpt' );

function ct_add_programmatic_page_meta_boxes() {
$post_types = ct_get_programmatic_post_types();
foreach ( $post_types as $post_type => $label ) {
add_meta_box(
'ct_programmatic_page_fields',
'Custom Fields',
'ct_render_programmatic_page_fields',
$post_type,
'normal',
'default'
);
}
}
add_action( 'add_meta_boxes', 'ct_add_programmatic_page_meta_boxes' );

function ct_get_programmatic_textarea_keys() {
return array(
'ct_meta_description',
'ct_hero_intro_paragraph',
'ct_why_point_1_description',
'ct_why_point_2_description',
'ct_why_point_3_description',
'ct_why_point_4_description',
'ct_why_point_5_description',
'ct_service_1_description',
'ct_service_2_description',
'ct_process_section_sub_text',
'ct_step_1_description',
'ct_step_2_description',
'ct_step_3_description',
'ct_step_4_description',
'ct_cost_intro_text',
'ct_service_area_paragraph',
'ct_bottom_cta_text',
'ct_faq_1_answer',
'ct_faq_2_answer',
'ct_faq_3_answer',
'ct_faq_4_answer',
'ct_faq_5_answer',
'ct_faq_6_answer',
'ct_faq_7_answer',
);
}

function ct_render_programmatic_page_fields( $post ) {
wp_nonce_field( 'ct_save_programmatic_page_fields', 'ct_programmatic_page_nonce' );

$fields = ct_get_programmatic_fields();
$textarea_keys = array_flip( ct_get_programmatic_textarea_keys() );

$fields_by_key = array();
foreach ( $fields as $field ) {
$fields_by_key[ $field['key'] ] = $field;
}

$sections = array(
'Page Basics' => array(
'ct_city',
'ct_page_url_slug',
),
'SEO' => array(
'ct_meta_title',
'ct_meta_description',
),
'Hero' => array(
'ct_hero_h1',
'ct_hero_intro_paragraph',
),
'Why' => array(
'ct_why_section_h2',
'ct_why_point_1_title',
'ct_why_point_1_description',
'ct_why_point_2_title',
'ct_why_point_2_description',
'ct_why_point_3_title',
'ct_why_point_3_description',
'ct_why_point_4_title',
'ct_why_point_4_description',
'ct_why_point_5_title',
'ct_why_point_5_description',
),
'Services' => array(
'ct_services_section_h2',
'ct_service_1_name',
'ct_service_1_description',
'ct_service_2_name',
'ct_service_2_description',
),
'Process' => array(
'ct_process_section_h2',
'ct_process_section_sub_text',
'ct_step_1_title',
'ct_step_1_description',
'ct_step_2_title',
'ct_step_2_description',
'ct_step_3_title',
'ct_step_3_description',
'ct_step_4_title',
'ct_step_4_description',
),
'Cost' => array(
'ct_cost_section_h2',
'ct_cost_intro_text',
),
'Locations' => array(
'ct_locations_section_h2',
'ct_nearby_area_1',
'ct_nearby_area_2',
'ct_nearby_area_3',
'ct_nearby_area_4',
'ct_service_area_paragraph',
),
'Bottom CTA' => array(
'ct_bottom_cta_heading',
'ct_bottom_cta_text',
),
'FAQ' => array(
'ct_faq_section_h2',
'ct_faq_1_question',
'ct_faq_1_answer',
'ct_faq_2_question',
'ct_faq_2_answer',
'ct_faq_3_question',
'ct_faq_3_answer',
'ct_faq_4_question',
'ct_faq_4_answer',
'ct_faq_5_question',
'ct_faq_5_answer',
'ct_faq_6_question',
'ct_faq_6_answer',
'ct_faq_7_question',
'ct_faq_7_answer',
),
);

echo '<table class="form-table" role="presentation"><tbody>';
foreach ( $sections as $section_label => $keys ) {
echo '<tr><th colspan="2" style="padding-top:18px;"><h2 style="margin:0;">' . esc_html( $section_label ) . '</h2></th></tr>';
foreach ( $keys as $key ) {
if ( ! isset( $fields_by_key[ $key ] ) ) {
continue;
}
$field = $fields_by_key[ $key ];
$value = get_post_meta( $post->ID, $field['key'], true );
echo '<tr>';
echo '<th scope="row"><label for="' . esc_attr( $field['key'] ) . '">' . esc_html( $field['label'] ) . '</label></th>';
if ( isset( $textarea_keys[ $field['key'] ] ) ) {
echo '<td><textarea class="large-text" rows="4" id="' . esc_attr( $field['key'] ) . '" name="' . esc_attr( $field['key'] ) . '">' . esc_textarea( $value ) . '</textarea></td>';
} else {
echo '<td><input type="text" class="regular-text" id="' . esc_attr( $field['key'] ) . '" name="' . esc_attr( $field['key'] ) . '" value="' . esc_attr( $value ) . '" /></td>';
}
echo '</tr>';
}
}
echo '</tbody></table>';
}

function ct_sanitize_programmatic_field( $value, $type ) {
if ( 'url' === $type ) {
return esc_url_raw( trim( wp_unslash( $value ) ) );
}

return sanitize_text_field( wp_unslash( $value ) );
}

function ct_sync_yoast_meta_from_programmatic( $post_id ) {
$meta_title = trim( (string) get_post_meta( $post_id, 'ct_meta_title', true ) );
$meta_description = trim( (string) get_post_meta( $post_id, 'ct_meta_description', true ) );

if ( '' !== $meta_title ) {
update_post_meta( $post_id, '_yoast_wpseo_title', $meta_title );
} else {
delete_post_meta( $post_id, '_yoast_wpseo_title' );
}

if ( '' !== $meta_description ) {
update_post_meta( $post_id, '_yoast_wpseo_metadesc', $meta_description );
} else {
delete_post_meta( $post_id, '_yoast_wpseo_metadesc' );
}
}

function ct_save_programmatic_page_fields( $post_id ) {
if ( ! isset( $_POST['ct_programmatic_page_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ct_programmatic_page_nonce'] ) ), 'ct_save_programmatic_page_fields' ) ) {
return;
}

if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
return;
}

if ( wp_is_post_revision( $post_id ) ) {
return;
}

if ( ! current_user_can( 'edit_post', $post_id ) ) {
return;
}

$fields = ct_get_programmatic_fields();
$textarea_keys = array_flip( ct_get_programmatic_textarea_keys() );
foreach ( $fields as $field ) {
if ( isset( $_POST[ $field['key'] ] ) ) {
$sanitized = isset( $textarea_keys[ $field['key'] ] )
? sanitize_textarea_field( wp_unslash( $_POST[ $field['key'] ] ) )
: ct_sanitize_programmatic_field( $_POST[ $field['key'] ], $field['type'] );
update_post_meta( $post_id, $field['key'], $sanitized );
}
}

ct_sync_yoast_meta_from_programmatic( $post_id );
}
function ct_save_programmatic_page_fields_on_save_post( $post_id, $post ) {
if ( ! $post ) {
return;
}

$post_types = ct_get_programmatic_post_types();
if ( ! isset( $post_types[ $post->post_type ] ) ) {
return;
}

ct_save_programmatic_page_fields( $post_id );
}
add_action( 'save_post', 'ct_save_programmatic_page_fields_on_save_post', 10, 2 );

function ct_normalize_csv_header( $value ) {
$value = preg_replace( '/^\xEF\xBB\xBF/', '', (string) $value );
$value = trim( (string) $value );
$value = preg_replace( '/\s+/', ' ', $value );
return strtolower( $value );
}

function ct_detect_csv_delimiter( $line ) {
$candidates = array( ',', ';', "\t", '|' );
$best_delimiter = ',';
$best_count = 0;

foreach ( $candidates as $candidate ) {
$count = substr_count( $line, $candidate );
if ( $count > $best_count ) {
$best_count = $count;
$best_delimiter = $candidate;
}
}

return $best_delimiter;
}

function ct_get_csv_row_value( $row_assoc, $candidate_headers ) {
foreach ( $candidate_headers as $header ) {
foreach ( $row_assoc as $row_header => $row_value ) {
if ( ct_normalize_csv_header( $row_header ) === ct_normalize_csv_header( $header ) ) {
return (string) $row_value;
}
}
}

return '';
}

function ct_replace_template_variables( $value, $row_assoc ) {
return preg_replace_callback(
'/\{\{\s*([^}]+)\s*\}\}/',
function ( $matches ) use ( $row_assoc ) {
$token = trim( $matches[1] );
$normalized_token = ct_normalize_csv_header( $token );
$normalized_token_fallback = ct_normalize_csv_header( preg_replace( '/[^a-z0-9]+/i', ' ', $token ) );
foreach ( $row_assoc as $header => $row_value ) {
 $normalized_header = ct_normalize_csv_header( $header );
if ( $normalized_header === $normalized_token ) {
return (string) $row_value;
}
if ( '' !== $normalized_token_fallback && $normalized_header === $normalized_token_fallback ) {
return (string) $row_value;
}
}
return $matches[0];
},
(string) $value
);
}

function ct_get_programmatic_import_url( $args = array() ) {
$post_type = ct_get_programmatic_post_type();
if ( isset( $args['ct_post_type'] ) ) {
$candidate = sanitize_key( (string) $args['ct_post_type'] );
$post_types = ct_get_programmatic_post_types();
if ( isset( $post_types[ $candidate ] ) ) {
$post_type = $candidate;
}
unset( $args['ct_post_type'] );
}

$base_url = admin_url( 'edit.php?post_type=' . $post_type . '&page=ct_programmatic_import_' . $post_type );
if ( empty( $args ) ) {
return $base_url;
}

return add_query_arg( $args, $base_url );
}

function ct_render_programmatic_import_page() {
if ( ! current_user_can( 'manage_options' ) ) {
return;
}

$post_types = ct_get_programmatic_post_types();
$active_post_type = ct_get_programmatic_post_type();
if ( isset( $_GET['post_type'] ) ) {
$candidate = sanitize_key( wp_unslash( $_GET['post_type'] ) );
if ( isset( $post_types[ $candidate ] ) ) {
$active_post_type = $candidate;
}
}

$status = isset( $_GET['ct_import_status'] ) ? sanitize_text_field( wp_unslash( $_GET['ct_import_status'] ) ) : '';
$message = isset( $_GET['ct_import_message'] ) ? sanitize_text_field( wp_unslash( $_GET['ct_import_message'] ) ) : '';
$created = isset( $_GET['created'] ) ? absint( $_GET['created'] ) : 0;
$updated = isset( $_GET['updated'] ) ? absint( $_GET['updated'] ) : 0;
$skipped = isset( $_GET['skipped'] ) ? absint( $_GET['skipped'] ) : 0;
$errors = isset( $_GET['errors'] ) ? absint( $_GET['errors'] ) : 0;
?>
<div class="wrap">
<h1>Import Programmatic Pages CSV</h1>
<?php if ( 'success' === $status ) : ?>
<div class="notice notice-success is-dismissible"><p><?php echo esc_html( sprintf( 'Import complete. Created: %1$d, Updated: %2$d, Skipped: %3$d, Errors: %4$d', $created, $updated, $skipped, $errors ) ); ?></p></div>
<?php endif; ?>
<?php if ( 'error' === $status && ! empty( $message ) ) : ?>
<div class="notice notice-error is-dismissible"><p><?php echo esc_html( $message ); ?></p></div>
<?php endif; ?>

<p>Upload a CSV file with headers matching your field labels. Template tokens like {{City}} are supported in values.</p>
<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
<?php wp_nonce_field( 'ct_import_programmatic_csv', 'ct_import_programmatic_csv_nonce' ); ?>
<input type="hidden" name="action" value="ct_import_programmatic_csv" />
<input type="hidden" name="ct_post_type" value="<?php echo esc_attr( $active_post_type ); ?>" />
<input type="file" name="ct_csv_file" accept=".csv,text/csv" required />
<?php submit_button( 'Import CSV' ); ?>
</form>
</div>
<?php
}

function ct_find_programmatic_post_by_slug( $slug ) {
$existing = get_posts(
array(
'post_type' => ct_get_programmatic_post_type(),
'post_status' => 'any',
'numberposts' => 1,
'fields' => 'ids',
'meta_query' => array(
'relation' => 'OR',
array(
'key' => 'ct_page_url_slug',
'value' => $slug,
),
array(
'key' => 'ct_url_slug',
'value' => $slug,
),
),
)
);

if ( ! empty( $existing ) ) {
return (int) $existing[0];
}

$by_name = get_posts(
array(
'name' => $slug,
'post_type' => ct_get_programmatic_post_type(),
'post_status' => 'any',
'numberposts' => 1,
'fields' => 'ids',
)
);

if ( empty( $by_name ) ) {
return 0;
}

return (int) $by_name[0];
}

function ct_handle_programmatic_csv_import() {
if ( ! current_user_can( 'manage_options' ) ) {
wp_die( 'Unauthorized request.' );
}

$post_types = ct_get_programmatic_post_types();
$active_post_type = ct_get_programmatic_post_type();
if ( isset( $_POST['ct_post_type'] ) ) {
$candidate = sanitize_key( wp_unslash( $_POST['ct_post_type'] ) );
if ( isset( $post_types[ $candidate ] ) ) {
$active_post_type = $candidate;
}
}

$uploaded_file = '';

if ( ! isset( $_POST['ct_import_programmatic_csv_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ct_import_programmatic_csv_nonce'] ) ), 'ct_import_programmatic_csv' ) ) {
wp_safe_redirect( ct_get_programmatic_import_url( array(
'ct_post_type' => $active_post_type,
'ct_import_status' => 'error',
'ct_import_message' => 'Invalid import nonce.',
) ) );
exit;
}

if ( empty( $_FILES['ct_csv_file']['tmp_name'] ) || ! empty( $_FILES['ct_csv_file']['error'] ) ) {
wp_safe_redirect( ct_get_programmatic_import_url( array(
'ct_post_type' => $active_post_type,
'ct_import_status' => 'error',
'ct_import_message' => 'Please choose a valid CSV file.',
) ) );
exit;
}

require_once ABSPATH . 'wp-admin/includes/file.php';
$upload = wp_handle_upload(
$_FILES['ct_csv_file'],
array(
'test_form' => false,
'mimes' => array(
'csv' => 'text/csv',
'txt' => 'text/plain',
),
)
);

if ( isset( $upload['error'] ) ) {
wp_safe_redirect( ct_get_programmatic_import_url( array(
'ct_post_type' => $active_post_type,
'ct_import_status' => 'error',
'ct_import_message' => $upload['error'],
) ) );
exit;
}

$uploaded_file = $upload['file'];

$handle = fopen( $upload['file'], 'r' );
if ( false === $handle ) {
if ( ! empty( $uploaded_file ) && file_exists( $uploaded_file ) ) {
wp_delete_file( $uploaded_file );
}
wp_safe_redirect( ct_get_programmatic_import_url( array(
'ct_post_type' => $active_post_type,
'ct_import_status' => 'error',
'ct_import_message' => 'Unable to open uploaded CSV.',
) ) );
exit;
}

 $first_line = fgets( $handle );
if ( false === $first_line ) {
fclose( $handle );
if ( ! empty( $uploaded_file ) && file_exists( $uploaded_file ) ) {
wp_delete_file( $uploaded_file );
}
wp_safe_redirect( ct_get_programmatic_import_url( array(
'ct_post_type' => $active_post_type,
'ct_import_status' => 'error',
'ct_import_message' => 'CSV appears empty or invalid.',
) ) );
exit;
}

rewind( $handle );
$delimiter = ct_detect_csv_delimiter( $first_line );
$headers = fgetcsv( $handle, 0, $delimiter );
if ( empty( $headers ) ) {
fclose( $handle );
if ( ! empty( $uploaded_file ) && file_exists( $uploaded_file ) ) {
wp_delete_file( $uploaded_file );
}
wp_safe_redirect( ct_get_programmatic_import_url( array(
'ct_post_type' => $active_post_type,
'ct_import_status' => 'error',
'ct_import_message' => 'CSV appears empty or invalid.',
) ) );
exit;
}

$headers = array_map( 'trim', $headers );
$normalized_header_counts = array_count_values( array_map( 'ct_normalize_csv_header', $headers ) );
$duplicate_headers = array();
foreach ( $normalized_header_counts as $normalized_header => $count ) {
if ( $count > 1 && '' !== $normalized_header ) {
$duplicate_headers[] = $normalized_header;
}
}

if ( ! empty( $duplicate_headers ) ) {
fclose( $handle );
if ( ! empty( $uploaded_file ) && file_exists( $uploaded_file ) ) {
wp_delete_file( $uploaded_file );
}
wp_safe_redirect( ct_get_programmatic_import_url( array(
'ct_post_type' => $active_post_type,
'ct_import_status' => 'error',
'ct_import_message' => sprintf( 'CSV contains duplicate headers: %s. Please keep only one column for each header.', implode( ', ', $duplicate_headers ) ),
) ) );
exit;
}

$has_city = false;
$has_slug = false;
foreach ( $headers as $header ) {
$normalized = ct_normalize_csv_header( $header );
if ( 'city' === $normalized ) {
$has_city = true;
}
if ( 'page url slug' === $normalized || 'url slug' === $normalized ) {
$has_slug = true;
}
}

if ( ! $has_city || ! $has_slug ) {
fclose( $handle );
if ( ! empty( $uploaded_file ) && file_exists( $uploaded_file ) ) {
wp_delete_file( $uploaded_file );
}
wp_safe_redirect( ct_get_programmatic_import_url( array(
'ct_post_type' => $active_post_type,
'ct_import_status' => 'error',
'ct_import_message' => 'CSV must include City and Page URL Slug (or URL Slug) columns.',
) ) );
exit;
}

$fields = ct_get_programmatic_fields();
$textarea_keys = array_flip( ct_get_programmatic_textarea_keys() );
$created = 0;
$updated = 0;
$skipped = 0;
$errors = 0;

while ( ( $row = fgetcsv( $handle, 0, $delimiter ) ) !== false ) {
if ( count( $row ) === 1 && false !== strpos( $row[0], $delimiter ) ) {
$row = str_getcsv( $row[0], $delimiter );
}

if ( count( $row ) < count( $headers ) ) {
$row = array_pad( $row, count( $headers ), '' );
}

$row_assoc = array();
$row_has_data = false;
foreach ( $headers as $index => $header ) {
$value = isset( $row[ $index ] ) ? trim( (string) $row[ $index ] ) : '';
$row_assoc[ $header ] = $value;
if ( '' !== $value ) {
$row_has_data = true;
}
}

if ( ! $row_has_data ) {
$skipped++;
continue;
}

$city_raw = ct_get_csv_row_value( $row_assoc, array( 'City' ) );
$slug_raw = ct_get_csv_row_value( $row_assoc, array( 'Page URL Slug', 'URL Slug' ) );

$city = sanitize_text_field( ct_replace_template_variables( $city_raw, $row_assoc ) );
$slug_source = ct_replace_template_variables( $slug_raw, $row_assoc );
if ( preg_match( '/^\{\{\s*\/?\s*city\s*\/?\s*\}\}$/i', trim( (string) $slug_raw ) ) ) {
$slug_source = $city;
}
$slug = sanitize_title( $slug_source );

if ( '' === $slug ) {
$errors++;
continue;
}

$title_raw = ct_get_csv_row_value( $row_assoc, array( 'Hero H1', 'H1', 'Meta Title', 'City' ) );
$title = sanitize_text_field( ct_replace_template_variables( $title_raw, $row_assoc ) );
if ( '' === $title ) {
$title = 'Programmatic Page ' . $slug;
}

$existing_id = ct_find_programmatic_post_by_slug( $slug );
$post_args = array(
'post_title' => $title,
'post_name' => $slug,
'post_type' => $active_post_type,
'post_status' => 'publish',
);

if ( $existing_id > 0 ) {
$post_args['ID'] = $existing_id;
$post_id = wp_update_post( $post_args, true );
if ( is_wp_error( $post_id ) ) {
$errors++;
continue;
}
$updated++;
} else {
$post_id = wp_insert_post( $post_args, true );
if ( is_wp_error( $post_id ) ) {
$errors++;
continue;
}
$created++;
}

foreach ( $fields as $field ) {
$raw_value = ct_get_csv_row_value( $row_assoc, array( $field['label'] ) );
$templated = ct_replace_template_variables( $raw_value, $row_assoc );
$sanitized = isset( $textarea_keys[ $field['key'] ] )
? sanitize_textarea_field( wp_unslash( $templated ) )
: ct_sanitize_programmatic_field( $templated, $field['type'] );
update_post_meta( $post_id, $field['key'], $sanitized );
}

ct_sync_yoast_meta_from_programmatic( $post_id );

if ( '' !== $city ) {
update_post_meta( $post_id, 'ct_city', $city );
}
update_post_meta( $post_id, 'ct_page_url_slug', $slug );
}

fclose( $handle );
if ( ! empty( $uploaded_file ) && file_exists( $uploaded_file ) ) {
wp_delete_file( $uploaded_file );
}
wp_safe_redirect( ct_get_programmatic_import_url( array(
'ct_post_type' => $active_post_type,
'ct_import_status' => 'success',
'created' => $created,
'updated' => $updated,
'skipped' => $skipped,
'errors' => $errors,
) ) );
exit;
}
add_action( 'admin_post_ct_import_programmatic_csv', 'ct_handle_programmatic_csv_import' );

function ct_build_programmatic_page_content( $post_id ) {
$sections = array();

$hero_intro = trim( (string) get_post_meta( $post_id, 'ct_hero_intro_paragraph', true ) );
if ( '' !== $hero_intro ) {
$sections[] = wpautop( esc_html( $hero_intro ) );
}

$why_section_h2 = trim( (string) get_post_meta( $post_id, 'ct_why_section_h2', true ) );
$why_items = array();
for ( $index = 1; $index <= 5; $index++ ) {
$title = trim( (string) get_post_meta( $post_id, 'ct_why_point_' . $index . '_title', true ) );
$description = trim( (string) get_post_meta( $post_id, 'ct_why_point_' . $index . '_description', true ) );
if ( '' === $title && '' === $description ) {
continue;
}

$item_html = '<li>';
if ( '' !== $title ) {
$item_html .= '<strong>' . esc_html( $title ) . '</strong>';
}
if ( '' !== $description ) {
$item_html .= '<p>' . esc_html( $description ) . '</p>';
}
$item_html .= '</li>';
$why_items[] = $item_html;
}

if ( ! empty( $why_items ) ) {
$section_html = '';
if ( '' !== $why_section_h2 ) {
$section_html .= '<h2>' . esc_html( $why_section_h2 ) . '</h2>';
}
$section_html .= '<ul>' . implode( '', $why_items ) . '</ul>';
$sections[] = $section_html;
}

$services_section_h2 = trim( (string) get_post_meta( $post_id, 'ct_services_section_h2', true ) );
$services_html = '';
for ( $index = 1; $index <= 2; $index++ ) {
$name = trim( (string) get_post_meta( $post_id, 'ct_service_' . $index . '_name', true ) );
$description = trim( (string) get_post_meta( $post_id, 'ct_service_' . $index . '_description', true ) );
if ( '' === $name && '' === $description ) {
continue;
}

if ( '' !== $name ) {
$services_html .= '<h3>' . esc_html( $name ) . '</h3>';
}
if ( '' !== $description ) {
$services_html .= wpautop( esc_html( $description ) );
}
}

if ( '' !== $services_html ) {
if ( '' !== $services_section_h2 ) {
$services_html = '<h2>' . esc_html( $services_section_h2 ) . '</h2>' . $services_html;
}
$sections[] = $services_html;
}

$faq_section_h2 = trim( (string) get_post_meta( $post_id, 'ct_faq_section_h2', true ) );
$faq_html = '';
for ( $index = 1; $index <= 7; $index++ ) {
$question = trim( (string) get_post_meta( $post_id, 'ct_faq_' . $index . '_question', true ) );
$answer = trim( (string) get_post_meta( $post_id, 'ct_faq_' . $index . '_answer', true ) );
if ( '' === $question && '' === $answer ) {
continue;
}

if ( '' !== $question ) {
$faq_html .= '<h3>' . esc_html( $question ) . '</h3>';
}
if ( '' !== $answer ) {
$faq_html .= wpautop( esc_html( $answer ) );
}
}

if ( '' !== $faq_html ) {
if ( '' !== $faq_section_h2 ) {
$faq_html = '<h2>' . esc_html( $faq_section_h2 ) . '</h2>' . $faq_html;
}
$sections[] = $faq_html;
}

$bottom_cta_heading = trim( (string) get_post_meta( $post_id, 'ct_bottom_cta_heading', true ) );
$bottom_cta_text = trim( (string) get_post_meta( $post_id, 'ct_bottom_cta_text', true ) );
if ( '' !== $bottom_cta_heading || '' !== $bottom_cta_text ) {
$cta_html = '';
if ( '' !== $bottom_cta_heading ) {
$cta_html .= '<h2>' . esc_html( $bottom_cta_heading ) . '</h2>';
}
if ( '' !== $bottom_cta_text ) {
$cta_html .= wpautop( esc_html( $bottom_cta_text ) );
}
$sections[] = $cta_html;
}

return implode( "\n", $sections );
}

function ct_programmatic_page_content_fallback( $content ) {
if ( ! in_the_loop() || ! is_main_query() ) {
return $content;
}

$post_types = array_keys( ct_get_programmatic_post_types() );
if ( empty( $post_types ) || ! is_singular( $post_types ) ) {
return $content;
}

$post_id = get_the_ID();
if ( ! $post_id ) {
return $content;
}

$stored_content = (string) get_post_field( 'post_content', $post_id );
if ( '' !== trim( $stored_content ) ) {
return $content;
}

$generated_content = ct_build_programmatic_page_content( $post_id );
if ( '' === trim( $generated_content ) ) {
return $content;
}

return $generated_content;
}
add_filter( 'the_content', 'ct_programmatic_page_content_fallback' );

function ct_programmatic_page_template_include( $template ) {
$post_types = array_keys( ct_get_programmatic_post_types() );
if ( empty( $post_types ) || ! is_singular( $post_types ) ) {
return $template;
}

$custom_template = plugin_dir_path( __FILE__ ) . 'templates/single-programmatic_page.php';
if ( file_exists( $custom_template ) ) {
return $custom_template;
}

return $template;
}
add_filter( 'template_include', 'ct_programmatic_page_template_include' );
