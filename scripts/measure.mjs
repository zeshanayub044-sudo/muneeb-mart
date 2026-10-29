import fs from 'node:fs';
const results=[];
for(let i=0;i<(process.argv.includes('--warm')?3:1);i++){
 const start=performance.now();const response=await fetch('http://127.0.0.1:8088/',{signal:AbortSignal.timeout(60000)});const html=await response.text();const ms=Math.round(performance.now()-start);results.push({sample:i+1,status:response.status,milliseconds:ms,html_bytes:Buffer.byteLength(html)});console.log(`Homepage sample ${i+1}: HTTP ${response.status}, ${ms} ms`);
}
fs.writeFileSync('.local/performance-'+(process.argv.includes('--warm')?'cached':'baseline')+'.json',JSON.stringify(results,null,2));
