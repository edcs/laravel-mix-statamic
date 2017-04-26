<?php

namespace Statamic\Addons\LaravelMix;

use Statamic\Extend\Modifier;

class LaravelMixModifier extends Modifier
{
    use LaravelMixTrait;

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
}
