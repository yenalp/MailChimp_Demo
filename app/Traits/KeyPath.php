<?php
namespace App\Traits;

trait KeyPath
{
    // See the config/helpers.php for the getKp and setKp functions

    public function getKp($path, $default = null)
    {
        return getKp($this, $path, $default);
    }

    /**
    * Suppression is set here because the boolean flag makes sense in this context.
    * It essentially acts like the "mkdir -p" flag.
    *
    * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
    */
    public function setKp($path, $val, $createPath = true)
    {
        return setKp($this, $path, $val, $createPath);
    }

    /*

    * Allows nested properties to be accessed using . path syntax.
    *
    * eg, $someVar = $this->getPropByPath('data.value.message.id');
    *
    * which would be the equivalent of:
    *
    * $someVar = $this['data']['value']['message']['id'];
    *
    * Null checks are handled at each level of nesting and the optional
    * default value will be returned if the property does not exist.
    */
    public function getPropByPath($path, $default = null)
    {
        return $this->getValueAtPath($this, $path, $default);
    }

    public function getValueAtPath($obj, $path, $default = null)
    {
        $parts = explode('.', $path);
        $current = $obj;
        foreach ($parts as $part) {
            if (!$current && $current !== false) {
                return $default;
            }

            if (gettype($current) === 'object'
                && !property_exists($current, $part)
            ) {
                return $default;
            }
            
            if (gettype($current) === 'object') {
                $current = $current->{$part};
                continue;
            }

            if (!isset($current[$part])) {
                return $default;
            }
            $current = $current[$part];
        }
        return $current;
    }

    /**
    * Suppression is set here because the boolean flag makes sense in this context.
    * It essentially acts like the "mkdir -p" flag.
    *
    * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
    */
    public function setPropByPath($path, $val, $createPath = true)
    {
        $parts = explode('.', $path);

        $first = array_shift($parts);
        $reference = &$this->{$first};
        foreach ($parts as $part) {
            if (!array_key_exists($part, $reference)) {
                if (!$createPath) {
                    return false;
                }
                $reference[$part] = [];
            }
            $reference = &$reference[$part];
        }
        $reference = $val;
    }
}
