const refreshStyle=document.createElement('link');refreshStyle.rel='stylesheet';refreshStyle.href='assets/refresh.css';document.head.appendChild(refreshStyle);
const $=(s,r=document)=>r.querySelector(s);
const $$=(s,r=document)=>[...r.querySelectorAll(s)];
const KEY='laundryku_state_v2';
const seed={
  users:[
    {name:'Raka Pratama',email:'raka@pelakorku.id',password:'laundry123',role:'customer'},
    {name:'Nadia Putri',email:'nadia@pelakorku.id',password:'laundry123',role:'agent'},
    {name:'Admin Pelakorku',email:'admin@pelakorku.id',password:'laundry123',role:'admin'}
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
function migrateState(s){
  if(!s||typeof s!=='object')return cloneSeed();
  if(Array.isArray(s.users))s.users.forEach(u=>{
    if(typeof u.name==='string')u.name=u.name.replace(/Laundryku/gi,'Pelakorku');
    if(typeof u.email==='string')u.email=u.email.replace(/@laundryku\.id$/i,'@pelakorku.id');
  });
  if(s.session){
    if(typeof s.session.name==='string')s.session.name=s.session.name.replace(/Laundryku/gi,'Pelakorku');
    if(typeof s.session.email==='string')s.session.email=s.session.email.replace(/@laundryku\.id$/i,'@pelakorku.id');
  }
  return s;
}
function getState(){try{return migrateState(JSON.parse(localStorage.getItem(KEY))||cloneSeed())}catch{return cloneSeed()}}
function saveState(s){localStorage.setItem(KEY,JSON.stringify(migrateState(s)))}
function money(n){return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(n)}
function roleLabel(r){return r==='admin'?'Administrator':r==='agent'?'Mitra Laundry':'Pelanggan'}
function statusClass(s){return s==='Selesai'?'done':s==='Dijemput'?'pickup':'process'}

const flowIcons=[
  `<svg viewBox="0 0 48 48" aria-hidden="true"><circle cx="21" cy="21" r="11"></circle><path d="m29 29 9 9"></path><path d="M17 21h8M21 17v8"></path></svg>`,
  `<svg viewBox="0 0 48 48" aria-hidden="true"><path d="M12 16h24l-2 22H14l-2-22Z"></path><path d="M18 16c0-4 2.5-7 6-7s6 3 6 7"></path><path d="M19 25h10M19 31h7"></path></svg>`,
  `<svg viewBox="0 0 48 48" aria-hidden="true"><path d="M8 30h24V15H8v15Z"></path><path d="M32 21h5l4 5v4h-9"></path><circle cx="16" cy="34" r="4"></circle><circle cx="35" cy="34" r="4"></circle><path d="M20 34h11"></path></svg>`,
  `<svg viewBox="0 0 48 48" aria-hidden="true"><circle cx="24" cy="24" r="16"></circle><path d="m16 24 6 6 11-13"></path><path d="M24 4v5M44 24h-5M24 44v-5M4 24h5"></path></svg>`
];

function applyPelakorkuIdentity(){
  document.title=document.title.replace(/Laundryku/gi,'Pelakorku');
  const description=document.querySelector('meta[name="description"]');
  if(description)description.content=description.content.replace(/Laundryku/gi,'Pelakorku');

  const walker=document.createTreeWalker(document.body,NodeFilter.SHOW_TEXT);
  const textNodes=[];
  while(walker.nextNode())textNodes.push(walker.currentNode);
  textNodes.forEach(node=>{
    if(/laundryku/i.test(node.nodeValue||''))node.nodeValue=node.nodeValue.replace(/LAUNDRYKU/g,'PELAKORKU').replace(/Laundryku/gi,'Pelakorku');
  });
  $$('img[alt]').forEach(img=>{if(/laundryku/i.test(img.alt))img.alt=img.alt.replace(/Laundryku/gi,'Pelakorku')});

  $$('.v3-brand').forEach(brand=>{
    brand.setAttribute('aria-label','Pelakorku home');
    brand.innerHTML='<img class="pelakorku-logo" src="img/banner.png" alt="Pelakorku">';
  });

  $$('.v3-flow-grid article').forEach((card,index)=>{
    if(index>3||card.querySelector('.v3-flow-icon'))return;
    const icon=document.createElement('div');
    icon.className='v3-flow-icon';
    icon.innerHTML=flowIcons[index];
    const number=card.querySelector(':scope > span');
    if(number)number.insertAdjacentElement('afterend',icon);else card.prepend(icon);
  });

  if(!document.getElementById('pelakorku-brand-patch')){
    const style=document.createElement('style');
    style.id='pelakorku-brand-patch';
    style.textContent=`
      .v3-brand{min-width:170px;height:72px;display:flex;align-items:center;margin-right:auto}
      .v3-brand .pelakorku-logo{display:block!important;height:68px!important;width:auto!important;max-width:170px!important;object-fit:contain!important;object-position:left center!important}
      .v3-brand-footer{height:auto!important;min-height:76px}
      .v3-brand-footer .pelakorku-logo{height:74px!important;max-width:185px!important}
      .v3-flow-grid{display:grid!important;grid-template-columns:repeat(4,minmax(0,1fr))!important;gap:14px!important}
      .v3-flow-grid article{position:relative!important;min-height:340px!important;padding:26px 26px 30px!important;border:1px solid #dbe2ec!important;border-radius:24px!important;background:#fff!important;display:flex!important;flex-direction:column!important;overflow:hidden!important;transition:transform .22s ease,border-color .22s ease,box-shadow .22s ease!important}
      .v3-flow-grid article:hover{transform:translateY(-5px)!important;border-color:#b7c8df!important;box-shadow:0 22px 48px rgba(24,42,72,.09)!important}
      .v3-flow-grid article:nth-child(2){background:#fff9d8!important}
      .v3-flow-grid article:nth-child(3){background:#eaf2ff!important}
      .v3-flow-grid article:nth-child(4){background:#101827!important;color:#fff!important;border-color:#101827!important}
      .v3-flow-grid article>span:first-child{font-size:.64rem!important;font-weight:900!important;letter-spacing:.16em!important;color:#748197!important}
      .v3-flow-grid article:nth-child(4)>span:first-child{color:#8fa0b8!important}
      .v3-flow-icon{width:76px;height:76px;border-radius:23px;display:grid;place-items:center;margin:34px 0 42px;background:#eff4ff;color:#125dff;border:1px solid rgba(18,93,255,.1);position:relative}
      .v3-flow-icon:after{content:"";position:absolute;inset:8px;border:1px solid currentColor;border-radius:17px;opacity:.12}
      .v3-flow-icon svg{width:38px;height:38px;fill:none;stroke:currentColor;stroke-width:2.2;stroke-linecap:round;stroke-linejoin:round}
      .v3-flow-grid article:nth-child(2) .v3-flow-icon{background:#fff0a5;color:#8a6500;border-color:rgba(138,101,0,.1)}
      .v3-flow-grid article:nth-child(3) .v3-flow-icon{background:#d8e8ff;color:#125dff}
      .v3-flow-grid article:nth-child(4) .v3-flow-icon{background:#1c2a41;color:#83bdff;border-color:#2b405f}
      .v3-flow-grid article>div:last-child{margin-top:auto}
      .v3-flow-grid h3{font-size:1.55rem!important;letter-spacing:-.045em!important;margin:0 0 9px!important}
      .v3-flow-grid p{font-size:.86rem!important;line-height:1.65!important;margin:0!important;color:#718096!important;max-width:24ch}
      .v3-flow-grid article:nth-child(4) p{color:#9facbe!important}
      @media(max-width:980px){.v3-flow-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important}.v3-brand .pelakorku-logo{height:62px!important;max-width:155px!important}}
      @media(max-width:680px){.v3-flow-grid{grid-template-columns:1fr!important}.v3-flow-grid article{min-height:300px!important}.v3-flow-icon{margin:28px 0 34px}.v3-brand{min-width:112px;height:64px}.v3-brand .pelakorku-logo{height:55px!important;max-width:120px!important}}
    `;
    document.head.appendChild(style);
  }
}

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
    const isV3=document.body.classList.contains('v3-page');
    nav.innerHTML=isV3
      ?`<a class="v3-btn v3-btn-quiet" href="dashboard.html">${state.session.name.split(' ')[0]}</a><a class="v3-btn v3-btn-dark" href="dashboard.html">Dashboard <span>↗</span></a>`
      :`<a class="btn btn-ghost" href="dashboard.html">${state.session.name.split(' ')[0]}</a><a class="btn btn-primary" href="dashboard.html">Dashboard</a>`;
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
    const map={customer:'raka@pelakorku.id',agent:'nadia@pelakorku.id',admin:'admin@pelakorku.id'};
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
  saveState(state);
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
  applyPelakorkuIdentity();
  const page=document.body.dataset.page;
  if(page==='home')initHome();
  else if(page==='features')initFeatures();
  else if(page==='login')initLogin();
  else if(page==='signup')initSignup();
  else if(page==='dashboard')initDashboard();
});