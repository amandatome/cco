# ThemeIsle White Label Module

[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0) 

The ThemeIsle White Label module add the functionality to remove any links to ThemeIsle website and change the identity in the dashboard. This setting is mostly used by agencies and developers who are building websites for clients.

How do I use this?
---
1. [Include the ThemeIsle White Label Module.](#1-include-the-themeisle-white-label-module)
2. [Call an instance of the main class in your project.](#2-call-an-instance-of-the-main-class-in-your-project)
3. [Configure access to the settings page.](#3-configure-access-to-the-settings-page)

### 1. Include the ThemeIsle White Label Module
There are two ways in which you can use the White Label Module: 
- Include this repository directly in your project
- Include it as a composer dependency. Because this is a private repository you'll need to add those lines in your composer.json file:
```
{
"require": {
    [...],
    "codeinwp/ti-white-label": "master"
  },
[...],
"autoload": {
    "files": [
     [...],
      "vendor/codeinwp/ti-white-label/load.php"
    ]
  },
"repositories": [
    {
      "type": "vcs",
      "url":  "git@github.com:Codeinwp/ti-white-label.git"
    }
  ],
}
```

### 2. Call an instance of the main class in your project
You'll have to instantiate the main class. Here's an example:

1. For plugins:
```
//Define this variable in your plugin's main file:
define( 'NEVE_PRO_BASEFILE', __FILE__ );

//Add this in a loader class or in a file where you include your files:
if ( class_exists( '\Ti_White_Label' ) ) {
    \Ti_White_Label::instance( NEVE_PRO_BASEFILE );
}
```

2. For themes:
```
//Add this in functions PHP:
if ( class_exists( 'Ti_White_Label' ) ) {
    Ti_White_Label::instance(get_template_directory() . '/style.css');
}
```

### 3. Configure access to the settings page.

Once you require the main class in your project a dashboard page will be created. You can access it by going to this link: **https://<your-site-url>/wp-admin/?page=ti-white-label**.
You need to make sure that you add the link to it somewhere.  
For example, you can add a link to that page. Keep in mind that the white label module offers the functionality to remove the access to that page. Here is an example:

```
/**
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function example_add_dashboard_widgets() {

	wp_add_dashboard_widget(
                 'example_dashboard_widget',         // Widget slug.
                 'Example Dashboard Widget',         // Title.
                 'example_dashboard_widget_function' // Display function.
        );	
}

// Check if the white label isn't enabled. If it's enabled, hide the access to the settings page
$white_label_settings  = get_option( 'ti_white_label_inputs' );
$white_label_settings  = json_decode( $white_label_settings, true );
$white_label_is_hidden = $white_label_settings['white_label'];
if ( $white_label_is_hidden !== true ) {
    add_action( 'wp_dashboard_setup', 'example_add_dashboard_widgets' );
}

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function example_dashboard_widget_function() {

	// Display the link to white label page
	echo admin_url( '?page=ti-white-label' );
}
```

Make sure that there is a way to reset the white label option. Here's an example where the module is load from a plugin. The option will reset when the plugin is deactivated:

```
/**
 * Actions that are running on plugin deactivate.
 */
function run_uninstall_actions() {
	/**
	 * Disable white label and make sure that the module is visible again in dashboard.
	 */
	$white_label_settings  = get_option( 'ti_white_label_inputs' );
	$white_label_settings  = json_decode( $white_label_settings, true );
	$white_label_settings['white_label'] = false;
	update_option( 'ti_white_label_inputs', json_encode( $white_label_settings ) );
}
register_deactivation_hook( __FILE__, 'run_uninstall_actions' );
```
License
---
The ThemeIsle White Label Module is licensed under the GPLv3 or later. 

Credits
---
- Inspired by Astra Pro White Label module.