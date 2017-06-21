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

	public function klantenvertellen_shortcode($atts = [], $content = null, $tag = '')
	{
		if (get_option('klantenvertellen_xml_url') == '') {
			die('Geen XML url ingevuld in de instellingen.');
		}

		// Get data from klantenvertellen
		$data = $this->getFromUrl(get_option('klantenvertellen_xml_url'));

		$output = '';

		$output .= <<<EOD
<div class="summary">
	<p>Hoi</p>
</div>
EOD;

		foreach ($data->beoordelingen->beoordeling as $review) {
			if (!empty((string) $review->woonplaats)) {
				$review->woonplaats = ' uit '.$review->woonplaats;
			}

			if ((string) $review->voornaam === 'Dhr./mevr.') {
				$review->voornaam = 'Anoniem';
				$review->achternaam = '';
			}

			if (!empty((string) $review->beschrijving)) {
				$review->beschrijving = "Ervaring: ".$review->beschrijving;
			}

			$review->aanbeveling = ucwords($review->aanbeveling);

			$stars = str_replace(',', '.', $review->gemiddelde) / 2;

			$starHtml = '';

			for($x = 0; $x < 5; $x++) {
				if(floor($stars) - $x >= 1) {
					$starHtml .= '<i class="fa fa-star"></i>'; 
				} elseif($starNumber - $x > 0) { 
					$starHtml .= '<i class="fa fa-star-half-o"></i>'; 
				} else {
					$starHtml .= '<i class="fa fa-star-o"></i>';
				}
			}

			$output .= <<<EOD
<div class="review">
	<div class="average">
		<span>$review->gemiddelde</span>
	</div>
	<div class="content">
		<span>$starHtml</span>
		<div class="name">
			<b><span itemprop="author">$review->voornaam $review->achternaam</span> $review->woonplaats</b>
		</div>
		<div class="reason">
			Reden: $review->redenbezoek
		</div>
		<div class="description">
			$review->beschrijving
		</div>
		<div class="clearfix"></div>
		<div class="grades">
			<table>
				<tbody>
					<tr>
						<td>Service</td>
						<td>$review->service</td>
					</tr>
					<tr>
						<td>Deskundigheid</td>
						<td>$review->deskundigheid</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="grades">
			<table>
				<tbody>
					<tr>
						<td>Prijs / Kwaliteit</td>
						<td>$review->prijskwaliteit</td>
					</tr>
					<tr>
						<td>Totaal</td>
						<td>$review->gemiddelde</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="grades">
			<table>
				<tbody>
					<tr>
						<td>Aanbeveling?</td>
						<td>$review->aanbeveling</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
EOD;
		}

		return $output;
	}

	/**
	 * Add rating to each page
	 *
	 * @return content
	 */
	public function klantenvertellen_rating_shortcode()
	{
		$data = $this->getFromUrl(get_option('klantenvertellen_xml_url'));

		$ratingSnippet = <<<EOD
<div class="rating">
	<span itemscope itemtype="http://schema.org/WebPage">
		<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
			Beoordeling door klanten:
			<span itemprop="ratingValue">{$data->totaal->gemiddelde}</span>/<span itemprop="bestRating">10</span>
			<span> van de <span itemprop="ratingCount">{$data->statistieken->aantalingevuld}</span> beoordelingen</span>
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
		return simplexml_load_file($url);
	}
	
}

new KlantenVertellenOptions();
new KlantenVertellen();
