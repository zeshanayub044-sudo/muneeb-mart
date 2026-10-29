import fs from 'node:fs';
import path from 'node:path';
const rows=[
 ['Heritage Classic',7500,'leather',['mens-watches','classic','gifts'],'Classic','Leather','Ivory','Silver'],
 ['Midnight Chronograph Style',12900,'steel',['mens-watches','chronograph'],'Chronograph-inspired','Steel','Black','Silver'],
 ['Rose Elegance',8400,'rose',['womens-watches','luxury-inspired','gifts'],'Dress','Steel','Champagne','Rose gold'],
 ['Urban Minimal',6900,'steel',['mens-watches','minimalist'],'Minimalist','Steel','Black','Silver'],
 ['Silver Edition',9800,'steel',['mens-watches','classic'],'Classic','Steel','Black','Silver'],
 ['Executive Black',11500,'leather',['mens-watches','classic'],'Dress','Leather','Ivory','Silver'],
 ['Signature Gold',14900,'rose',['luxury-inspired','gifts','womens-watches'],'Dress','Steel','Champagne','Gold'],
 ['Ocean Sport',10200,'steel',['sports','mens-watches'],'Sports-inspired','Steel','Black','Silver'],
 ['Luna Petite',7900,'rose',['womens-watches','minimalist'],'Minimalist','Steel','Champagne','Rose gold'],
 ['Atelier Dress',9300,'leather',['classic','gifts','mens-watches'],'Dress','Leather','Ivory','Silver'],
 ['Solstice Rose',10900,'rose',['womens-watches','luxury-inspired'],'Dress','Steel','Champagne','Rose gold'],
 ['Nocturne Steel',11800,'steel',['mens-watches','luxury-inspired'],'Classic','Steel','Black','Silver'],
 ['Sunday Edition',6500,'leather',['minimalist','gifts'],'Minimalist','Leather','Ivory','Silver'],
 ['Contour Duo',12500,'steel',['mens-watches','luxury-inspired'],'Classic','Steel','Black','Silver'],
 ['Daybreak Pearl',8900,'rose',['womens-watches','classic'],'Dress','Steel','Champagne','Rose gold'],
 ['Voyager Dial',13200,'steel',['sports','chronograph','mens-watches'],'Chronograph-inspired','Steel','Black','Silver'],
 ['Camden Leather',8200,'leather',['classic','mens-watches'],'Classic','Leather','Ivory','Silver'],
 ['Muse Bracelet',9600,'rose',['womens-watches','gifts'],'Dress','Steel','Champagne','Rose gold'],
 ['Essential 01',5900,'steel',['minimalist'],'Minimalist','Steel','Black','Silver'],
 ['Evening Reserve',15800,'leather',['luxury-inspired','classic','gifts'],'Dress','Leather','Ivory','Silver']
];
const products=rows.map((r,i)=>({sku:`MM-DEMO-${String(i+1).padStart(3,'0')}`,name:`MUNEEB ${r[0]}`,price:r[1],image:r[2],collections:r[3],attributes:{'watch-type':r[4],'strap-material':r[5],'dial-color':r[6],'case-color':r[7]},stock:i===18?0:12+i,variable:i===13,short:`${r[4]} styling. A ${r[5].toLowerCase()}-look strap and ${r[6].toLowerCase()} dial, thoughtfully paired for an individual point of view. Demonstration product; specifications unverified.`,description:`An introduction to the MUNEEB MART design language. ${r[0]} pairs a considered silhouette with an understated palette. Explore the sample design, save it to your wishlist, or add it to a development order. This is a fictional demonstration watch. ${r[4].includes('inspired')?'Styling does not imply verified technical performance.':''}`}));
fs.mkdirSync('wordpress/wp-content/plugins/muneeb-mart-core/data',{recursive:true});
fs.writeFileSync('wordpress/wp-content/plugins/muneeb-mart-core/data/products.json',JSON.stringify(products,null,2)+'\n');
const quote=v=>'"'+String(v).replaceAll('"','""')+'"';
const columns=['Type','SKU','Name','Published','Is featured?','Visibility in catalog','Short description','Description','In stock?','Stock','Regular price','Categories','Attribute 1 name','Attribute 1 value(s)','Attribute 1 visible','Attribute 1 global','Attribute 2 name','Attribute 2 value(s)','Attribute 2 visible','Attribute 2 global'];
const csv=[columns,...products.map((p,i)=>['simple',p.sku,p.name,1,i<4?1:0,'visible',p.short,p.description+' DEMONSTRATION ONLY.',p.stock>0?1:0,p.stock,p.price,p.collections.join(', '),'Strap material',p.attributes['strap-material'],1,0,'Dial color',p.attributes['dial-color'],1,0])].map(row=>row.map(quote).join(',')).join('\r\n');
fs.writeFileSync('demo-content/products.csv',csv);
console.log(`Prepared ${products.length} products. Native setup importer handles images, global attributes, and variable product.`);
