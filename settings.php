<?php
/**
 * Class KlantenVertellenOptions
 */
class KlantenVertellenOptions
{
	/**
	 * Add the actual things to WP 
	 */
	public function __construct()
	{
      if (!is_admin())
		  return;

		add_action("admin_menu", [$this, "add_theme_menu_item"]);
		add_action("admin_init", [$this, "display_klantenvertellen_options_fields"]);
	}
	
	/*
	 * Create menu entry for the settings page
	 */
	public function add_theme_menu_item() {
		add_menu_page("Klantenvertellen", "Klantenvertellen", "manage_options", "Klantenvertellen-options", [$this, "klantenvertellen_page"], null, 99);
	}

	/*
	 * Add all the things to the page
	 */
	public function klantenvertellen_page() {
	?>
			<div class="wrap">
			<h1>Klanten vertellen</h1>
			<form method="post" action="options.php" enctype="multipart/form-data">
	<?php
		settings_fields("klantenvertellen_settings");
		do_settings_sections("klantenvertellen-options");
		submit_button(); 
	?>          
			</form>
			</div>
	<?php
	}

	/*
	 * Function to register all settings and create sections and names
	 */
	public function display_klantenvertellen_options_fields() {
		/**
		 * General settings
		 */
		add_settings_section(
			"general_section",
			"Algemeen",
			null,
			"klantenvertellen-options"
		);

		add_settings_field(
			"klantenvertellen_xml_url",
			"Klantenvertellen XML url",
			[$this, "klantenvertellen_xml_url_element"],
			"klantenvertellen-options",
			"general_section"
		);

		register_setting(
			"klantenvertellen_settings",
			"klantenvertellen_xml_url"
		);
	}

	/*
	 * URL element
	 */
	public function klantenvertellen_xml_url_element() {
	?>
		<input type="text" name="klantenvertellen_xml_url" id="klantenvertellen_xml_url" value="<?php echo get_option("klantenvertellen_xml_url"); ?>" />
	<?php
	}
}
