<?php 
/**
 * Post-install Single Page View Stub.
 *
 * PHP version 5.4
 *
 * @author   Oliver Green <oliver@c5dev.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     https://c5dev.com/add-ons/twitter-feed
 */
defined('C5_EXECUTE') or die('Access Denied.');

        $fs = new \Illuminate\Filesystem\Filesystem(); 
 $fs->requireOnce($template_path);
