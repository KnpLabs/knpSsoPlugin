<div id="login">
  <div id="secure">
    <h3><?php echo __("You don't have sufficient permissions to access this page.") ?></h3>
    <ul>
      <li><a href="<?php echo url_for('user/logout'); ?>"><?php echo __("Click here"); ?></a> <?php echo __("to identify with another account.") ?></li>
      <li><a href="#" onclick="history.go(-1);"><?php echo __("Click here"); ?></a> <?php echo __("to go back to the previous page.") ?></li>
    </ul>
  </div>
</div>
