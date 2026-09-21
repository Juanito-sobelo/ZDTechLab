document.addEventListener("DOMContentLoaded",()=>{
const btn=document.querySelector(".boton-menu");
const menu=document.querySelector(".panel__menu");
if(btn&&menu){btn.addEventListener("click",()=>{
const ab=menu.classList.toggle("abierto");
btn.setAttribute("aria-expanded",ab?"true":"false");});}
// Tabla productos desde datos-prueba (prototipo dias 7-8; en PHP se llena desde MySQL)
const tbody=document.querySelector("#tabla-productos tbody");
if(tbody&&typeof productos!=="undefined"){
const pintar=(lista)=>{tbody.replaceChildren(...lista.map(p=>{
const tr=document.createElement("tr");
tr.innerHTML=`<td>${p.nombre}</td><td>${p.categoria}</td><td>${moneda.format(p.precio)}</td><td>${p.stock}</td><td><button class="boton-mini" data-accion="editar" data-id="${p.id}">Editar</button> <button class="boton-mini boton-peligro" data-accion="eliminar" data-id="${p.id}">Eliminar</button></td>`;
return tr;});};
pintar(productos);
tbody.addEventListener("click",(e)=>{
const b=e.target.closest("button[data-accion]");if(!b)return;
const {accion,id}=b.dataset;
if(accion==="editar")abrirFormulario(Number(id));
if(accion==="eliminar")confirmarBorrado(Number(id));});
const busc=document.querySelector("#buscador");
if(busc){busc.addEventListener("input",()=>{
const t=busc.value.toLowerCase();
pintar(productos.filter(p=>p.nombre.toLowerCase().includes(t)));});}
}
// Validacion formulario producto
const form=document.querySelector("#form-producto");
if(form){form.addEventListener("submit",(e)=>{
const nom=form.elements.nombre,pre=form.elements.precio,sto=form.elements.stock,cat=form.elements.categoria_id;
nom.setCustomValidity(nom.value.trim().length<3?"El nombre debe tener al menos 3 caracteres.":"");
pre.setCustomValidity(Number(pre.value)<=0?"El precio debe ser mayor que cero.":"");
sto.setCustomValidity(!Number.isInteger(Number(sto.value))||Number(sto.value)<0?"Stock entero no negativo.":"");
cat.setCustomValidity(!cat.value?"Seleccione una categoria.":"");
if(!form.checkValidity()){e.preventDefault();form.reportValidity();}
});}
});
function abrirFormulario(id){console.log("editar",id);}
function confirmarBorrado(id){if(confirm("Desactivar producto "+id+"?"))console.log("eliminar",id);}
