<?php 
/**
 * Page Install Helper File.
 *
 * PHP version 5.4
 *
 * @author   Oliver Green <oliver@c5dev.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5dev.com/
 */
namespace C5dev\Package\Thanks;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Page\Page;
use Concrete\Core\Page\Single;
use Redirect;
use Request;
use Package;

/**
 * Package Installer Helper Class.
 *
 * @author   Oliver Green <oliver@c5dev.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5dev.com/
 */
class PackageInstallHelper
{
    /**
     * The path to install the thank you page to.
     *
     * @var string
     */
    protected $thanks_page_path = '/dashboard/thanks-package-installed/';

    /**
     * The current package.
     * 
     * @var mixed
     */
    protected $package;

    /**
     * Constructor.
     * 
     * @param mixed
     */
    public function __construct($package)
    {
        $this->package = $package;
    }

    /**
     * Redirects the user to a custom page after package install.
     *
     * @return void
     */
    public function checkForPostInstall()
    {
        $url_parts = explode('/', substr(Request::getInstance()->getPath(), 1));

        if (5 === count($url_parts) && 'package_installed' === $url_parts[3]) {
            $db_package = Package::getByID($url_parts[4]);

            if ($db_package->getPackageHandle() === $this->package->getPackageHandle()) {
                $r = Redirect::to(
                    $this->thanks_page_path.$db_package->getPackageID()
                );
                $r->send();
                die;
            }
        }
    }

    /**
     * Add the 'Thanks for Installing' page if
     * it doesn't already exist.
     */
    public function addThanksPage()
    {
        if (! ($sp = Page::getByPath($this->thanks_page_path))) {
            $sp->delete();
        }

        $sp = Single::add($this->thanks_page_path, $this->package);
        $sp->update(['cName' => 'Package Installed']);
        $sp->setAttribute('exclude_nav', true);

        return $sp;
    }
}
