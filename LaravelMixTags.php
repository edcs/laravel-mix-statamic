<?php

namespace Statamic\Addons\LaravelMix;

use Statamic\API\Config;
use Statamic\API\File;
use Statamic\API\Str;
use Statamic\API\URL;
use Statamic\Extend\Tags;

class LaravelMixTags extends Tags
{
    /**
     * The name of Mix's revision manifest file.
     */
    const MANIFEST = 'mix-manifest.json';

    /**
     * The {{ laravel_mix }} tag - outputs the revision manifest file as JSON.
     *
     * @return string|array
     */
    public function index()
    {
        return $this->getManifest()->toJson();
    }

    /**
     * The {{ laravel_mix:css }} tag - outputs the URL or tag for the theme's main CSS file.
     *
     * @return string
     */
    public function css()
    {
        $src  = $this->get('src', Config::get('theming.theme'));
        $path = '/css/' . Str::ensureRight($src, '.css');
        $url  = $this->themeUrl($this->getManifest()->get($path));

        if ($this->getBool('tag')) {
            return '<link rel="stylesheet" href="' . $url . '" />';
        }

        return $url;
    }

    /**
     * The {{ laravel_mix:js }} tag - outputs the URL or tag for the theme's main JS file.
     *
     * @return string
     */
    public function js()
    {
        $src  = $this->get('src', Config::get('theming.theme'));
        $path = '/js/' . Str::ensureRight($src, '.js');
        $url  = $this->themeUrl($this->getManifest()->get($path));

        if ($this->getBool('tag')) {
            return '<script src="' . $url . '"></script>';
        }

        return $url;
    }

    /**
     * Returns the revision manifest contained in a Collection.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getManifest()
    {
        $path = webroot_path('site/themes/' . Config::get('theming.theme') . '/' . static::MANIFEST);

        return collect(json_decode(File::get($path), true));
    }

    /**
     * Transforms the asset directory into a relative or absolute URL for use in the front-end.
     *
     * @param string $path
     * @return string
     */
    private function themeUrl($path)
    {
        $url = URL::assemble(
            Config::get('system.filesystems.themes.url'),
            Config::get('theming.theme'),
            $path
        );

        $url = URL::prependSiteUrl(
            $url,
            $this->get('locale', default_locale()),
            false
        );

        if (!$this->getBool('absolute')) {
            $url = URL::makeRelative($url);
        }

        return $url;
    }
}
