<?php 
/**
 * Package Controller File.
 *
 * PHP version 5.4
 *
 * @author   Oliver Green <oliver@c5dev.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5dev.com/add-ons/twitter-feed
 */
namespace Concrete\Package\SnowEffectPackage;

defined('C5_EXECUTE') or die('Access Denied.');

use Asset;
use AssetList;
use BlockType;
use Package;
use Concrete\Package\TweetFeedPackage\Src\TwitterFeedRequestHandler;
use Database;
use Illuminate\Filesystem\Filesystem;

/**
 * Package Controller Class.
 *
 * @author   Oliver Green <oliver@c5dev.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5dev.com/add-ons/twitter-feed
 */
class Controller extends Package
{
    /**
     * Package Handle.
     *
     * @var string
     */
    protected $pkgHandle = 'snow_effect_package';

    /**
     * Application Version Required.
     *
     * @var string
     */
    protected $appVersionRequired = '5.7.1';

    /**
     * Package Version.
     *
     * @var string
     */
    protected $pkgVersion = '0.9.0';

    /**
     * Keep me updated interest ID.
     * 
     * @var string
     */
    public $interest_id = '8dac0adcdc';

    /**
     * Package Name.
     *
     * @return string
     */
    public function getPackageName()
    {
        return t('Area Layout Snow Effect Components');
    }

    /**
     * Package Description.
     *
     * @return string
     */
    public function getPackageDescription()
    {
        return t('Adds an animated snow effect to an area layout.');
    }

    /**
     * Get a helper instance.
     * 
     * @param  mixed $pkg
     * @return \C5dev\Package\Thanks\PackageInstallHelper
     */
    protected function getHelperInstance($pkg)
    {
        if (! class_exists('\C5dev\Package\Thanks\PackageInstallHelper')) {
            // Require composer
            $filesystem = new Filesystem();
            $filesystem->getRequire(__DIR__.'/vendor/autoload.php');
        }

        return new \C5dev\Package\Thanks\PackageInstallHelper($pkg);
    }

    /**
     * Start-up Hook.
     *
     * @return void
     */
    public function on_start()
    {
        $this->registerAssets();

        // Check whether we have just installed the package 
        // and should redirect to intermediate 'thank you' page.
        $this->getHelperInstance($this)->checkForPostInstall();

        \Events::addListener('on_before_render', function($c) {
            $c['view']->requireAsset('snow');
        });
    }

    /**
     * Install Hook.
     *
     * @return void
     */
    public function install()
    {
        $pkg = parent::install();

        // Install the 'thank you' page if needed.
        $this->getHelperInstance($pkg)->addThanksPage();

        return $pkg;
    }

    /**
     * Unistall Hook.
     *
     * @return void
     */
    public function uninstall()
    {
        parent::uninstall();
    }

    /**
     * Registers the packages (and blocks) assets
     * with the concrete5 asset pipeline.
     *
     * @return void
     */
    public function registerAssets()
    {
        $al = AssetList::getInstance();

        // Bootstrap Tabs
        $al->register(
            'javascript',
            'snow/js',
            'assets/snow.js',
            [
                'version' => '0.9.0',
                'position' => Asset::ASSET_POSITION_FOOTER,
                'minify' => false,
                'combine' => false,
            ],
            $this
        );

        $al->register(
            'css',
            'snow/css',
            'assets/snow.css',
            [
                'version' => '0.9.0',
                'position' => Asset::ASSET_POSITION_HEADER,
                'minify' => false,
                'combine' => false,
            ],
            $this
        );

        $al->registerGroup(
            'snow',
            [
                ['css', 'snow/css'],
                ['javascript', 'snow/js'],
            ]
        );
    }
}
