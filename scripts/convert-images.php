<?php
$directory=__DIR__.'/../wordpress/wp-content/themes/muneeb-mart/assets/images/';
$sources=['hero'=>'hero-watch','editorial'=>'editorial-leather','steel'=>'product-steel','rose'=>'product-rose','leather'=>'editorial-leather'];
foreach($sources as $name=>$original){$image=imagecreatefromjpeg(__DIR__.'/../assets-source/'.$original.'.jpg');imagewebp($image,$directory.$name.'.webp',88);imagedestroy($image);echo $name." converted\n";}
