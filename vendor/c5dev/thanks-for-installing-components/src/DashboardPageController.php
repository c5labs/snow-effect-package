<?php 
/**
 * Dashboard Page Controller File.
 *
 * PHP version 5.4
 *
 * @author   Oliver Green <oliver@c5dev.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5dev.com/
 */
namespace C5dev\Package\Thanks;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Page\Controller\DashboardPageController as Controller;
use Concrete\Core\Routing\Redirect;
use Package;

/**
 * Dashboard Page Controller Class.
 *
 * @author   Oliver Green <oliver@c5dev.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5dev.com/
 */
class DashboardPageController extends Controller
{
    /**
     * Keep in touch form URL.
     *
     * @var string
     */
    protected $intouchFormUrl = '//comms.c5labs.com/forms/keep-in-touch';

    /**
     * Returns the 'Keep in touch' form URL that is used
     * on the thank you page to display a email
     * subscription form to the user.
     *
     * @return string
     */
    protected function getIntouchFormUrl($package)
    {
        // Default ID
        $interestID = '6076d41f7f';

        // Version 8 fix.
        if (method_exists($package, 'getController')) {
            $package = $package->getController();
        }

        if (property_exists($package, 'interest_id')) {
            $interestID = $package->interest_id;
        }

        return $this->intouchFormUrl.'?interest='.$interestID;
    }

    /**
     * Require & add assets.
     *
     * @param mixed $package
     */
    protected function addAssets($package)
    {
        $path = $package->getRelativePath();

        $this->requireAsset('javascript', 'jquery');

        $items = [
            [
                'addFooterItem',
                '<script>var form_url = "%s";</script>',
                $this->getIntouchFormUrl($package),
            ],
            [
                'addFooterItem',
                '<script src="%s/vendor/c5dev/thanks-for-installing-components/assets/view.js"></script>',
                $path,
            ],
            [
                'addHeaderItem',
                '<link href="%s/vendor/c5dev/thanks-for-installing-components/assets/view.css" media="all" rel="stylesheet" />',
                $path,
            ],
        ];

        foreach ($items as $item) {
            $tag = sprintf($item[1], $item[2]);
            call_user_func_array([$this, $item[0]], [$tag]);
        }
    }

    /**
     * Show the 'thank you' page.
     *
     * @param  int $pkgID
     */
    public function view($pkgID = null)
    {
        $package = Package::getByID($pkgID);

        if (! $package) {
            return Redirect::to('/dashboard');
        }

        $this->addAssets($package);

        $this->set('package', $package);
        $this->set('template_path', $this->getTemplateIncludePath());
    }

    /**
     * Gets the path for the 'thank you' page view template.
     *
     * @return string
     */
    public function getTemplateIncludePath()
    {
        $ds = DIRECTORY_SEPARATOR;

        return __DIR__.$ds.'..'.$ds.'templates'.$ds.'thanks.php';
    }
}
