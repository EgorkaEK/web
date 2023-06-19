<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit65e09143db9373ecba510564f9a5cb5b
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Photos\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Photos\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit65e09143db9373ecba510564f9a5cb5b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit65e09143db9373ecba510564f9a5cb5b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit65e09143db9373ecba510564f9a5cb5b::$classMap;

        }, null, ClassLoader::class);
    }
}
