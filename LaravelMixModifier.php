<?php

namespace Statamic\Addons\LaravelMix;

use Statamic\API\Config;
use Statamic\API\File;
use Statamic\API\Str;
use Statamic\API\URL;
use Statamic\Extend\Modifier;

class LaravelMixModifier extends Modifier
{
    /**
     * The name of Mix's revision manifest file.
     */
    const MANIFEST = 'mix-manifest.json';

    /**
     * Modifies the input into an asset path.
     *
     * @param string $value In this case either 'js' or 'css'.
     * @return string
     */
    public function index($value)
    {
        return $this->getAssetPath($value);
    }

    /**
     * Returns the path of given asset type.

     * @param  string $type
     * @return string
     */
    private function getAssetPath($type)
    {
        $theme    = Config::get('theming.theme');
        $path     = '/' . $type . '/' . Str::ensureRight($theme, '.' . $type);
        $manifest = $this->getManifest()->get($path);

        return $this->themeUrl($manifest);
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

        return $url;
    }
}
