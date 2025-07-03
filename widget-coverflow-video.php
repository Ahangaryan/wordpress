<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class JetEngine_Coverflow_Video extends Widget_Base {

	public function get_name() {
		return 'jetengine_coverflow_video';
	}

	public function get_title() {
		return 'JetEngine Coverflow Video';
	}

	public function get_icon() {
		return 'eicon-play';
	}

	public function get_categories() {
		return ['general'];
	}

	public function get_script_depends() {
		return ['swiper-js'];
	}

	public function get_style_depends() {
		return ['swiper-css'];
	}

	protected function register_controls() {
		$this->start_controls_section('content_section', [
			'label' => 'Video Settings',
			'tab' => Controls_Manager::TAB_CONTENT,
		]);

		$this->add_control('meta_key', [
			'label' => 'Repeater Key Name',
			'type' => Controls_Manager::TEXT,
			'placeholder' => 'For Example: Videos',
		]);

		$this->add_control('video_subfield', [
			'label' => 'Key In Repeater',
			'type' => Controls_Manager::TEXT,
			'placeholder' => 'For Example: Video_URL',
		]);

		$this->end_controls_section();
	}

protected function render() {
	$meta_key = $this->get_settings('meta_key');
	$video_field = $this->get_settings('video_subfield');
	$post_id = get_the_ID();

	$repeater_data = get_post_meta($post_id, $meta_key, true);

	if (!is_array($repeater_data) || empty($repeater_data)) {
		echo '<p>Video Not Found.</p>';
		return;
	}

	$uniq = 'swiper_' . uniqid();

	wp_enqueue_style('swiper-css');
	wp_enqueue_script('swiper-js');

	echo '<div class="swiper-container-wrapper" style="position: relative;">';

	echo '<div class="swiper ' . esc_attr($uniq) . '">';
	echo '<div class="swiper-wrapper">';

	foreach ($repeater_data as $item) {
		$url = trim($item[$video_field] ?? '');
		if (!$url) continue;

		echo '<div class="swiper-slide" style="width: 100%; max-width: 600px; text-align: center;">';

		if (preg_match('/(youtube\.com|youtu\.be)/', $url)) {
			preg_match('/(?:v=|\/)([0-9A-Za-z_-]{11})/', $url, $matches);
			$video_id = $matches[1] ?? '';
			if ($video_id) {
				$embed_url = 'https://www.youtube.com/embed/' . $video_id;
				echo '<iframe width="100%" height="340" src="' . esc_url($embed_url) . '" frameborder="0" allowfullscreen></iframe>';
			}
		} elseif (strpos($url, 'vimeo.com') !== false) {
			preg_match('/vimeo\.com\/(\d+)/', $url, $matches);
			$video_id = $matches[1] ?? '';
			if ($video_id) {
				$embed_url = 'https://player.vimeo.com/video/' . $video_id;
				echo '<iframe width="100%" height="340" src="' . esc_url($embed_url) . '" frameborder="0" allowfullscreen></iframe>';
			}
		} elseif (strpos($url, 'aparat.com') !== false) {
			$embed_url = strpos($url, 'embed') !== false ? $url : 'https://www.aparat.com/video/video/embed/videohash/' . basename($url);
			echo '<iframe width="100%" height="340" src="' . esc_url($embed_url) . '" frameborder="0" allowfullscreen></iframe>';
		} elseif (preg_match('/\.(mp4|webm|ogg)$/i', $url)) {
			echo '<video controls preload="none" width="100%" height="340">';
			echo '<source src="' . esc_url($url) . '" type="video/mp4">';
			echo '</video>';
		} else {
			echo '<p>Not Supported Format: ' . esc_html($url) . '</p>';
		}

		echo '</div>';
	}

	echo '</div>'; // swiper-wrapper

	// navigation buttons
	echo '<div class="swiper-button-prev" style="color: #fafafa;"></div>';
	echo '<div class="swiper-button-next" style="color: #fafafa;"></div>';

	echo '</div>'; // swiper
	echo '</div>'; // wrapper

	// Swiper initialization
	echo "<script>
	document.addEventListener('DOMContentLoaded', function () {
		new Swiper('." . esc_js($uniq) . "', {
			effect: 'coverflow',
			grabCursor: true,
			centeredSlides: true,
			slidesPerView: 'auto',
			loop: true,
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			coverflowEffect: {
				rotate: 50,
				stretch: 0,
				depth: 150,
				modifier: 1,
				slideShadows: true,
			},
			breakpoints: {
				320: {
					slidesPerView: 1,
					spaceBetween: 10
				},
				768: {
					slidesPerView: 'auto'
				}
			}
		});
	});
	</script>";
}


}
