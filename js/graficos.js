const PALETA=["#39A900","#15506B","#F08A00","#8FD46A","#B0209E","#00A0C6"];
const fmtCOP=new Intl.NumberFormat("es-CO",{style:"currency",currency:"COP",maximumFractionDigits:0});
let gV,gC,gL;
async function dibujarGraficos(){
try{
const d=await fetch("api/graficos.php",{credentials:"same-origin"}).then(r=>r.json());
if(typeof Chart==="undefined")return;
gV?.destroy();
gV=new Chart(document.getElementById("g-ventas"),{type:"bar",
data:{labels:d.ventasMes.etiquetas,datasets:[{label:"Ventas del mes",data:d.ventasMes.valores,backgroundColor:PALETA[0],borderRadius:2}]},
options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>fmtCOP.format(c.parsed.y)}}},scales:{y:{beginAtZero:true,ticks:{callback:v=>(v/1000000)+" M"}}}}});
gC?.destroy();
gC=new Chart(document.getElementById("g-categorias"),{type:"doughnut",
data:{labels:d.categorias.etiquetas,datasets:[{data:d.categorias.valores,backgroundColor:PALETA}]},
options:{responsive:true,maintainAspectRatio:false,cutout:"62%",plugins:{legend:{position:"right"},tooltip:{callbacks:{label:c=>`${c.label}: ${fmtCOP.format(c.parsed)}`}}}}});
if(document.getElementById("g-stock")){
gL?.destroy();
gL=new Chart(document.getElementById("g-stock"),{type:"line",
data:{labels:d.stock.etiquetas,datasets:[{label:"Stock critico",data:d.stock.valores,borderColor:PALETA[2],backgroundColor:PALETA[2],tension:.3}]},
options:{responsive:true,maintainAspectRatio:false}});}
}catch(e){console.warn("graficos",e);}}
document.addEventListener("DOMContentLoaded",dibujarGraficos);
