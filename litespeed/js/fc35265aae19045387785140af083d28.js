import{SimpleIndexedDB}from "../../../indexedDB/main";import{wishlistElement}from "./wishlistElement";const localWishlist=new SimpleIndexedDB('localWishlistDB','wishlistStore');await localWishlist.init();async function addToLocalWishlist(element){if(!(element instanceof wishlistElement))
return{success:!1,error:"Elemento non valido"};if(!(localWishlist instanceof SimpleIndexedDB))
return{success:!1,error:"Si è verificato un errore con il database"};const all=await localWishlist.getAll();if(all.some(el=>el.id===element.id))
return{success:!1,error:"Elemento già presente in wishlist"};try{await localWishlist.create(element);return{success:!0}}catch(error){return{success:!1,error:error.message||String(error)}}}
async function removeFromLocalWishlist(elementId){if(typeof elementId!=="string"&&typeof elementId!=="number")
return{success:!1,error:"ID non valido"};if(!elementId)
return{success:!1,error:"ID mancante o vuoto"};if(!(localWishlist instanceof SimpleIndexedDB))
return{success:!1,error:"Si è verificato un errore con il database"};try{await localWishlist.delete(elementId);return{success:!0}}catch(error){return{success:!1,error:error.message||String(error)}}}
async function getAllLocalWishlist(){try{const items=await localWishlist.getAll();return{success:!0,items}}catch(error){return{success:!1,error:error.message||String(error)}}}
async function pushAllWishlistFromServer(userToken){if(!userToken)
return{success:!1,error:"I parametri non sono validi"};const allElements=await getAllLocalWishlist();if(allElements.success===!1)
return{success:!1,error:allElements.error};const items=allElements.items;if(items.length===0)
return{success:!1,error:"La wishlist locale è vuota"};let results=[];for(const item of items){try{const response=await fetch('/api/v1/wishlist/add',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({user_token:userToken,element_id:item.id})});const data=await response.json();results.push({id:item.id,success:response.ok,serverResponse:data})}catch(error){results.push({id:item.id,success:!1,error:error.message||String(error)})}}
return{success:!0,results}}
async function isElementInLocalWishlist(elementId){const{items}=await getAllLocalWishlist();return items.some(el=>String(el.id)===String(elementId))}
window.addToLocalWishlist=addToLocalWishlist;window.removeFromLocalWishlist=removeFromLocalWishlist;window.getAllLocalWishlist=getAllLocalWishlist;window.pushAllWishlistFromServer=pushAllWishlistFromServer;window.isElementInLocalWishlist=isElementInLocalWishlist
;