<?php

namespace Statamic\Addons\LaravelMix;

use Statamic\API\Config;
use Statamic\API\File;
use Statamic\API\Str;
use Statamic\API\URL;
use Statamic\Extend\HasParameters;

trait LaravelMixTrait
{
    /**
     * Provides access to methods for retrieving parameters
     */
    use HasParameters;

    /**
     * The name of Mix's revision manifest file.
     */
    protected $manifest = 'mix-manifest.json';

    /**
     * Returns the path of given asset type.

     * @param  string $type
     * @return string
     */
    public function getAssetPath($type)
    {
        $theme    = Config::get('theming.theme');
        $path     = '/' . $type . '/' . Str::ensureRight($theme, '.' . $type);
        $manifest = $this->getManifest()->get($path);

        return $this->themeUrl($manifest);
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

    /**
     * Returns the revision manifest contained in a Collection.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getManifest()
    {
        $path = webroot_path('site/themes/' . Config::get('theming.theme') . '/' . $this->manifest);

        return collect(json_decode(File::get($path), true));
    }
}
