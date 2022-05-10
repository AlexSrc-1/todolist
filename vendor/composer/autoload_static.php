<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7da9fb333c6efb6c9ba29793b0bbe55f
{
    public static $prefixLengthsPsr4 = array (
        'a' => 
        array (
            'app\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7da9fb333c6efb6c9ba29793b0bbe55f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7da9fb333c6efb6c9ba29793b0bbe55f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7da9fb333c6efb6c9ba29793b0bbe55f::$classMap;

        }, null, ClassLoader::class);
    }
}