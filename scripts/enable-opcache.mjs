import fs from 'node:fs';
const path='.local/php.ini';let ini=fs.readFileSync(path,'utf8');
if(!fs.existsSync('.local/ext/php_opcache.dll'))throw new Error('Bundled opcode cache extension is not available.');
if(!ini.includes('zend_extension=opcache')){ini+='\nzend_extension=opcache\nopcache.enable=1\nopcache.enable_cli=1\nopcache.validate_timestamps=1\nopcache.revalidate_freq=0\nopcache.memory_consumption=128\n';fs.writeFileSync(path,ini)}
console.log('Portable PHP opcode cache configured. Restart the owned local PHP server to apply.');
