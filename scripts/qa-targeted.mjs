import fs from 'node:fs';
const base='http://127.0.0.1:8088',cookies=new Map(),checks=[],fixtures=JSON.parse(fs.readFileSync('.local/qa-fixtures.json','utf8'));
const decode=s=>s.replaceAll('&amp;','&').replaceAll('&#038;','&').replaceAll('&#38;','&');
async function request(url,options={}){const response=await fetch(new URL(url,base),{...options,redirect:'manual',headers:{Cookie:[...cookies].map(([k,v])=>`${k}=${v}`).join('; '),...options.headers},signal:AbortSignal.timeout(60000)});for(const c of response.headers.getSetCookie()){const pair=c.split(';')[0],i=pair.indexOf('=');cookies.set(pair.slice(0,i),pair.slice(i+1))}if(response.status>=300&&response.status<400&&response.headers.get('location'))return request(response.headers.get('location'));return {status:response.status,text:await response.text()}}
const post=(url,data)=>request(url,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams(data)});
const check=(test,pass)=>{checks.push({test,pass:Boolean(pass)});console.log(`${pass?'PASS':'FAIL'}: ${test}`)};
try{
 let r=await request('/privacy-policy/');check('Published editable privacy policy',r.status===200&&r.text.includes('Information we collect'));
 r=await request('/?s=Heritage&post_type=product');check('Dedicated product search returns exactly one watch',(r.text.match(/class="woocommerce-loop-product__title"/g)||[]).length===1&&r.text.includes('>MUNEEB Heritage Classic</h2>'));
 r=await request('/catalog/');const titles=[...r.text.matchAll(/class="woocommerce-loop-product__title">([^<]+)</g)].map(x=>x[1]);check('Default catalog order matches newest-arrivals selector',titles[0]==='MUNEEB Heritage Classic');
 await post('/?wc-ajax=add_to_cart',{product_id:fixtures.simple,quantity:1});r=await request('/cart/');let remove=r.text.match(/href="([^"]*remove_item[^"]*)"/)?.[1];check('Native cart removal link has valid encoded URL',Boolean(remove));if(remove){r=await request(decode(remove));check('Cart item removal works',!r.text.includes('woocommerce-cart-form')&&(r.text.includes('empty')||r.text.includes('currently empty')))}
 r=await post('/product/muneeb-contour-duo/',{'add-to-cart':fixtures.variable,product_id:fixtures.variable,variation_id:fixtures.variations[1],attribute_finish:'Gold',quantity:1});r=await request('/cart/');check('Gold variation adds with correct selection and price',r.text.includes('Gold')&&r.text.includes('13,000'));remove=r.text.match(/href="([^"]*remove_item[^"]*)"/)?.[1];if(remove)await request(decode(remove));
 r=await post('/?wc-ajax=add_to_cart',{product_id:fixtures.out_of_stock,quantity:1});check('Out-of-stock product cannot be added',JSON.parse(r.text).error===true);
 r=await request('/wp-content/debug.log');check('Debug logs are protected',r.status===404);
 r=await request('/product/muneeb-heritage-classic/');check('Demo products emit no unverified Product schema',!r.text.includes('"@type":"Product"'));
}catch(e){check('Targeted QA completed',false);console.error(e.stack)}
fs.writeFileSync('.local/qa-targeted.json',JSON.stringify(checks,null,2));if(checks.some(x=>!x.pass))process.exitCode=1;
