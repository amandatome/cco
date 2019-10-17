<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite0ebfeffc7f43696a317914bd2bb6ed3
{
    public static $files = array (
        '3c811c5eee2f69449ba771bff79ea54a' => __DIR__ . '/..' . '/codeinwp/ti-about-page/load.php',
        'c8e9888657e6defd3de05726d7b39ae1' => __DIR__ . '/..' . '/codeinwp/ti-onboarding/load.php',
        'c730ac5ba4946398dd12db7e8d42d1c8' => __DIR__ . '/..' . '/codeinwp/themeisle-sdk/load.php',
        '4c3bcd61dc8e4dc113d6d770892056fe' => __DIR__ . '/..' . '/codeinwp/ti-about-page/load.php',
        '11c10943e97268bbf2aa201d18da2c4f' => __DIR__ . '/..' . '/codeinwp/ti-onboarding/load.php',
    );

    public static $prefixLengthsPsr4 = array (
        'H' => 
        array (
            'HFG\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'HFG\\' => 
        array (
            0 => __DIR__ . '/../..' . '/header-footer-grid',
        ),
    );

    public static $classMap = array (
        'HFG\\Core\\Builder\\Abstract_Builder' => __DIR__ . '/../..' . '/header-footer-grid/Core/Builder/Abstract_Builder.php',
        'HFG\\Core\\Builder\\Footer' => __DIR__ . '/../..' . '/header-footer-grid/Core/Builder/Footer.php',
        'HFG\\Core\\Builder\\Header' => __DIR__ . '/../..' . '/header-footer-grid/Core/Builder/Header.php',
        'HFG\\Core\\Components\\Abstract_Component' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/Abstract_Component.php',
        'HFG\\Core\\Components\\Abstract_FooterWidget' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/Abstract_FooterWidget.php',
        'HFG\\Core\\Components\\Button' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/Button.php',
        'HFG\\Core\\Components\\CartIcon' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/CartIcon.php',
        'HFG\\Core\\Components\\Copyright' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/Copyright.php',
        'HFG\\Core\\Components\\CustomHtml' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/CustomHtml.php',
        'HFG\\Core\\Components\\FooterWidgetFour' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/FooterWidgetFour.php',
        'HFG\\Core\\Components\\FooterWidgetOne' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/FooterWidgetOne.php',
        'HFG\\Core\\Components\\FooterWidgetThree' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/FooterWidgetThree.php',
        'HFG\\Core\\Components\\FooterWidgetTwo' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/FooterWidgetTwo.php',
        'HFG\\Core\\Components\\Logo' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/Logo.php',
        'HFG\\Core\\Components\\MenuIcon' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/MenuIcon.php',
        'HFG\\Core\\Components\\Nav' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/Nav.php',
        'HFG\\Core\\Components\\NavFooter' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/NavFooter.php',
        'HFG\\Core\\Components\\Search' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/Search.php',
        'HFG\\Core\\Components\\SearchResponsive' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/SearchResponsive.php',
        'HFG\\Core\\Components\\SecondNav' => __DIR__ . '/../..' . '/header-footer-grid/Core/Components/SecondNav.php',
        'HFG\\Core\\Customizer' => __DIR__ . '/../..' . '/header-footer-grid/Core/Customizer.php',
        'HFG\\Core\\Customizer\\SpacingControl' => __DIR__ . '/../..' . '/header-footer-grid/Core/Customizer/SpacingControl.php',
        'HFG\\Core\\Interfaces\\Builder' => __DIR__ . '/../..' . '/header-footer-grid/Core/Interfaces/Builder.php',
        'HFG\\Core\\Interfaces\\Component' => __DIR__ . '/../..' . '/header-footer-grid/Core/Interfaces/Component.php',
        'HFG\\Core\\Settings\\Config' => __DIR__ . '/../..' . '/header-footer-grid/Core/Settings/Config.php',
        'HFG\\Core\\Settings\\Defaults' => __DIR__ . '/../..' . '/header-footer-grid/Core/Settings/Defaults.php',
        'HFG\\Core\\Settings\\Manager' => __DIR__ . '/../..' . '/header-footer-grid/Core/Settings/Manager.php',
        'HFG\\Main' => __DIR__ . '/../..' . '/header-footer-grid/Main.php',
        'HFG\\Traits\\Core' => __DIR__ . '/../..' . '/header-footer-grid/Traits/Core.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite0ebfeffc7f43696a317914bd2bb6ed3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite0ebfeffc7f43696a317914bd2bb6ed3::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite0ebfeffc7f43696a317914bd2bb6ed3::$classMap;

        }, null, ClassLoader::class);
    }
}