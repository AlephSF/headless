# This is Where WordPress Loses its Head

The goal of this plugin is to make it simple-ish to run a headless WordPress installation
serving only JSON responses from the native REST API. When we build React-based (or really
anything-based) front ends, there are some super-common things we need to do to make
sure everything plays nice together. This plugin aims to capture all the most common
features in one go:
- Redirect all front-end requests to the WordPress site to their respective proper
routes on the real front end.
- Tweak permalinks so that they don't aim at the WordPress site, but rather at the
front end.
- Allow post previews to work by sending JSON to the front end as a `POST` request.
- Add SEO data to singular WordPress entities, like posts and pages, in the API.
- Decide which shortcodes to render in WordPress and which to pass along to the front end.

## Getting Started
You can just download this plugin, place it in your `plugins` folder and activate
it like any other WP Plugin. A better way, if you're using Bedrock and Composer for your
plugins, is to add this to your `composer.json`'s `respositories` block:
```json
{
  "type": "package",
  "package": {
    "name": "alephsf/headless",
    "version": "1.0.1",
    "type": "wordpress-plugin",
    "source": {
      "url": "https://github.com/AlephSF/headless",
      "type": "git",
      "reference": "master"
    }
  }
}
```
And then require it in the `require` block like this:
```json
    "alephsf/headless": "*"
```
**NB!** You must specify a new version number in the first block every time you want
to pull an upgraded version of this plugin.

Headless will not work until you have at least the `HEADLESS_FRONTEND_URL` constant
set! Set the following values wherever you place custom constants. We use Bedrock
so they generally go in an `.env` file, but you can also drop them into `application.php` or
your `wp-config.php` if you're old-school:
- `HEADLESS_FRONTEND_URL` (required `string`) - Should be the URL, including the `https://` where
your site's front end appears. No trailing slashes, please!  
- `HEADLESS_SHORTCODE_WHITELIST` (optional `array` of strings) - Specify which shortcodes should
be parsed by WordPress, i.e. `blockquote`. Note that this must be set in PHP, as `.env` files can't
use arrays. A good place would be in the `application.php` if you're using Bedrock.
- `HEADLESS_POST_PREVIEW` (optional `boolean`, defaults to `true`) - Whether or not to hijack
post previews and send them to the front end for rendering.
- `HEADLESS_POST_PREVIEW_DEST` (optional `string`, defaults to `HEADLESS_FRONTEND_URL`) - used
to override the URL where we're sending `POST` requests with preview data.

## Here's What it Does

When activated, this plugin does a few important things:
- It rewrites and edits all links from WordPress to the same paths at the URL specified in
the `HEADLESS_FRONTEND_URL` constant.
- It disables all shortcode parsing *except* for any shortcodes explicitly listed in the
`HEADLESS_SHORTCODE_WHITELIST` array. We do this because lots of shortcodes try to load
external scripts, which is a no-no for most front-end apps. (It also means you'll have
to handle those shortcodes on the front end somehow!)
- It disables `wptexturize` which plays havoc with unparsed shortcodes. If you want fancy
quotes, you'll have to do it without WordPress's help.
- It adds an `seoData` object to REST API responses for posts and pages, which contain basic
information that everyone should have in their site's `<head>`. It is meant to work well with
Yoast SEO, so any of the page-specific overrides there will show up in the API.
- It optionally hijacks the post preview button by sending the entire post's data to a URL
of your choosing via a `POST` request. What you do with the data is up to you, but we send
it to the same place that our front-end app renders our posts.

## Roadmap

- Properly handle Yoast SEO sitemaps if at all possible.
- Improve SEO data handling and generation.
