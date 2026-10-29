<?php
if(PHP_SAPI!=='cli')exit;
$db=new mysqli('127.0.0.1','root','','muneeb_mart',3308);
foreach(['SELECT option_name,option_value FROM mm_options WHERE option_name IN ("siteurl","active_plugins","template","woocommerce_version")','SELECT post_type,COUNT(*) AS total FROM mm_posts GROUP BY post_type'] as $sql){$result=$db->query($sql);if($result)foreach($result as $row)echo json_encode($row)."\n";}
