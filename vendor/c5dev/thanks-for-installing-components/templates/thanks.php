<?php 
/**
 * Post-install Single Page View.
 *
 * PHP version 5.4
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5labs.com
 */
defined('C5_EXECUTE') or die('Access Denied.');
?>
<div id="thanksPageContent" class="text-center">
    <div class="row">
        <div class="col-xs-12">
            <h1><?php  echo t('Thanks for installing my add-on!'); ?></h1>
        </div>
        <div id="formOuter" class="col-xs-12 col-md-10 col-md-push-1">
            <div id="formInner">
                <div id="signupContent">
                    <h2><?php  echo t('Can I occasionally keep you up to date?'); ?></h2>
                    <p><?php  echo t('I will'); ?> <strong><?php  echo t('never'); ?></strong> <?php  echo t('send you SPAM, you can leave at anytime'); ?>.</p>
                </div>
                <iframe 
                    id="keepInTouchForm" 
                    data-src="<?php  echo $form_url; ?>" 
                    scrolling="no" 
                    frameborder="no"
                >Please wait, just getting the form...</iframe>
                <a id="backLink" href="<?php  echo URL::to('/dashboard/extend/install') ?>">
                    <?php  echo t('No thanks! Take me back to the add-on manager'); ?>
                </a>
            </div>
        </div>
         <div class="col-xs-12">
            <footer>
                &copy; <?php  echo date('Y'); ?>,  Oliver Green. <a target="_blank" href="https://c5labs.com/privacy-policy"><?php  echo t('Privacy Policy'); ?></a>.
            </footer>
        </div>
    </div>
</div>