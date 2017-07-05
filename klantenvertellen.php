<?php

/*
Plugin Name: Klantenvertellen
Description: Klantenvertellen integratie voor Wordpress
Version:     0.0.1
Author:      Anne Douwe Bouma
License:     GPL2
 */

require(__DIR__.'/settings.php');

defined('ABSPATH') or die('No direct script access allowed.');

/**
 * Class Klantenvertellen
 * @author Anne Douwe Bouma
 */
class Klantenvertellen
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		add_action('wp_enqueue_scripts', [&$this, 'klantenvertellen_assets']);
		add_shortcode('KlantenVertellen', [&$this, 'klantenvertellen_shortcode']);
		add_shortcode('KlantenVertellenRating', [&$this, 'klantenvertellen_rating_shortcode']);
	}

	public function klantenvertellen_shortcode()
	{
		if (get_option('klantenvertellen_xml_url') == '') {
			echo('Geen XML url ingevuld in de instellingen.');

			return;
		}

		// Get data from klantenvertellen
		$data = $this->getFromUrl(get_option('klantenvertellen_xml_url'));

		$reviews = $data['beoordelingen']['beoordeling'];

		$currentPage = isset($_GET['pag']) ? $_GET['pag'] : 1;
		$perPage = get_option('klantenvertellen_perpage', 10);

		$reviews = array_splice($reviews, (($currentPage - 1) * $perPage), $perPage);

		$reviews = array_map(function($review) {
			foreach ($review as &$value) {
				if (is_array($value)) {
					$value = null;
				}
			}
			
			if (!empty((string) $review['woonplaats'])) {
				$review['woonplaats'] = ' uit '.$review['woonplaats'];
			}

			if ((string) $review['voornaam'] === 'Dhr./mevr.') {
				$review['voornaam'] = 'Anoniem';
				$review['achternaam'] = '';
			}

			if (!empty((string) $review['beschrijving'])) {
				$review['beschrijving'] = "Ervaring: ".$review['beschrijving'];
			}

			$review['aanbeveling'] = ucwords($review['aanbeveling']);

			$stars = str_replace(',', '.', $review['gemiddelde']) / 2;

			$review['starHtml'] = '';

			for($x = 0; $x < 5; $x++) {
				if(floor($stars) - $x >= 1) {
					$review['starHtml'] .= '<i class="fa fa-star"></i>'; 
				} elseif($starNumber - $x > 0) { 
					$review['starHtml'] .= '<i class="fa fa-star-half-o"></i>'; 
				} else {
					$review['starHtml'] .= '<i class="fa fa-star-o"></i>';
				}
			}

			return $review;
		}, $reviews);

		$reviewCount = count($data['beoordelingen']['beoordeling']);
		$maxPage = ceil($reviewCount / $perPage);
		$previousPage = ($currentPage <= 1) ? 1 : ($currentPage - 1);
		$nextPage = ($currentPage >= $maxPage) ? $maxPage : ($currentPage + 1);
		$paginationOffset = get_option('klantenvertellen_paginationoffset', 5);

		require plugin_dir_path(__FILE__)."klantenvertellen_list.php";
	}

	/**
	 * Add rating to each page
	 *
	 * @return content
	 */
	public function klantenvertellen_rating_shortcode()
	{
		if (get_option('klantenvertellen_xml_url') == '') {
			echo('Geen XML url ingevuld in de instellingen.');

			return;
		}

		$data = $this->getFromUrl(get_option('klantenvertellen_xml_url'));

		$ratingSnippet = <<<EOD
<div class="rating">
	<span itemscope itemtype="http://schema.org/WebPage">
		<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
			Beoordeling door klanten:
			<span itemprop="ratingValue">{$data['totaal']['gemiddelde']}</span>/<span itemprop="bestRating">10</span>
			<span> van de <span itemprop="ratingCount">{$data['statistieken']['aantalingevuld']}</span> beoordelingen</span>
		</span>
	</span>
</div>
EOD;

		return $ratingSnippet;
	}
	

	public function klantenvertellen_assets() 
	{
		wp_enqueue_style('klantenvertellen', plugin_dir_url(__FILE__)."css/klantenvertellen.css");
		wp_enqueue_style('font-awesome', "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
	}

	/**
	 * Get data from url
	 *
	 * @param string $url
	 * @return string
	 */
	private function getFromUrl($url)
	{
		$xml = file_get_contents($url);

		$string = preg_replace_callback('/<!\[CDATA\[(.*)\]\]>/', function($matches) {
			$converted = htmlspecialchars($matches[1]);
			$trimmed = trim($converted);
			return $trimmed;
		}, $xml);

		return json_decode(json_encode(simplexml_load_string($string)), true);
	}
}

if (is_admin()) {
	new KlantenVertellenOptions();
}

new KlantenVertellen();
