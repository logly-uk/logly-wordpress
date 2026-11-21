=== Loglyuk Privacy Analytics ===
Contributors: loglyuk
Tags: analytics, privacy, statistics, cookieless, gdpr
Requires at least: 5.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.5
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Cookieless, privacy-first web analytics — a lightweight Google Analytics alternative. No cookie banner, GDPR-ready, under 1 KB.

== Description ==

**Loglyuk Privacy Analytics** brings cookieless, privacy-first web analytics to your WordPress site — a lightweight alternative to Google Analytics that measures your traffic without cookies, without storing personal data, and without a consent banner. You see your stats right inside the WordPress dashboard, and the tracking script is under 1 KB, so it won't drag down your page speed or Core Web Vitals.

👉 **[Try our live demo](https://app.logly.uk/demo)** — explore the full dashboard with real data, no signup required.

= Why Loglyuk? =

* **See your stats inside WordPress** — pageviews trend, top pages, referrers and country/device/browser charts right in wp-admin, rendered natively (no iframe)
* **Under 1 KB** — zero impact on your Lighthouse score or Core Web Vitals
* **No cookie banner required** — no cookies, no persistent identifiers, GDPR compliant by design
* **Works with ad blockers** — including Brave Shields and uBlock Origin
* **Active time on page** — measures real reading time, not just tab-open time
* **Free plan** — 10,000 pageviews/month, forever, no credit card

= What you get =

* Pageviews, sessions, visitors
* Top pages and referrers
* Countries, devices, browsers
* Active visitors right now
* Week-over-week comparison

= Setup =

1. Install and activate the plugin
2. Go to **Loglyuk** in the WordPress admin sidebar
3. Enter your Site ID from [app.logly.uk](https://app.logly.uk)
4. Done — no tag manager, no configuration file

== Installation ==

1. Upload the `loglyuk-privacy-analytics` folder to `/wp-content/plugins/`
2. Activate the plugin via **Plugins → Installed Plugins**
3. Go to **Loglyuk** in the admin sidebar and enter your Site ID

== Frequently Asked Questions ==

= Is Loglyuk a Google Analytics alternative? =

Yes. Loglyuk is a lightweight, cookieless alternative to Google Analytics. It reports the metrics most site owners actually use — pageviews, visitors, sessions, top pages, referrers, countries, devices and browsers — without cookies, consent banners, or a heavy script.

= Can I see my analytics inside the WordPress dashboard? =

Yes. The plugin renders your stats natively on the Loglyuk admin page — a pageviews trend, top pages and referrers, and country, device and browser breakdowns — by reading Loglyuk's public API. No external page is embedded in your admin area.

= Do I need a cookie banner? =

No. Loglyuk does not set cookies or store personal data, so no consent banner is required under GDPR or PECR.

= Does it work with Brave or ad blockers? =

Yes. Loglyuk is designed to work with Brave Shields, uBlock Origin, and Firefox Enhanced Tracking Protection.

= Where do I find my Site ID? =

In your Loglyuk dashboard at [app.logly.uk](https://app.logly.uk) — go to Settings for your site and copy the ID from the installation snippet.

= Is there a free plan? =

Yes. Free forever up to 10,000 pageviews/month, no credit card required.

== External services ==

This plugin loads a tracking script from Loglyuk (https://logly.uk) and sends pageview data to Loglyuk's servers so you can view analytics for your site. Loglyuk is a privacy-first web analytics service operated at logly.uk.

**What is sent and when:** every time a visitor loads a page on your site, the tracker script (loaded from `https://logly.uk/p.js`) sends a small GET request to `https://logly.uk/e` containing only:

* the page path being viewed
* an anonymous session hash (derived from IP + User-Agent + a daily-rotating salt; not reversible, not stored)
* the visitor's country (resolved from IP at the edge; the IP itself is never stored)
* device, browser and OS family (e.g. "Mobile / Chrome / Android")
* the referrer URL, if any
* your Site ID (so Loglyuk knows which property the pageview belongs to)

No cookies are set, no personal data is stored, and no consent banner is required under GDPR or PECR.

**When this happens:** only after you configure the plugin with a Site ID in the Loglyuk admin page. Without a Site ID, the plugin sends nothing.

**Reading your analytics in wp-admin:** when you open the Loglyuk admin page, the plugin fetches your aggregated, non-personal statistics (totals, top pages and referrers) from Loglyuk's public API at `https://app.logly.uk/api/public/<your-site-id>/stats` and `/breakdown`, and renders them natively in WordPress. These read-only requests run only on the plugin's admin page and only return data you have explicitly marked Public in your Loglyuk settings.

**Terms and privacy policy:**

* Terms of Service: https://logly.uk/terms
* Privacy Policy: https://logly.uk/privacy

== Screenshots ==

1. Your analytics inside the WordPress admin — pageviews trend, top pages and referrers, and country, device and browser charts, rendered natively (no iframe).
2. Dashboard overview — pageviews trend, sessions, visitors, top pages and referrers, with week-over-week comparison.
3. Custom events with A/B comparison and conversion funnels that show exactly where visitors drop off.
4. Audience insights — countries, devices, browsers, and a user-flow view from source to entry and exit pages.

== Changelog ==

= 1.0.5 =
* Added a link to the live demo in the plugin description.

= 1.0.4 =
* The in-WordPress dashboard screenshot is now the first image on the listing.

= 1.0.3 =
* Added a screenshot of the dashboard inside the WordPress admin and refreshed the plugin description.

= 1.0.2 =
* Countries, devices and browsers are now shown as donut charts with a percentage legend.

= 1.0.1 =
* The admin dashboard now renders your analytics natively in WordPress via the Loglyuk public API instead of embedding an external iframe.
* Richer overview: pageviews trend chart, plus top pages, referrers, countries, devices and browsers, with a 7/30/90-day range picker.

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.5 =
Documentation only — added a live demo link to the description.

= 1.0.4 =
Documentation only — screenshot order updated.

= 1.0.3 =
Documentation only — refreshed description and added an in-WordPress dashboard screenshot.

= 1.0.2 =
Countries, devices and browsers now display as donut charts.

= 1.0.1 =
Analytics now render natively in wp-admin (no external iframe).

= 1.0.0 =
Initial release.
