<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit007eaeead6dfa19c28e467a042b8dcb7
{
    public static $prefixLengthsPsr4 = array (
        'w' => 
        array (
            'wapmorgan\\Mp3Info\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'wapmorgan\\Mp3Info\\' => 
        array (
            0 => __DIR__ . '/..' . '/wapmorgan/mp3info/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit007eaeead6dfa19c28e467a042b8dcb7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit007eaeead6dfa19c28e467a042b8dcb7::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
