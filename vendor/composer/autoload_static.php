<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit815704ca75640aa97fea1d865779f7d0
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'TEC\\Extensions\\Promoter\\' => 24,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'TEC\\Extensions\\Promoter\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit815704ca75640aa97fea1d865779f7d0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit815704ca75640aa97fea1d865779f7d0::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit815704ca75640aa97fea1d865779f7d0::$classMap;

        }, null, ClassLoader::class);
    }
}
