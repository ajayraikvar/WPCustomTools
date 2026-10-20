'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');

const widget = fs.readFileSync('includes/class-wpct-elementor-product-card.php', 'utf8');
const module = fs.readFileSync('includes/class-wpct-elementor-widget.php', 'utf8');
const css = fs.readFileSync('assets/css/elementor-product-card.css', 'utf8');
const bootstrap = fs.readFileSync('includes/class-wpct-plugin.php', 'utf8');
const admin = fs.readFileSync('includes/class-wpct-admin-menu.php', 'utf8');

assert.match(module, /elementor\/widgets\/register/, 'Widget module should register through Elementor hook.');
assert.match(module, /class_exists\(.*Elementor.*Widget_Base/s, 'Elementor must remain an optional dependency.');
assert.match(bootstrap, /Elementor_Widget_Module::instance\(\)->init\(\)/, 'Plugin bootstrap should initialize the widget module.');
assert.match(widget, /get_name\(\)[\s\S]*?wpct-product-card/, 'Widget slug should be stable.');
assert.match(widget, /'media_type'/, 'Widget should expose image/video selector.');
assert.match(widget, /'video_url'/, 'Widget should expose video URL setting.');
assert.match(widget, /'product_title'/, 'Widget should expose title field.');
assert.match(widget, /'current_price'/, 'Widget should expose price field.');
assert.match(widget, /'button_link'/, 'Widget should expose CTA URL.');
assert.match(widget, /TAB_STYLE/, 'Widget should provide Elementor style controls.');
assert.match(widget, /esc_html\( \$settings\['product_title'\] \)/, 'Editable text should be escaped at output.');
assert.match(widget, /esc_url\( \$settings\['video_url'\]\['url'\] \)/, 'Video URL should be escaped.');
assert.match(css, /\.wpct-product-card__media video/, 'Styles should cover video media.');
assert.match(css, /\.wpct-product-card__button/, 'Styles should cover the CTA button.');
assert.match(admin, /WP Custom Product Card/, 'Admin instructions should mention widget name.');
assert.match(admin, /Requires Elementor to be installed and active/, 'Admin should explain optional Elementor requirement.');

console.log('Elementor Product Card widget contract checks passed.');
