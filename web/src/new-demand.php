<?php
/**
 * Created by PhpStorm.
 * User: eygle
 * Date: 8/23/16
 * Time: 3:16 PM
 */

require_once __DIR__ . "/autoload.php";

$pageTitle = I18n::get("title");
$page = "demands";

include_once __DIR__ . "/template/top.php";
?>

    <div id="home" class="container">
        <div class="page-header">
            <h1><?php echo I18n::get("new_demand_title");?></h1>
        </div>

        <form>
            <div class="form-group">
                <label for="title"><?php echo I18n::get("new_demand_form_title");?></label>
                <input type="text" class="form-control" id="title" placeholder="<?php echo I18n::get("placeholder_title");?>">
            </div>
            <div class="form-group">
                <label for="category"><?php echo I18n::get("new_demand_form_category");?></label>
                <select>
                    <?php foreach (I18n::get("categories") as $cat) {
                        echo "<option value=\"".$cat["id"]."\">".$cat["name"]."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="desc"><?php echo I18n::get("new_demand_form_desc");?></label>
                <textarea class="form-control" id="desc" placeholder="<?php echo I18n::get("new_demand_form_desc_placeholder");?>"></textarea>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>

<?php
include_once __DIR__ . "/template/bottom.php";
