const $=(s,r=document)=>r.querySelector(s);
const $$=(s,r=document)=>[...r.querySelectorAll(s)];
const KEY='laundryku_state_v2';
const seed={
  users:[
    {name:'Raka Pratama',email:'raka@laundryku.id',password:'laundry123',role:'customer'},
    {name:'Nadia Putri',email:'nadia@laundryku.id',password:'laundry123',role:'agent'},
    {name:'Admin Laundryku',email:'admin@laundryku.id',password:'laundry123',role:'admin'}
  ],
  session:null,
  orders:[
    {id:'LDY-2408',customer:'Raka Pratama',shop:'Fresh Wash',service:'Cuci + Setrika',weight:'4,2 kg',status:'Disetrika',total:42000,date:'10 Agu 2026'},
    {id:'LDY-2396',customer:'Raka Pratama',shop:'Laundry Corner',service:'Cuci Kering',weight:'3,6 kg',status:'Selesai',total:28800,date:'07 Agu 2026'},
    {id:'LDY-2381',customer:'Alya Ramadhani',shop:'Fresh Wash',service:'Express',weight:'2,8 kg',status:'Dicuci',total:50400,date:'10 Agu 2026'},
    {id:'LDY-2374',customer:'Bimo Aji',shop:'Bersih Kilat Laundry',service:'Cuci + Setrika',weight:'5,1 kg',status:'Dijemput',total:51000,date:'10 Agu 2026'}
  ]
};

function cloneSeed(){return JSON.parse(JSON.stringify(seed))}
function getState(){try{return JSON.parse(localStorage.getItem(KEY))||cloneSeed()}catch{return cloneSeed()}}
function saveState(s){localStorage.setItem(KEY,JSON.stringify(s))}
function money(n){return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(n)}
function roleLabel(r){return r==='admin'?'Administrator':r==='agent'?'Mitra Laundry':'Pelanggan'}
function statusClass(s){return s==='Selesai'?'done':s==='Dijemput'?'pickup':'process'}

function toast(msg){
  let el=$('.toast');
  if(!el){el=document.createElement('div');el.className='toast';document.body.appendChild(el)}
  el.textContent=msg;el.classList.add('show');
  clearTimeout(window.__toastTimer);
  window.__toastTimer=setTimeout(()=>el.classList.remove('show'),2200);
}

function initPublicNav(){
  const nav=$('#navActions');
  if(!nav)return;
  const state=getState();
  if(state.session){
    nav.innerHTML=`<a class="btn btn-ghost" href="dashboard.html">${state.session.name.split(' ')[0]}</a><a class="btn btn-primary" href="dashboard.html">Dashboard</a>`;
  }
}

function initMotion(){
  if(!window.matchMedia('(hover:hover) and (pointer:fine)').matches)return;
  if(window.matchMedia('(prefers-reduced-motion: reduce)').matches)return;
  $$('[data-tilt]').forEach(card=>{
    let frame=0;
    card.addEventListener('pointermove',e=>{
      cancelAnimationFrame(frame);
      frame=requestAnimationFrame(()=>{
        const r=card.getBoundingClientRect();
        const x=(e.clientX-r.left)/r.width-.5;
        const y=(e.clientY-r.top)/r.height-.5;
        card.style.transform=`perspective(1000px) rotateX(${-y*2.5}deg) rotateY(${x*3}deg)`;
      });
    },{passive:true});
    card.addEventListener('pointerleave',()=>{cancelAnimationFrame(frame);card.style.transform=''});
  });
}

function initHome(){
  initPublicNav();
  const input=$('#agentSearch');
  if(input){
    input.addEventListener('input',()=>{
      const q=input.value.trim().toLowerCase();
      $$('.agent-card').forEach(c=>c.hidden=!c.dataset.search.includes(q));
    });
  }
  initMotion();
}

function initFeatures(){initPublicNav();initMotion()}

function setAlert(type,msg){
  const a=$('#formAlert');
  if(!a)return;
  a.className=`alert show ${type}`;
  a.textContent=msg;
}

function initLogin(){
  const f=$('#loginForm');
  if(!f)return;
  f.addEventListener('submit',e=>{
    e.preventDefault();
    const email=$('#email').value.trim().toLowerCase();
    const pass=$('#password').value;
    const s=getState();
    const u=s.users.find(x=>x.email.toLowerCase()===email&&x.password===pass);
    if(!u)return setAlert('error','Email atau kata sandi belum cocok.');
    s.session={name:u.name,email:u.email,role:u.role};
    saveState(s);
    setAlert('success','Berhasil masuk. Membuka dashboard…');
    setTimeout(()=>location.href='dashboard.html',250);
  });
  $$('[data-login]').forEach(b=>b.addEventListener('click',()=>{
    const map={customer:'raka@laundryku.id',agent:'nadia@laundryku.id',admin:'admin@laundryku.id'};
    $('#email').value=map[b.dataset.login];
    $('#password').value='laundry123';
    f.requestSubmit();
  }));
}

function initSignup(){
  const f=$('#signupForm');
  if(!f)return;
  f.addEventListener('submit',e=>{
    e.preventDefault();
    const s=getState();
    const name=$('#name').value.trim();
    const email=$('#email').value.trim().toLowerCase();
    const password=$('#password').value;
    const role=$('#role').value;
    if(name.length<2||!email.includes('@')||password.length<6)return setAlert('error','Lengkapi data akun dengan benar.');
    if(s.users.some(u=>u.email.toLowerCase()===email))return setAlert('error','Email tersebut sudah digunakan.');
    s.users.push({name,email,password,role});
    s.session={name,email,role};
    saveState(s);
    setAlert('success','Akun berhasil dibuat. Membuka dashboard…');
    setTimeout(()=>location.href='dashboard.html',300);
  });
}

function initDashboard(){
  const state=getState();
  if(!state.session){location.replace('login.html');return}
  const user=state.session;
  $('#userName').textContent=user.name;
  $('#userRole').textContent=roleLabel(user.role);
  $('#avatar').textContent=user.name.slice(0,1).toUpperCase();
  $('#helloName').textContent=user.name.split(' ')[0];
  $('#roleBadge').textContent=roleLabel(user.role);
  const mine=user.role==='customer'?state.orders.filter(o=>o.customer===user.name):state.orders;
  $('#statOne').textContent=user.role==='admin'?state.users.length:mine.filter(o=>o.status!=='Selesai').length;
  $('#statOneLabel').textContent=user.role==='admin'?'Total pengguna':'Pesanan aktif';
  $('#statTwo').textContent=user.role==='agent'?state.orders.length:mine.length;
  $('#statTwoLabel').textContent=user.role==='agent'?'Order masuk':'Total pesanan';
  $('#statThree').textContent=money(mine.reduce((a,o)=>a+o.total,0));
  $('#statThreeLabel').textContent=user.role==='agent'?'Nilai order':'Total transaksi';
  $('#statFour').textContent=user.role==='admin'?'5':'4.9';
  $('#statFourLabel').textContent=user.role==='admin'?'Mitra aktif':'Rating rata-rata';
  renderOrders(mine);renderHistory(mine);

  $$('[data-section]').forEach(b=>b.addEventListener('click',()=>{
    const id=b.dataset.section;
    $$('[data-section]').forEach(x=>x.classList.toggle('active',x===b));
    $$('.dash-section').forEach(x=>x.classList.toggle('active',x.id===id));
  }));
  $('#logoutBtn')?.addEventListener('click',()=>{state.session=null;saveState(state);location.href='index.html'});
  $('#openOrder')?.addEventListener('click',()=>$('#orderModal')?.classList.add('open'));
  $('#closeOrder')?.addEventListener('click',()=>$('#orderModal')?.classList.remove('open'));
  $('#newOrderForm')?.addEventListener('submit',e=>{
    e.preventDefault();
    const s=getState();
    const service=$('#service').value;
    const shop=$('#shop').value;
    const weight=parseFloat($('#weight').value||'1');
    const price=service==='Express'?18000:service==='Cuci + Setrika'?10000:7000;
    s.orders.unshift({id:'LDY-'+Math.floor(2500+Math.random()*500),customer:user.name,shop,service,weight:`${weight.toFixed(1).replace('.',',')} kg`,status:'Dijemput',total:Math.round(weight*price),date:'10 Agu 2026'});
    saveState(s);
    $('#orderModal')?.classList.remove('open');
    toast('Pesanan berhasil dibuat');
    setTimeout(()=>location.reload(),350);
  });
}

function renderOrders(orders){
  const box=$('#recentOrders');if(!box)return;
  box.innerHTML=orders.slice(0,4).map(o=>`<div class="order-row"><div><strong>${o.shop}</strong><span>${o.id} · ${o.service}</span></div><span>${o.weight}</span><span>${money(o.total)}</span><span class="badge ${statusClass(o.status)}">${o.status}</span></div>`).join('')||'<p class="empty">Belum ada pesanan.</p>';
}
function renderHistory(orders){
  const body=$('#historyBody');if(!body)return;
  body.innerHTML=orders.map(o=>`<tr><td><strong>${o.id}</strong></td><td>${o.date}</td><td>${o.shop}</td><td>${o.service}</td><td>${o.weight}</td><td>${money(o.total)}</td><td><span class="badge ${statusClass(o.status)}">${o.status}</span></td></tr>`).join('');
}

document.addEventListener('DOMContentLoaded',()=>{
  const page=document.body.dataset.page;
  if(page==='home')initHome();
  else if(page==='features')initFeatures();
  else if(page==='login')initLogin();
  else if(page==='signup')initSignup();
  else if(page==='dashboard')initDashboard();
});