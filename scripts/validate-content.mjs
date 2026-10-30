import fs from 'node:fs';
const base='wordpress/wp-content/plugins/muneeb-mart-core/data/';
const products=JSON.parse(fs.readFileSync(base+'products.json','utf8'));
const articles=['journal-guides.json','journal-care.json'].flatMap(file=>JSON.parse(fs.readFileSync(base+file,'utf8')));
const pages=JSON.parse(fs.readFileSync(base+'support-pages.json','utf8'));
const assert=(condition,message)=>{if(!condition)throw Error(message)};
assert(products.length===20&&new Set(products.map(p=>p.sku)).size===20,'Expected 20 unique demo SKUs');
assert(articles.length===8&&new Set(articles.map(a=>a.slug)).size===8,'Expected eight unique editorial slugs');
for(const article of articles){
 for(const key of ['slug','title','excerpt','seo_title','description','category','image','image_alt','content'])assert(typeof article[key]==='string'&&article[key].trim(),'Missing '+key+' in '+article.slug);
 const words=article.content.replace(/<[^>]*>/g,' ').match(/\b[\w’'-]+\b/g)||[];
 assert(words.length>=1000,'Article too short: '+article.slug);
 assert(fs.existsSync('wordpress/wp-content/themes/muneeb-mart/assets/images/'+article.image+'.webp'),'Missing editorial image: '+article.slug);
 assert(article.content.includes('<h2>')&&article.content.includes('{{home}}/'),'Missing article structure or internal links: '+article.slug);
 assert(Array.isArray(article.tags)&&Array.isArray(article.collections),'Missing article classification: '+article.slug);
}
assert(Object.keys(pages).length===8,'Expected eight support pages');
console.log('Content validated: 20 unique demo products, eight complete articles and eight support pages.');
