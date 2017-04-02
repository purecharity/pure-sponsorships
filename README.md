# Pure Charity Sponsorships Plugin

A collection of shortcodes to show sponsorships on your WordPress site.

It depends on the Pure Charity Base Plugin being installed and it's credentials configured to work.

## Features

* The plugin enables 3 shortcodes for use in any page you want to.
* Possibility to configure between 3 display styles.
* Possibility to configure the plugin to use custom fields you have on the app.
* Possibility to customize the main color of the elements.
* Possibility to add a custom image to show as brand on the views.

## Installation

IMPORTANT:  At this time the plugin requires a name change after extracting from Github.  After downloading the source code from Github unzip the files and rename the folder **/purecharity-wp-sponsorships** and compress as **purecharity-wp-sponsorships.zip** if you plan to use the Wordpress plugin installer via upload.   

1. Copy the `/purecharity-wp-sponsorships` folder to the `/wp-content/plugins` on your WP install
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You're done!

## Shortcodes

### Sponsorships Listing
`[sponsorships program_id=X]`

Possible parameters:
* `program_id` - (Required) The id of the program to show
* `per_page` - The amount of records to fetch per page
* `reject` - (1,2,3) List of IDs of sponsorships to reject on the listing
* `status` - (available | reserved) To list only available or funded slots

### Single Child
`[sponsorship_child]`

Possible parameters:
* `sponsorship` - The id of the child to show

### Sponsorship Program
`[sponsorship id=X]`

Possible parameters:
* `id` - The sponsorship id

## Template Tags

### Sponsorships List

> Returns JSON information for the program requested

Function:
`pc_sponsorships($program_slug, $limit=9999, $status='public')`

Parameters:

`$program_slug`: The program's slug
`$limit`: Amount of sponsorships to pull
`$status`: (public|available|visible|reserved) Default: Public. Status of the sponsorships

### Sponsorships Info

> Returns JSON information for the program requested

Function:
`pc_sponsorship($id)`

Parameters:

`$id`: The sponsorship's ID
