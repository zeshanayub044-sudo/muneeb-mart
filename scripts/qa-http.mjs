import fs from 'node:fs';
const base='http://127.0.0.1:8088',checks=[],cookies=new Map();
function check(test,pass,detail=''){checks.push({test,pass:Boolean(pass),detail});console.log((pass?'PASS':'FAIL')+': '+test+(detail?' — '+detail:''))}
async function request(url,options={}){url=new URL(url,base);const start=performance.now();const response=await fetch(url,{...options,headers:{Cookie:[...cookies].map(([k,v])=>`${k}=${v}`).join('; '),...options.headers},redirect:'manual',signal:AbortSignal.timeout(60000)});for(const value of response.headers.getSetCookie()){const pair=value.split(';')[0],index=pair.indexOf('=');cookies.set(pair.slice(0,index),pair.slice(index+1))}if(response.status>=300&&response.status<400&&response.headers.get('location'))return request(response.headers.get('location'),{method:'GET'});const text=await response.text();return {status:response.status,text,response,ms:Math.round(performance.now()-start)}}
const post=(url,data)=>request(url,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams(data)});
const count=html=>(html.match(/class="woocommerce-loop-product__title"/g)||[]).length;
const getNonce=(html,name)=>html.match(new RegExp('name="'+name+'" value="([^"]+)"'))?.[1];
try{
 const home=await request('/');check('Homepage serves custom theme',home.status===200&&home.text.includes('Timeless elegance. Every second.'),`${home.ms} ms, ${home.text.length} HTML chars`);
 check('Development store is noindex',home.text.includes('noindex'));
 const urls=['/catalog/','/collections/','/about-us/','/contact-us/','/cart/','/checkout/','/my-account/','/wishlist/','/privacy-policy/','/terms-conditions/','/shipping-policy/','/return-refund-policy/','/frequently-asked-questions/'];
 for(const url of urls){const r=await request(url);check('Page '+url,r.status===200&&!r.text.includes('critical error'),`${r.ms} ms`)}
 const catalog=await request('/catalog/');check('Catalog paginates 20 products',count(catalog.text)===12&&catalog.text.includes('page-numbers'));
 for(const slug of ['mens-watches','womens-watches','classic','minimalist','chronograph','luxury-inspired','sports','new-arrivals','gifts','executive','budget']){const r=await request('/collections/'+slug+'/');check('Collection '+slug,r.status===200&&count(r.text)>0)}
 const filtered=await request('/catalog/?filter_strap-material=leather');check('Strap filter applies to real WooCommerce products',count(filtered.text)>0&&count(filtered.text)<12&&filtered.text.includes('MUNEEB Heritage Classic'));
 const price=await request('/catalog/?min_price=14000&max_price=16000');check('Price range filter',count(price.text)===2,`${count(price.text)} matches`);
 const combined=await request('/catalog/?filter_strap-material=steel&filter_dial-color=black&in_stock=1');check('Combined attribute and stock filters',count(combined.text)>0&&!combined.text.includes('>MUNEEB Essential 01</h2>'));
 const sort=await request('/catalog/?orderby=price');check('Price sorting low to high',sort.text.indexOf('>MUNEEB Essential 01</h2>')<sort.text.indexOf('>MUNEEB Sunday Edition</h2>'));
 const search=await request('/?s=Heritage&post_type=product');check('Product search',count(search.text)===1&&search.text.includes('MUNEEB Heritage Classic'));
 const empty=await request('/catalog/?s=nonexistent-watch-qa&post_type=product');check('Helpful empty search state',empty.text.includes('No watches match')||empty.text.includes('No products were found'));
 const missing=await request('/no-such-watch-page-qa/');check('Proper branded 404',missing.status===404&&missing.text.includes('find your way back'));
 const denied=await request('/wp-config.php');check('Configuration not served',denied.status===404&&!denied.text.includes('DB_PASSWORD'));
 const api=await request('/wp-json/wc/store/v1/products?per_page=100');const products=JSON.parse(api.text);check('WooCommerce Store API returns 20 products',Array.isArray(products)&&products.length===20);
 const fixtures=JSON.parse(fs.readFileSync('.local/qa-fixtures.json','utf8'));
 for(const product of products){const page=await request(product.permalink);check('Product '+product.name,page.status===200&&page.text.includes('single_add_to_cart_button')||page.status===200&&!product.is_in_stock)}
 const contact=await request('/contact-us/');const nonce=getNonce(contact.text,'mm_nonce');
 const invalid=await post('/wp-admin/admin-ajax.php',{action:'mm_contact',mm_nonce:nonce,consent:'1',email:'bad',name:'QA',subject:'Product question',message:'A sample product enquiry for QA.'});check('Contact rejects invalid email',invalid.status===400);
 const csrf=await post('/wp-admin/admin-ajax.php',{action:'mm_contact',mm_nonce:'bad',consent:'1',email:'qa@example.invalid'});check('Forms reject invalid nonce',csrf.status===403);
 const form=await post('/wp-admin/admin-ajax.php',{action:'mm_contact',mm_nonce:nonce,consent:'1',email:'qa@example.invalid',name:'Development QA',subject:'Product question',message:'This is a local development enquiry for testing. No reply is required.'});check('Contact enquiry saved',JSON.parse(form.text).success===true);
 const subscribeNonce=home.text.match(/name="action" value="mm_subscribe"[\s\S]*?name="mm_nonce" value="([^"]+)"/)?.[1];
 const signup=await post('/wp-admin/admin-ajax.php',{action:'mm_subscribe',mm_nonce:subscribeNonce,consent:'1',email:'newsletter-qa@example.invalid'});check('Newsletter consent saved',JSON.parse(signup.text).success===true);
 const bot=await post('/wp-admin/admin-ajax.php',{action:'mm_subscribe',mm_nonce:subscribeNonce,consent:'1',email:'bot@example.invalid',website:'bot'});check('Honeypot rejects spam',bot.status===400);
 const noConsent=await post('/wp-admin/admin-ajax.php',{action:'mm_subscribe',mm_nonce:subscribeNonce,email:'missing-consent@example.invalid'});check('Newsletter requires consent',noConsent.status===400);
 const add=await post('/?wc-ajax=add_to_cart',{product_id:fixtures.simple,quantity:'1'});const added=JSON.parse(add.text);check('Native WooCommerce add-to-cart',Boolean(added.fragments)&&!added.error);
 const cart=await request('/cart/');check('Cart contains selected product and subtotal',cart.text.includes('MUNEEB Heritage Classic')&&cart.text.includes('7,500'));
 const key=cart.text.match(/name="cart\[([^\]]+)\]\[qty\]"/)?.[1];const cartNonce=getNonce(cart.text,'woocommerce-cart-nonce');
 const updated=await post('/cart/',{[`cart[${key}][qty]`]:'2','woocommerce-cart-nonce':cartNonce,update_cart:'Update cart'});check('Cart quantity updates and recalculates',updated.text.includes('15,000'));
 const checkout=await request('/checkout/');check('Development gateway visible at checkout',checkout.text.includes('mm_test'));
 const checkoutNonce=getNonce(checkout.text,'woocommerce-process-checkout-nonce');
 const required=await post('/?wc-ajax=checkout',{'woocommerce-process-checkout-nonce':checkoutNonce,payment_method:'mm_test',terms:'1','terms-field':'1'});check('Checkout rejects missing required fields',JSON.parse(required.text).result==='failure');
 const checkoutBody={'woocommerce-process-checkout-nonce':checkoutNonce,billing_first_name:'Development',billing_last_name:'QA',billing_country:'PK',billing_address_1:'Sample test address',billing_city:'Lahore',billing_state:'PB',billing_postcode:'54000',billing_phone:'03000000000',billing_email:'qa@example.invalid',payment_method:'mm_test','shipping_method[0]':checkout.text.match(/name="shipping_method\[0\]"[^>]*value="([^"]+)"/)?.[1]||'flat_rate:1',terms:'1','terms-field':'1',order_comments:'LOCAL QA TEST — do not fulfill.'};
 const order=await post('/?wc-ajax=checkout',checkoutBody);let placed;try{placed=JSON.parse(order.text)}catch{fs.writeFileSync('.local/checkout-error.html',order.text);throw new Error('Checkout returned non-JSON')}
 check('Real development order placed through WooCommerce checkout',placed.result==='success',placed.result==='success'?'Saved order; no payment collected.':String(placed.messages).replace(/<[^>]*>/g,'').slice(0,400));
 if(placed.redirect){const confirmation=await request(placed.redirect);check('Confirmation displays real test order',confirmation.status===200&&confirmation.text.includes('Development test order.')&&confirmation.text.includes('MUNEEB Heritage Classic'));fs.writeFileSync('.local/qa-order.json',JSON.stringify({redirect:placed.redirect,product_id:fixtures.simple,quantity:2},null,2))}
 const emptyCart=await request('/cart/');check('Cart clears after order',!emptyCart.text.includes('woocommerce-cart-form')&&emptyCart.text.includes('empty'));
 const readd=await post('/?wc-ajax=add_to_cart',{product_id:fixtures.simple,quantity:1});const removable=await request('/cart/');const removeUrl=removable.text.match(/href="([^"]*remove_item[^"]*)"/)?.[1]?.replaceAll('&amp;','&').replaceAll('&#038;','&').replaceAll('&#38;','&');if(removeUrl){const removed=await request(removeUrl);check('Remove item produces empty cart',removed.text.includes('empty')&&!removed.text.includes('woocommerce-cart-form'))}else check('Cart removal link',false);
}catch(error){check('QA execution completed',false,error.stack||String(error))}
fs.writeFileSync('.local/qa-http.json',JSON.stringify(checks,null,2));console.log(`${checks.filter(x=>x.pass).length}/${checks.length} checks passed.`);if(checks.some(x=>!x.pass))process.exitCode=1;
