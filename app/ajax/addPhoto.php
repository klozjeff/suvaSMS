<?php
session_start();
define('IS_AJAX','true');
require('../core/config/config.php');
require('../core/config/config-lang.php');
require('../core/system.php');
?>
<script src="<?php echo $domain?>/vendor/bootstrap-filestyle/src/bootstrap-filestyle.js"></script>
<div class="row">
<div class="col-md-5">
<div class="form-group has-feedback">
<label for="photo"><?php echo $lang['Photo']?> #<?php echo $_GET['c']?></label>
<input type="file" name="photo[]" data-classbutton="btn btn-default" data-classinput="form-control inline" class="form-control filestyle" required>
</div>
</div>
</div>