// public/js/app.js
async function postJSON(url, data){
const res = await fetch(url, {
method: 'POST',
headers: {'Content-Type':'application/json'},
body: JSON.stringify(data),
credentials: 'same-origin'
});
return res.json();
}


// like (example element should call likeUser(userId))
async function likeUser(targetId){
const res = await postJSON('/socialapp/user/like.php', {target_id: targetId});
if(res.ok){
alert(res.message || 'Polubiono');
} else {
alert(res.error || 'Błąd');
}
}


// poll notifications every 15s
setInterval(async ()=>{
try{
const r = await fetch('/socialapp/user/notifications_fetch.php', {credentials:'same-origin'});
const j = await r.json();
if(j && j.count && j.count>0){
// prosta wizualizacja: zmiana tytułu
document.title = '('+j.count+') SocialApp';
} else {
document.title = 'SocialApp';
}
}catch(e){/*ignore*/}
},15000);