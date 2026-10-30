<?php
/** Creates a private consistent SQL export; no credentials in arguments or output. */
if(PHP_SAPI!=='cli')exit(1);
require __DIR__.'/../.local/wordpress/wp-load.php';
$root=dirname(__DIR__);$directory=$root.'/.local/backups/export-'.gmdate('Y-m-d-His');
if(!wp_mkdir_p($directory))throw new RuntimeException('Cannot create private backup directory.');
$executable=$root.'/.local/mariadb-11.4.13-winx64/bin/mariadb-dump.exe';
$command=[$executable,'--no-defaults','--host=127.0.0.1','--port=3308','--user='.DB_USER,'--single-transaction','--skip-lock-tables','--hex-blob',DB_NAME];
$output=fopen($directory.'/database.sql','wb');$errors=fopen($directory.'/export-errors.log','wb');$environment=getenv();$environment['MYSQL_PWD']=DB_PASSWORD;
$process=proc_open($command,[0=>['pipe','r'],1=>$output,2=>$errors],$pipes,$root,$environment,['bypass_shell'=>true,'create_no_window'=>true]);
if(!is_resource($process))throw new RuntimeException('Cannot start export.');fclose($pipes[0]);$status=proc_close($process);fclose($output);fclose($errors);
if($status!==0)throw new RuntimeException('Database export failed; inspect private export log.');
copy(ABSPATH.'wp-config.php',$directory.'/wp-config.private.php');
$uploads=ABSPATH.'wp-content/uploads';$copied=0;
if(is_dir($uploads))foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($uploads,FilesystemIterator::SKIP_DOTS),RecursiveIteratorIterator::SELF_FIRST) as $entry){
    if($entry->isLink())continue;$relative=substr($entry->getPathname(),strlen($uploads)+1);$target=$directory.'/uploads/'.$relative;
    if($entry->isDir()){if(!wp_mkdir_p($target))throw new RuntimeException('Media backup directory failed.');}
    else{if(!wp_mkdir_p(dirname($target))||!copy($entry->getPathname(),$target))throw new RuntimeException('Media backup failed.');$copied++;}
}
echo 'Private SQL, configuration and '.$copied.' media files exported: '.basename($directory).". Keep it outside GitHub and public web roots.\n";
