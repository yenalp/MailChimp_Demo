<?php
if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

if (!function_exists('resolve')) {
    /**
     * Resolve a service from the container.
     *
     * @param  string  $name
     * @param  array  $parameters
     * @return mixed
     */
    function resolve($name, $parameters = [])
    {
        return app($name, $parameters);
    }
}

if (!function_exists('getKp')) {

    function getKp($obj, $path, $default = null)
    {
        $parts = explode('.', $path);
        $current = $obj;
        foreach ($parts as $part) {
            if (!$current && $current !== false) {
                return $default;
            }
            if(gettype($current) === 'object'
                && !property_exists($current, $part)
            ) {
                return $default;
            }
            if(gettype($current) === 'object') {
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
}

if (!function_exists('setKp')) {

    function setKp($obj, $path, $val, $createPath = true)
    {
        $parts = explode('.', $path);

        $first = array_shift($parts);
        if(gettype($obj) === 'object') {
            $reference = &$obj->{$first};
        }
        else {
            $reference = &$obj[$first];
        }

        $partsLen = count($parts);
        $count = 0;
        foreach ($parts as $part) {
            $count++;
            if(gettype($reference) === 'object') {
                if(!property_exists($reference, $part) && !$createPath) {
                    return false;
                }
                if($reference->{$part} === null) {
                    $reference->{$part} = [];
                }

                $reference = &$reference->{$part};
                continue;
            }

            if(gettype($reference) === 'array') {
                if (!array_key_exists($part, $reference)) {
                    if (!$createPath) {
                        return false;
                    }
                    $reference[$part] = [];
                }
                $reference = &$reference[$part];
                continue;
            }

            if($count === $partsLen
                && isset($reference[$part])) {
                $reference = &$reference[$part];
                break;
            }

            if(isset($reference[$part])) {
                return false;
            }

            if(!isset($reference[$part])
                && !$createPath
            ) {
                return false;
            }

            return false;
        }
        $reference = $val;
        return true;
    }
}