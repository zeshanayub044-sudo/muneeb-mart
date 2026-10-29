import fs from 'node:fs';
import path from 'node:path';
import net from 'node:net';
import {spawn,spawnSync} from 'node:child_process';
import {fileURLToPath} from 'node:url';
const root=path.resolve(path.dirname(fileURLToPath(import.meta.url)),'..');
const local=path.join(root,'.local');
const args=process.argv.slice(2);
const portOpen=port=>new Promise(resolve=>{const socket=net.connect({host:'127.0.0.1',port});socket.once('connect',()=>{socket.destroy();resolve(true)});socket.once('error',()=>resolve(false));socket.setTimeout(1500,()=>{socket.destroy();resolve(false)})});
const php=path.join(local,'php.exe');
if(args.includes('restart-web')){
 const pidPath=path.join(local,'web-server.pid');
 if(!fs.existsSync(pidPath))throw new Error('Open the local store first so its router records the owned server PID.');
 const pid=Number(fs.readFileSync(pidPath,'utf8'));if(!Number.isInteger(pid)||pid<1)throw new Error('Invalid owned server PID.');
 process.kill(pid);console.log('Restarting the project’s local PHP server.');
 let remaining=await portOpen(8088);for(let i=0;remaining&&i<10;i++){await new Promise(r=>setTimeout(r,100));remaining=await portOpen(8088)}
 if(remaining)throw new Error('Port remains in use; another process was not stopped.');
 args.push('start');
}
if(args.includes('status')){for(const p of [3308,8088])console.log(`${p}: ${await portOpen(p)?'listening':'stopped'}`);for(const name of ['database-error.log','server-error.log','wordpress/wp-content/debug.log']){const p=path.join(local,name);if(fs.existsSync(p))console.log(name,fs.readFileSync(p,'utf8').slice(-2400))}process.exit(0)}
if(args.includes('sync')){for(const relative of ['themes/muneeb-mart','plugins/muneeb-mart-core','plugins/woocommerce']){const target=path.join(local,'wordpress/wp-content',relative);if(relative==='plugins/woocommerce'&&fs.existsSync(path.join(target,'woocommerce.php')))continue;fs.cpSync(path.join(root,'wordpress/wp-content',relative),target,{recursive:true});}console.log('Local source synchronized.')}
if(args.includes('start')){
 const dbPort=await portOpen(3308),webPort=await portOpen(8088),state=fs.existsSync(path.join(local,'node-processes.json'))?JSON.parse(fs.readFileSync(path.join(local,'node-processes.json'),'utf8')):{};
 if(!dbPort){const log=fs.openSync(path.join(local,'database.log'),'a'),error=fs.openSync(path.join(local,'database-error.log'),'a');const db=spawn(path.join(local,'mariadb-11.4.13-winx64/bin/mariadbd.exe'),['--no-defaults','--datadir='+path.join(local,'db'),'--port=3308','--bind-address=127.0.0.1','--console'],{cwd:root,detached:true,windowsHide:true,stdio:['ignore',log,error]});state.database=db.pid;db.unref();console.log('Local MariaDB started.')}
 if(!webPort){const log=fs.openSync(path.join(local,'server.log'),'a'),error=fs.openSync(path.join(local,'server-error.log'),'a');const server=spawn(php,['-S','127.0.0.1:8088','-t',path.join(local,'wordpress'),path.join(root,'scripts/router.php')],{cwd:root,detached:true,windowsHide:true,stdio:['ignore',log,error]});state.php=server.pid;server.unref();console.log('Local WordPress server started.')}
 fs.writeFileSync(path.join(local,'node-processes.json'),JSON.stringify(state));
}
if(args.includes('lint')){const walk=d=>fs.readdirSync(d,{withFileTypes:true}).flatMap(e=>e.isDirectory()?walk(path.join(d,e.name)):e.name.endsWith('.php')?[path.join(d,e.name)]:[]);let failures=0;for(const p of [...walk(path.join(root,'wordpress/wp-content/themes/muneeb-mart')),...walk(path.join(root,'wordpress/wp-content/plugins/muneeb-mart-core')),...walk(path.join(root,'scripts'))]){const result=spawnSync(php,['-l',p],{cwd:root,encoding:'utf8',windowsHide:true});console.log(result.stdout||result.stderr);if(result.status)failures++}if(failures)process.exit(1)}
if(args.includes('versions')){console.log(fs.readFileSync(path.join(local,'wordpress/wp-includes/version.php'),'utf8').slice(0,700));console.log(fs.readFileSync(path.join(root,'wordpress/wp-content/plugins/woocommerce/woocommerce.php'),'utf8').slice(0,1400))}
