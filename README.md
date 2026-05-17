# Logly Analytics — WordPress plugin

Privacy-first web analytics for WordPress. Adds the [Logly](https://logly.uk)
tracker to your site — under 1 KB, no cookies, no consent banner.

The plugin lives in [`logly/`](logly/). It injects the Logly tracking script and
shows your analytics dashboard inside WP Admin.

## Why Logly

- **Under 1 KB** — zero impact on your Lighthouse score or Core Web Vitals.
- **No cookie banner** — no cookies, no persistent identifiers, GDPR compliant by design.
- **Works with ad blockers** — including Brave Shields and uBlock Origin.
- **Active time on page** — measures real reading time, not just tab-open time.
- **Free plan** — 10,000 pageviews/month, forever, no credit card.

## Installation

**From a release zip** (recommended):

1. Download the latest `logly-analytics-*.zip` from the [Releases page](https://github.com/logly-uk/logly-wordpress/releases).
2. In WP Admin, go to **Plugins → Add New → Upload Plugin**, choose the zip, then **Install Now** and **Activate**.
3. Open **Logly** in the admin menu and enter your Site ID.

**From WordPress.org**: once approved, search for *Logly Analytics* under Plugins → Add New.

**Manually**: copy the [`logly/`](logly/) folder into `wp-content/plugins/`, then activate the plugin under Plugins → Installed Plugins.

## Configuration

Your Site ID comes from your Logly dashboard at
[app.logly.uk](https://app.logly.uk) — Settings, shown below the install
snippet for each site. Paste it into the plugin's settings page and you're done.
The tracker is injected automatically on every page; there is no tag manager and
no configuration file.

To see analytics inside WP Admin, enable the **Public** toggle for your site in
the Logly dashboard.

## Requirements

- WordPress 5.0+
- PHP 7.4+
- A Logly account ([free plan](https://logly.uk) available)

## Links

- Logly — <https://logly.uk>
- Documentation — <https://logly.uk/docs>
- Dashboard — <https://app.logly.uk>

## License

GPL-2.0-or-later. See the plugin headers in [`logly/logly.php`](logly/logly.php).
