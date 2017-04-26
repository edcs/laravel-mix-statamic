<?php

namespace Statamic\Addons\LaravelMix;

use Statamic\Extend\Tags;

class LaravelMixTags extends Tags
{
    use LaravelMixTrait;

    /**
     * The {{ laravel_mix }} tag - outputs the revision manifest file as JSON.
     *
     * @return string
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
        $url = $this->getAssetPath('css');

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
        $url = $this->getAssetPath('js');

        if ($this->getBool('tag')) {
            return '<script src="' . $url . '"></script>';
        }

        return $url;
    }
}
