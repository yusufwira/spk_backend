<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcdb40e25ebb738892bc1977892bbbb7a
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Phpml\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Phpml\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-ai/php-ml/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitcdb40e25ebb738892bc1977892bbbb7a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcdb40e25ebb738892bc1977892bbbb7a::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
