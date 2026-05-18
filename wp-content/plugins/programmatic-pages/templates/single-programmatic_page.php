<?php
if ( ! defined( 'ABSPATH' ) ) {
exit;
}

get_header();

if ( ! function_exists( 'ct_programmatic_get_meta_text' ) ) {
function ct_programmatic_get_meta_text( $post_id, $key ) {
return trim( (string) get_post_meta( $post_id, $key, true ) );
}
}

if ( ! function_exists( 'ct_programmatic_render_text_block' ) ) {
function ct_programmatic_render_text_block( $text ) {
if ( '' === trim( (string) $text ) ) {
return;
}

echo wp_kses_post( wpautop( esc_html( (string) $text ) ) );
}
}
?>

<main id="primary" class="ct-programmatic-template">
<?php while ( have_posts() ) : the_post(); ?>
<?php
$post_id = get_the_ID();

$city = ct_programmatic_get_meta_text( $post_id, 'ct_city' );
$hero_h1 = ct_programmatic_get_meta_text( $post_id, 'ct_hero_h1' );
if ( '' === $hero_h1 ) {
$hero_h1 = get_the_title( $post_id );
}
$hero_intro = ct_programmatic_get_meta_text( $post_id, 'ct_hero_intro_paragraph' );

$why_section_h2 = ct_programmatic_get_meta_text( $post_id, 'ct_why_section_h2' );
$services_section_h2 = ct_programmatic_get_meta_text( $post_id, 'ct_services_section_h2' );
$process_section_h2 = ct_programmatic_get_meta_text( $post_id, 'ct_process_section_h2' );
$process_section_sub_text = ct_programmatic_get_meta_text( $post_id, 'ct_process_section_sub_text' );
$cost_section_h2 = ct_programmatic_get_meta_text( $post_id, 'ct_cost_section_h2' );
$cost_intro_text = ct_programmatic_get_meta_text( $post_id, 'ct_cost_intro_text' );
$locations_section_h2 = ct_programmatic_get_meta_text( $post_id, 'ct_locations_section_h2' );
$service_area_paragraph = ct_programmatic_get_meta_text( $post_id, 'ct_service_area_paragraph' );
$bottom_cta_heading = ct_programmatic_get_meta_text( $post_id, 'ct_bottom_cta_heading' );
$bottom_cta_text = ct_programmatic_get_meta_text( $post_id, 'ct_bottom_cta_text' );
$faq_section_h2 = ct_programmatic_get_meta_text( $post_id, 'ct_faq_section_h2' );

$why_points = array();
for ( $index = 1; $index <= 5; $index++ ) {
$title = ct_programmatic_get_meta_text( $post_id, 'ct_why_point_' . $index . '_title' );
$description = ct_programmatic_get_meta_text( $post_id, 'ct_why_point_' . $index . '_description' );
if ( '' === $title && '' === $description ) {
continue;
}

$why_points[] = array(
'title' => $title,
'description' => $description,
);
}

$services = array();
for ( $index = 1; $index <= 2; $index++ ) {
$name = ct_programmatic_get_meta_text( $post_id, 'ct_service_' . $index . '_name' );
$description = ct_programmatic_get_meta_text( $post_id, 'ct_service_' . $index . '_description' );
if ( '' === $name && '' === $description ) {
continue;
}

$services[] = array(
'name' => $name,
'description' => $description,
);
}

$process_steps = array();
for ( $index = 1; $index <= 4; $index++ ) {
$title = ct_programmatic_get_meta_text( $post_id, 'ct_step_' . $index . '_title' );
$description = ct_programmatic_get_meta_text( $post_id, 'ct_step_' . $index . '_description' );
if ( '' === $title && '' === $description ) {
continue;
}

$process_steps[] = array(
'number' => $index,
'title' => $title,
'description' => $description,
);
}

$nearby_areas = array();
for ( $index = 1; $index <= 4; $index++ ) {
$area = ct_programmatic_get_meta_text( $post_id, 'ct_nearby_area_' . $index );
if ( '' === $area ) {
continue;
}
$nearby_areas[] = $area;
}

$faqs = array();
for ( $index = 1; $index <= 7; $index++ ) {
$question = ct_programmatic_get_meta_text( $post_id, 'ct_faq_' . $index . '_question' );
$answer = ct_programmatic_get_meta_text( $post_id, 'ct_faq_' . $index . '_answer' );
if ( '' === $question && '' === $answer ) {
continue;
}

$faqs[] = array(
'question' => $question,
'answer' => $answer,
);
}
?>
<style>
.ct-programmatic-template {
--ct-bg: #ffffff;
--ct-panel: #ffffff;
--ct-band: #0d3f97;
--ct-band-alt: #0a337d;
--ct-text: #16345f;
--ct-muted: #4f6484;
--ct-line: #d6e1ee;
--ct-highlight: #e9539a;
background: var(--ct-bg);
color: var(--ct-text);
padding: 24px 0 72px;
}
.ct-programmatic-wrap {
max-width: 1080px;
margin: 0 auto;
padding: 0 20px;
}
.ct-breadcrumb {
font-size: 12px;
color: #7e91ae;
margin-bottom: 16px;
}
.ct-row {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 24px;
align-items: center;
margin-top: 24px;
}
.ct-row-full {
grid-template-columns: 1fr;
}
.ct-hero-row {
grid-template-columns: 1fr;
}
.ct-row .ct-copy,
.ct-row .ct-media {
background: var(--ct-panel);
border: 1px solid var(--ct-line);
border-radius: 18px;
box-shadow: 0 10px 26px rgba(16, 46, 86, 0.08);
}
.ct-row .ct-copy {
padding: 30px;
}
.ct-media {
overflow: hidden;
min-height: 260px;
}
.ct-media img {
width: 100%;
height: 100%;
min-height: 260px;
object-fit: cover;
display: block;
}
.ct-photo-fallback {
height: 100%;
min-height: 260px;
background: linear-gradient(135deg, #dbe8f8 0%, #afc7ea 38%, #8eb0df 100%);
position: relative;
}
.ct-photo-fallback::before {
content: '';
position: absolute;
inset: 14px;
border-radius: 14px;
border: 1px solid rgba(255, 255, 255, 0.8);
}
.ct-photo-fallback span {
position: absolute;
left: 24px;
bottom: 20px;
font-weight: 700;
font-size: 14px;
color: #08326b;
letter-spacing: 0.03em;
}
.ct-row.is-reverse .ct-copy {
order: 2;
}
.ct-row.is-reverse .ct-media {
order: 1;
}
.ct-programmatic-template h1,
.ct-programmatic-template h2,
.ct-programmatic-template h3 {
font-family: "Trebuchet MS", "Segoe UI", sans-serif;
color: #103869;
margin: 0 0 12px;
}
.ct-programmatic-template h1 {
font-size: clamp(2rem, 4.1vw, 2.95rem);
line-height: 1.15;
margin-bottom: 10px;
}
.ct-programmatic-template h2 {
font-size: clamp(1.55rem, 2.8vw, 2.1rem);
}
.ct-programmatic-template h3 {
font-size: 1.1rem;
margin-bottom: 8px;
}
.ct-programmatic-template p {
color: var(--ct-muted);
font-size: 15px;
line-height: 1.7;
margin-top: 0;
}
.ct-city-pill {
display: inline-block;
font-size: 11px;
letter-spacing: 0.09em;
text-transform: uppercase;
padding: 6px 11px;
border-radius: 999px;
border: 1px solid var(--ct-line);
background: #f6f9ff;
color: #6f83a3;
margin-bottom: 14px;
}
.ct-inline-actions {
display: flex;
gap: 10px;
margin-top: 14px;
flex-wrap: wrap;
}
.ct-btn {
display: inline-flex;
align-items: center;
justify-content: center;
padding: 10px 14px;
border-radius: 8px;
font-size: 13px;
font-weight: 700;
text-decoration: none;
border: 1px solid transparent;
}
.ct-btn-primary {
background: var(--ct-highlight);
color: #fff;
}
.ct-btn-secondary {
background: #2f79e8;
color: #fff;
}
.ct-point-list {
display: grid;
gap: 12px;
padding: 0;
margin: 0;
list-style: none;
counter-reset: ctpoint;
}
.ct-point-list li {
border: 1px solid var(--ct-line);
border-radius: 12px;
padding: 14px;
background: #fcfdff;
}
.ct-point-list li::before {
counter-increment: ctpoint;
content: "0" counter(ctpoint);
font-weight: 700;
font-size: 12px;
letter-spacing: 0.06em;
color: #7b90ae;
display: block;
margin-bottom: 6px;
}
.ct-process-grid {
display: grid;
grid-template-columns: repeat(2, minmax(0, 1fr));
gap: 12px;
}
.ct-process-step {
border: 1px solid var(--ct-line);
border-radius: 12px;
padding: 14px;
background: #fcfdff;
}
.ct-process-chip {
display: inline-flex;
align-items: center;
justify-content: center;
width: 26px;
height: 26px;
border-radius: 50%;
background: var(--ct-band);
color: #fff;
font-size: 12px;
font-weight: 700;
margin-bottom: 8px;
}
.ct-locations-list {
display: flex;
flex-wrap: wrap;
gap: 10px;
padding: 0;
margin: 0 0 14px;
list-style: none;
}
.ct-locations-list li {
padding: 6px 11px;
border-radius: 999px;
border: 1px solid var(--ct-line);
background: #fff;
color: #4f6484;
font-size: 13px;
}
.ct-band {
margin-top: 38px;
background: linear-gradient(120deg, var(--ct-band) 0%, var(--ct-band-alt) 100%);
color: #eaf2ff;
padding: 58px 20px;
text-align: center;
}
.ct-band .ct-band-inner {
max-width: 760px;
margin: 0 auto;
}
.ct-band h2,
.ct-band p {
color: #eaf2ff;
}
.ct-band p {
opacity: 0.95;
}
.ct-faq-shell {
background: #f3f7fc;
padding: 54px 0;
margin-top: 0;
}
.ct-faq-wrap {
max-width: 1080px;
margin: 0 auto;
padding: 0 20px;
}
.ct-faq-panel {
background: #fff;
border: 1px solid var(--ct-line);
border-radius: 16px;
padding: 24px;
}
.ct-faq-item {
border-top: 1px solid var(--ct-line);
}
.ct-faq-item:first-child {
border-top: none;
}
.ct-faq-button {
width: 100%;
border: none;
background: transparent;
padding: 15px 0;
display: flex;
align-items: center;
justify-content: space-between;
text-align: left;
cursor: pointer;
font-size: 15px;
font-weight: 600;
color: #1a3f70;
}
.ct-faq-button::after {
content: "+";
font-size: 1.2rem;
line-height: 1;
color: #6a83a8;
}
.ct-faq-item.is-open .ct-faq-button::after {
content: "-";
}
.ct-faq-answer {
display: none;
padding: 0 0 15px;
}
.ct-faq-item.is-open .ct-faq-answer {
display: block;
}
@media (max-width: 900px) {
.ct-row {
grid-template-columns: 1fr;
}
.ct-row .ct-copy,
.ct-row .ct-media,
.ct-row.is-reverse .ct-copy,
.ct-row.is-reverse .ct-media {
order: initial;
}
.ct-process-grid {
grid-template-columns: 1fr;
}
}
@media (max-width: 680px) {
.ct-programmatic-wrap,
.ct-faq-wrap {
padding: 0 14px;
}
.ct-row .ct-copy {
padding: 20px;
}
}
</style>

<div class="ct-programmatic-wrap">
<div class="ct-breadcrumb">Home / Programmatic Page<?php if ( '' !== $city ) : ?> / <?php echo esc_html( $city ); ?><?php endif; ?></div>

<section class="ct-row ct-hero-row">
<div class="ct-copy">
<?php if ( '' !== $city ) : ?>
<div class="ct-city-pill"><?php echo esc_html( $city ); ?></div>
<?php endif; ?>
<h1><?php echo esc_html( $hero_h1 ); ?></h1>
<?php ct_programmatic_render_text_block( $hero_intro ); ?>
</div>
</section>

<?php if ( ! empty( $why_points ) ) : ?>
<section class="ct-row ct-row-full">
<div class="ct-copy">
<?php if ( '' !== $why_section_h2 ) : ?><h2><?php echo esc_html( $why_section_h2 ); ?></h2><?php endif; ?>
<ul class="ct-point-list">
<?php foreach ( $why_points as $point ) : ?>
<li>
<?php if ( '' !== $point['title'] ) : ?><h3><?php echo esc_html( $point['title'] ); ?></h3><?php endif; ?>
<?php ct_programmatic_render_text_block( $point['description'] ); ?>
</li>
<?php endforeach; ?>
</ul>
</div>
</section>
<?php endif; ?>

<?php if ( ! empty( $services ) ) : ?>
<section class="ct-row ct-row-full">
<div class="ct-copy">
<?php if ( '' !== $services_section_h2 ) : ?><h2><?php echo esc_html( $services_section_h2 ); ?></h2><?php endif; ?>
<?php foreach ( $services as $service ) : ?>
<article>
<?php if ( '' !== $service['name'] ) : ?><h3><?php echo esc_html( $service['name'] ); ?></h3><?php endif; ?>
<?php ct_programmatic_render_text_block( $service['description'] ); ?>
</article>
<?php endforeach; ?>
</div>
</section>
<?php endif; ?>

<?php if ( ! empty( $process_steps ) ) : ?>
<section class="ct-row ct-row-full">
<div class="ct-copy">
<?php if ( '' !== $process_section_h2 ) : ?><h2><?php echo esc_html( $process_section_h2 ); ?></h2><?php endif; ?>
<?php ct_programmatic_render_text_block( $process_section_sub_text ); ?>
<div class="ct-process-grid">
<?php foreach ( $process_steps as $step ) : ?>
<article class="ct-process-step">
<span class="ct-process-chip"><?php echo esc_html( (string) $step['number'] ); ?></span>
<?php if ( '' !== $step['title'] ) : ?><h3><?php echo esc_html( $step['title'] ); ?></h3><?php endif; ?>
<?php ct_programmatic_render_text_block( $step['description'] ); ?>
</article>
<?php endforeach; ?>
</div>
</div>
</section>
<?php endif; ?>

<?php if ( '' !== $cost_intro_text || '' !== $cost_section_h2 || '' !== $locations_section_h2 || ! empty( $nearby_areas ) || '' !== $service_area_paragraph ) : ?>
<section class="ct-row ct-row-full">
<div class="ct-copy">
<?php if ( '' !== $cost_section_h2 ) : ?><h2><?php echo esc_html( $cost_section_h2 ); ?></h2><?php endif; ?>
<?php ct_programmatic_render_text_block( $cost_intro_text ); ?>

<?php if ( '' !== $locations_section_h2 ) : ?><h2><?php echo esc_html( $locations_section_h2 ); ?></h2><?php endif; ?>
<?php if ( ! empty( $nearby_areas ) ) : ?>
<ul class="ct-locations-list">
<?php foreach ( $nearby_areas as $area ) : ?>
<li><?php echo esc_html( $area ); ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<?php ct_programmatic_render_text_block( $service_area_paragraph ); ?>
</div>
</section>
<?php endif; ?>
</div>

<?php if ( '' !== $bottom_cta_heading || '' !== $bottom_cta_text ) : ?>
<section id="ct-bottom-cta" class="ct-band">
<div class="ct-band-inner">
<?php if ( '' !== $bottom_cta_heading ) : ?><h2><?php echo esc_html( $bottom_cta_heading ); ?></h2><?php endif; ?>
<?php ct_programmatic_render_text_block( $bottom_cta_text ); ?>
</div>
</section>
<?php endif; ?>

<?php if ( ! empty( $faqs ) ) : ?>
<section id="ct-faq" class="ct-faq-shell">
<div class="ct-faq-wrap">
<div class="ct-faq-panel">
<div class="ct-faq" data-ct-faq>
<?php foreach ( $faqs as $faq ) : ?>
<article class="ct-faq-item">
<button type="button" class="ct-faq-button">
<span><?php echo esc_html( $faq['question'] ); ?></span>
</button>
<div class="ct-faq-answer">
<?php ct_programmatic_render_text_block( $faq['answer'] ); ?>
</div>
</article>
<?php endforeach; ?>
</div>
</div>
</section>
<?php endif; ?>
<script>
(function () {
var groups = document.querySelectorAll('[data-ct-faq]');
for (var i = 0; i < groups.length; i++) {
var buttons = groups[i].querySelectorAll('.ct-faq-button');
for (var j = 0; j < buttons.length; j++) {
buttons[j].addEventListener('click', function () {
var item = this.parentElement;
if (!item) {
return;
}
item.classList.toggle('is-open');
});
}
}
})();
</script>
<?php endwhile; ?>
</main>

<?php
get_footer();
