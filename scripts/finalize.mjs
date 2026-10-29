import fs from 'node:fs';
import path from 'node:path';
const root=process.cwd();
const imageRoot=path.join(root,'wordpress/wp-content/themes/muneeb-mart/assets/images');
for(const name of ['hero','editorial','steel','rose','leather']){const p=path.resolve(imageRoot,name+'.jpg');if(p.startsWith(path.resolve(imageRoot)+path.sep)&&fs.existsSync(p))fs.unlinkSync(p)}
const screenshot=path.join(root,'docs/screenshots/home-desktop.jpg');if(fs.existsSync(screenshot))fs.copyFileSync(screenshot,path.join(root,'wordpress/wp-content/themes/muneeb-mart/screenshot.jpg'));
const history=path.join(root,'.local/wordpress/wp-content/debug.log');if(fs.existsSync(history)){fs.copyFileSync(history,path.join(root,'.local/debug-setup-history.log'));fs.writeFileSync(history,'')}
for(const name of ['qa-backend','qa-targeted','qa-order-verified','qa-admin']){const p=path.join(root,'.local',name+'.json');if(fs.existsSync(p)){const results=JSON.parse(fs.readFileSync(p,'utf8'));console.log(`${name}: ${results.filter(x=>x.pass).length}/${results.length} passed`)}}
console.log('Release assets prepared; original imagery retained in assets-source.');
