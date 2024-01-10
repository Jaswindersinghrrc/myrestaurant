
<?php
  require 'config.php';
  require 'database.php';
  $g_title = BLOG_NAME . ' - Register';
  $g_page = 'register';
  require 'header.php';
  require 'menu.php';
?>
<div id="all_blogs">
<table width="500" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<form name="form1" method="post" action="process_register.php">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
<tr>
<td colspan="3"><strong>Member Register </strong></td>
</tr>
<tr>
<td width="78">Username</td>
<td width="6">:</td>
<td><input name="username" type="text"id="username" value="<?=$myusername?>" required></td>
<tr>
<td>E-mail</td>
<td>:</td>
<td><input name="email" type="text" id="email" value="<?=$myemail?>" required></td>
</tr>
<tr>
<td>Password</td>
<td>:</td>
<td><input name="password" type="password" id="password"></td>
</tr>
<tr>
<td>Verify Password</td>
<td>:</td>
<td><input name="password2" type="password" id="password2" required></td>
</tr>

<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><input type="submit" name="Submit" value="Register"></td>
</tr>
</table>
</td>
</form>
</tr>
</table>
</div>
<?php
  require 'footer.php';
?>


