# Laravel Mix for Statamic

This is a Stamic add-on which allows you to build CSS and JavaScript files for your website using
[Laravel Mix](https://github.com/JeffreyWay/laravel-mix). Mix differs from Laravel Elixir in several ways - the one we're
interested in here is the `mix-manifest.json` file, if you're familiar with Elixir then you'll notice the filename has changed.
This makes it incompatible with Statamic's built in support, hence needing a separate add-on.

## Installation

The easiest way of installing this add-on is to download as a zip bundle straigt from GitHub and placing it in your
`site/addons` directory.

An alternative way, if you're a Git user, would be do add it as a Git submodule. This gives you the avantage of being able
to pull in any updates which may be published in the future. The following command will install this add-on as a submodule if
run from the root of your project:

```bash
$ git submodule add https://github.com/edcs/laravel-mix-statamic site/addons/LaravelMix
```

## Setting Up Mix

This is an example `webpack.mix.js` file - it sets the public path where the built JavaScript and CSS should end up (I like to
keep my Statamic application files out of my public directory), then it transpiles my JavaScript modules into a single file
and finally it converts my SASS into CSS:

```javascript
const laravelMix = require('laravel-mix');

laravelMix
    .setPublicPath('../../../public/site/themes/theme-name')
    .js('js/app.js', '/js/theme-name.js')
    .sass('scss/app.scss', '/css/theme-name.css');
```

One of the useful things Mix does is tell Webpack to version the built files so that the cache can be busted when new changes
are published to a website. To implement this, the following change is required:

```javascript
const laravelMix = require('laravel-mix');

laravelMix
    .setPublicPath('../../../public/site/themes/theme-name')
    .js('js/app.js', '/js/theme-name.js')
    .sass('scss/app.scss', '/css/theme-name.css')
    .version();
```

This will generate files with a filename similar to this, `theme-name.a22b8a115a8da98d0a70.css`. This file can now no longer be
picked up by Statamic's built in theme CSS and JS tags as they no longer follow convention. Don't worry though, the next step
solves that problem!

## Setting Up Theme Templates

You can now update your theme template tags to use those provided by this add-on:

```
<!-- the default CSS template tag: -->
{{ theme:css }}

<!-- should be changed to: -->
{{ laravel_mix:css }}

<!-- the default JavaScript template tag: -->
{{ theme:js }}

<!-- should be changed to: -->
{{ laravel_mix:js }}
```

This add-on supports the following tags, these are:

|  Parameter |     Default     |                Description               |
|:----------:|:---------------:|------------------------------------------|
|      `tag` | `boolean` false | Enable this to output the full HTML tag. |
| `absolute` | `boolean` false | Output an absolute or relative URL.      |
|     `path` | `string`  null  | The path which gets prepended to the URL of the asset. If it is omitted, the default Statamitc behaviour is invoked which is to generate a URL relative to the theme's public directory |

## Using with Laravel Blade instead of Antlers.

When using Laravel Blade the add-on tags aren't available. Instead, use the modifier:

```
<!-- For CSS. -->
<link rel="stylesheet" href="{{ modify('css')->laravelMix() }}">

<!-- For JS. -->
<script src="{{ modify('js')->laravelMix() }}"></script>
```

## And Finally...

Now you're ready to build your CSS and JavaScript assets using Laravel Mix - once complete, there should be a
`mix-manifest.json` in the build directory.
