<?php
/**
 * Created by PhpStorm.
 * User: eygle
 * Date: 8/18/16
 * Time: 10:09 AM
 */

require_once __DIR__ . "/autoload.php";

$pageTitle = I18n::get("title");
$page = "home";

include_once __DIR__ . "/template/top.php";
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo I18n::get("tuto_title");?></h1>
    </div>
    <p class="lead"><?php echo I18n::get("tuto_desc");?></p>
    <h2><?php echo I18n::get("demands");?></h2>
    <p><?php echo I18n::get("tuto_demands");?></p>
    <h2><?php echo I18n::get("offers");?></h2>
    <p><?php echo I18n::get("tuto_offers");?></p>
</div>

<?php
include_once __DIR__ . "/template/bottom.php";
?>

