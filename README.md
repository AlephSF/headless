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
it like any other WP Plugin. **It will not work** until you have the proper constants
set, however! Place these constants wherever you place custom constants. We use Bedrock
so they generally go in an `.env` file, but you can also drop them into your `wp-config.php`
if you're old-school:
- `HEADLESS_FRONTEND_URL` (required `string`) - Should be the URL, including the `https://` where
your site's front end appears. No trailing slashes, please!  
- `HEADLESS_SHORTCODE_WHITELIST` (optional `array` of strings) - Specify which shortcodes should
be parsed by WordPress, i.e. `blockquote`.
- `HEADLESS_POST_PREVIEW` (optional `boolean`, defaults to `true`) - Whether or not to hijack
post previews and send them to the front end for rendering.
- `HEADLESS_POST_PREVIEW_DEST` (optional `string`, defaults to `HEADLESS_FRONTEND_URL`) - used
to override the URL where we're sending `POST` requests with preview data. 
