<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6278ab2d869842e2205997599c547f2e
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twilio\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twilio\\' => 
        array (
            0 => __DIR__ . '/..' . '/twilio/sdk/src/Twilio',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6278ab2d869842e2205997599c547f2e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6278ab2d869842e2205997599c547f2e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit6278ab2d869842e2205997599c547f2e::$classMap;

        }, null, ClassLoader::class);
    }
}
