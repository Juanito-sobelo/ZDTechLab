const moneda=new Intl.NumberFormat("es-CO",{style:"currency",currency:"COP",maximumFractionDigits:0});
const masCaro=productos.reduce((a,b)=>b.precio>a.precio?b:a);
const porCategoria=productos.reduce((acc,p)=>{acc[p.categoria]=(acc[p.categoria]??0)+p.stock;return acc;},{});
const stockBajo=productos.filter(p=>p.stock<5);
const promedio=productos.map(p=>p.precio).reduce((a,b)=>a+b,0)/productos.length;
const valorInventario=productos.filter(p=>p.stock>0).map(p=>p.precio*p.stock).reduce((a,b)=>a+b,0);
console.table(productos.filter(p=>p.stock>0));
console.log("Mas caro:",masCaro.nombre,moneda.format(masCaro.precio));
console.log("Por categoria:",porCategoria);
console.log("Stock bajo:",stockBajo.map(p=>p.nombre));
console.log("Promedio:",moneda.format(promedio));
console.log("Valor inventario:",moneda.format(valorInventario));
// map vs forEach: map DEVUELVE arreglo nuevo, forEach solo itera (devuelve undefined)
const nombresMap=productos.map(p=>p.nombre); // ['Teclado',...]
productos.forEach(p=>console.log(p.nombre)); // solo imprime, no crea arreglo
