<?php
$data = $_POST['data'];
if(get_magic_quotes_gpc())
{
	$data = stripslashes($data);
}
?>
<html>
	<body>
		<?php print $data; ?>
	</body>
</html>
