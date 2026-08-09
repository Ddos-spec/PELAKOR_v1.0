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
    {id:'LDY-2381',customer:'Alya Ramadhani',shop:'Fresh Wash',service:'Kilat',weight:'2,8 kg',status:'Dicuci',total:50400,date:'10 Agu 2026'},
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
  if(Array.isArray(s.orders))s.orders.forEach(o=>{if(o.service==='Express')o.service='Kilat'});
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

const exactCopy={
  'NEW ROUTINE / 洗濯':'RUTINITAS BARU / 洗濯',
  'Explore the system':'Lihat alurnya',
  'Role terhubung':'Peran terhubung',
  'Avg. service score':'Rata-rata layanan',
  'LAUNDRY ARC / 001':'CERITA LAUNDRY / 001',
  'ORDER STATUS':'STATUS PESANAN',
  'PICKUP':'JEMPUT','WASH':'CUCI','DONE':'SELESAI','TRACKING':'PANTAU','DELIVERY':'ANTAR',
  'EVERYDAY CLEAN ROUTINE':'RUTINITAS BERSIH SETIAP HARI',
  'WHY PELAKORKU / 02':'MENGAPA PELAKORKU / 02',
  'PRODUCT SYSTEM / 03':'SISTEM PRODUK / 03',
  'Find your clean crew.':'Cari yang pas, tanpa berputar.',
  '5 laundry nearby':'5 laundry di sekitar',
  'NEW ORDER':'PESANAN BARU','CONFIRMED':'DIKONFIRMASI',
  'Order tanpa drama.':'Pesan cepat, proses tepat.',
  'LIVE ROUTINE':'PROSES BERJALAN','Next':'Berikutnya',
  'Three views.':'Tiga peran.','One system.':'Satu alur.',
  'CUSTOMER':'PELANGGAN','PARTNER':'MITRA',
  'Order & tracking':'Pesan & pantau','Incoming jobs':'Pesanan masuk','System overview':'Ringkasan sistem',
  'Open full feature map':'Lihat semua fitur',
  'THE EXPERIENCE / 04':'PENGALAMAN / 04',
  'Feels less like software.':'Bukan sekadar aplikasi.','More like a routine.':'Alurnya terasa nyata.',
  'CHAPTER 01':'BABAK 01','Drop the mental load.':'Urusan berkurang, hari lebih tenang.',
  '01 — DISCOVER':'01 — TEMUKAN','LOCATION':'LOKASI','RATING':'PENILAIAN','PRICE':'HARGA','SERVICE':'LAYANAN',
  '02 / PROCESS':'02 / PROSES','PELAKORKU PRODUCT PRINCIPLE':'PRINSIP PRODUK PELAKORKU',
  'NEARBY PARTNERS / 05':'MITRA TERDEKAT / 05','Meet your neighborhood clean crew.':'Temukan mitra laundry di sekitarmu.',
  'OPEN':'BUKA',
  'HOW IT MOVES / 06':'CARA KERJA / 06','Four steps. One clean ending.':'Empat langkah, cucian pun tenang.',
  'Discover':'Temukan','Order':'Pesan','Track':'Pantau','Done':'Selesai',
  'OPEN THE APP / 07':'BUKA APLIKASI / 07','Less laundry.':'Laundry beres.','More life.':'Hari lebih bebas.',
  'PRODUCT MAP / 洗濯体験':'PETA FITUR / 洗濯体験',
  'Not a dashboard.':'Bukan sekadar halaman utama.','A complete laundry loop.':'Semua alurnya tersambung.',
  'See the system':'Lihat alurnya','Open app ↗':'Buka aplikasi ↗',
  'PELAKORKU / SYSTEM MAP':'PELAKORKU / PETA SISTEM',
  'discover · order · track':'cari · pesan · pantau','receive · process · update':'terima · proses · perbarui','monitor · control · review':'pantau · kendali · tinjau',
  'CORE CAPABILITIES / 02':'FITUR UTAMA / 02','Discovery':'Pencarian','SEARCH / FILTER':'CARI / SARING',
  'Ordering':'Pemesanan','ORDER FLOW':'ALUR PESANAN','Tracking':'Pelacakan','LIVE STATUS':'STATUS PROSES',
  'History':'Riwayat','TRANSACTIONS':'TRANSAKSI','Partner Ops':'Operasional Mitra','WORKSPACE':'RUANG KERJA',
  'Admin Control':'Kontrol Admin','OVERVIEW':'RINGKASAN',
  'CUSTOMER JOURNEY / 03':'PERJALANAN PELANGGAN / 03','ORDER #2408':'PESANAN #2408',
  'ROLE EXPERIENCE / 04':'PENGALAMAN PERAN / 04','01 / CUSTOMER':'01 / PELANGGAN','02 / PARTNER':'02 / MITRA',
  'customer / overview':'pelanggan / ringkasan','HI, RAKA':'HAI, RAKA','Laundry is moving.':'Cucian sedang diproses.',
  'Active':'Aktif','Orders':'Pesanan','Rating':'Nilai','Incoming work.':'Pesanan yang masuk.',
  'Incoming orders':'Pesanan masuk','Progress control':'Kontrol proses','Order value':'Nilai pesanan','Work history':'Riwayat pekerjaan',
  'User overview':'Ringkasan pengguna','Active partners':'Mitra aktif','Order monitoring':'Pantau pesanan','Transaction value':'Nilai transaksi',
  'OPEN THE APP / 05':'BUKA APLIKASI / 05','See it move.':'Lihat alurnya.','Not just look good.':'Bukan cuma tampil bagus.',
  'Open Pelakorku ↗':'Buka PELAKORKU ↗','Back home':'Kembali ke beranda','PRODUCT SYSTEM / 洗濯':'SISTEM PRODUK / 洗濯',
  'WELCOME BACK / おかえり':'SELAMAT DATANG KEMBALI / おかえり','One login.':'Sekali masuk.','Zero laundry drama.':'Urusan laundry lebih lega.',
  '01 PICKUP':'01 JEMPUT','02 WASH':'02 CUCI','03 DONE':'03 SELESAI','Account access':'Akses akun',
  'NEW ROUTINE / はじめよう':'MULAI RUTINITAS BARU / はじめよう','Your laundry arc starts here.':'Mulai dari sini, laundry lebih rapi.',
  'JOIN':'DAFTAR','ORDER':'PESAN','TRACK':'PANTAU','Create account':'Buat akun',
  'Overview':'Ringkasan','Quick actions':'Akses cepat','ACTIVE ROUTINE / 洗濯中':'PROSES AKTIF / 洗濯中',
  'Everything clean.':'Semua rapi.','Everything tracked.':'Semua terpantau.','Transactions':'Transaksi','Profile':'Profil',
  'Account active':'Akun aktif','Preferences':'Preferensi','New order':'Pesanan baru','service score':'nilai layanan','Dashboard':'Dasbor'
};

const phraseCopy=[
  ['Laundry yang terasa seperti ','Titip, pantau, beres. '],
  ['tidak mengurus laundry.','Hari jadi lebih lepas.'],
  ['Cari mitra, pilih layanan, buat pesanan, lalu pantau progresnya. Semua dibuat supaya rutinitas laundry hilang dari daftar hal yang harus kamu pikirkan.','Cari mitra, pilih layanan, lalu pantau cucian. Pesan lebih ringkas, proses lebih jelas, hasil rapi tanpa bikin hari terasa berat.'],
  ['Laundry seharusnya sesederhana ','Urusan laundry cukup '],
  ['Karena yang dibutuhkan bukan aplikasi yang ramai, tapi sistem yang membuat pelanggan, mitra laundry, dan admin bergerak di alur yang sama tanpa saling mengganggu.','Bukan soal menu yang ramai. Yang penting pelanggan nyaman, mitra lancar, admin sigap, dan semuanya bergerak dalam alur yang rapi.'],
  ['Satu produk. Banyak momen kecil yang dibikin gampang.','Satu sistem, banyak urusan jadi ringan.'],
  ['Setiap fitur dibangun mengikuti perjalanan laundry nyata—bukan sekadar menambah menu.','Setiap fitur mengikuti kebutuhan nyata: cari, pesan, pantau, lalu selesai tanpa langkah yang berbelit.'],
  ['Cari laundry berdasarkan area, rating, layanan, dan harga tanpa pindah-pindah halaman.','Cari berdasarkan lokasi, penilaian, layanan, dan harga dalam satu tampilan yang ringkas.'],
  ['Pilih mitra, layanan, dan estimasi berat. Selesai dalam satu flow.','Pilih mitra, layanan, dan estimasi berat. Sedikit langkah, pesanan langsung berangkat.'],
  ['Dari pickup sampai selesai, status ditampilkan sebagai timeline yang gampang dibaca.','Dari dijemput sampai selesai, progres terlihat jelas. Tak perlu menebak, tak perlu bertanya berulang.'],
  ['Dari discovery sampai cucian kembali, seluruh perjalanan dibuat terasa satu cerita.','Dari mencari mitra sampai cucian kembali, semuanya mengalir dalam satu cerita yang mudah diikuti.'],
  ['Pilih laundry yang cocok, bukan yang kebetulan ketemu.','Pilih yang cocok, bukan sekadar yang muncul paling dulu.'],
  ['Bandingkan area, rating, layanan, dan harga. Tidak perlu mulai dari chat satu per satu.','Bandingkan lokasi, penilaian, layanan, dan harga tanpa harus membuka percakapan satu per satu.'],
  ['Yang penting bukan banyak fitur. Yang penting pengguna selalu tahu harus melakukan apa berikutnya.','Fitur yang baik bukan yang paling ramai, tetapi yang membuat langkah berikutnya selalu terasa jelas.'],
  ['Pickup & Delivery','Jemput & Antar'],['Bed Cover · Shoes Care','Bed Cover · Perawatan Sepatu'],
  ['Daily Laundry · Same-day Service','Laundry Harian · Selesai Hari Ini'],['Fast Pickup · Status Tracking','Jemput Cepat · Status Terpantau'],
  ['Premium Care · Household Laundry','Perawatan Premium · Laundry Rumah Tangga'],
  ['Temukan mitra berdasarkan kebutuhanmu.','Cari mitra yang paling sesuai dengan lokasi dan kebutuhanmu.'],
  ['Pilih layanan dan buat pesanan.','Pilih layanan, isi kebutuhan, lalu kirim pesanan.'],
  ['Pantau progres cucian secara jelas.','Pantau progres dari dijemput sampai kembali rapi.'],
  ['Cucian selesai, histori tetap tersimpan.','Cucian beres, riwayat tersimpan, hati pun tenang.'],
  ['Masuk ke salah satu role dan lihat seluruh alurnya berjalan.','Masuk sebagai pelanggan, mitra, atau admin dan rasakan alurnya dari awal sampai selesai.'],
  ['Tiga orang. Tiga kebutuhan. Satu source of truth.','Tiga peran, tiga kebutuhan, satu alur yang sama.'],
  ['Interface berubah sesuai role supaya tidak ada informasi yang mengganggu pekerjaan utama.','Tampilan menyesuaikan peran, jadi setiap orang hanya melihat hal yang memang perlu dikerjakan.'],
  ['Kerjakan order, bukan mengurus dashboard.','Kerjakan pesanan, bukan sibuk mengurus tampilan.'],
  ['System overview without the noise.','Pantau sistem tanpa keruwetan.'],
  ['From “cucian numpuk” to “done”.','Dari cucian menumpuk sampai kembali rapi.']
];

const flowIcons=[
  `<svg viewBox="0 0 48 48" aria-hidden="true"><circle cx="21" cy="21" r="11"></circle><path d="m29 29 9 9"></path><path d="M17 21h8M21 17v8"></path></svg>`,
  `<svg viewBox="0 0 48 48" aria-hidden="true"><path d="M12 16h24l-2 22H14l-2-22Z"></path><path d="M18 16c0-4 2.5-7 6-7s6 3 6 7"></path><path d="M19 25h10M19 31h7"></path></svg>`,
  `<svg viewBox="0 0 48 48" aria-hidden="true"><path d="M8 30h24V15H8v15Z"></path><path d="M32 21h5l4 5v4h-9"></path><circle cx="16" cy="34" r="4"></circle><circle cx="35" cy="34" r="4"></circle><path d="M20 34h11"></path></svg>`,
  `<svg viewBox="0 0 48 48" aria-hidden="true"><circle cx="24" cy="24" r="16"></circle><path d="m16 24 6 6 11-13"></path><path d="M24 4v5M44 24h-5M24 44v-5M4 24h5"></path></svg>`
];

function localizeText(){
  const walker=document.createTreeWalker(document.body,NodeFilter.SHOW_TEXT);
  const nodes=[];while(walker.nextNode())nodes.push(walker.currentNode);
  nodes.forEach(node=>{
    let value=node.nodeValue||'';
    value=value.replace(/LAUNDRYKU/g,'PELAKORKU').replace(/Laundryku/gi,'Pelakorku');
    const trimmed=value.trim();
    if(exactCopy[trimmed]) value=value.replace(trimmed,exactCopy[trimmed]);
    phraseCopy.forEach(([from,to])=>{if(value.includes(from))value=value.split(from).join(to)});
    node.nodeValue=value;
  });
  const circle=$('.v3-circle-link span');if(circle)circle.innerHTML='LIHAT<br>FITUR';
}

function applyPelakorkuIdentity(){
  document.title=document.title.replace(/Laundryku/gi,'PELAKORKU').replace(/Clean Routine System/gi,'Laundry Lebih Ringkas');
  const description=document.querySelector('meta[name="description"]');
  if(description)description.content=description.content.replace(/Laundryku/gi,'PELAKORKU');
  localizeText();
  $$('img[alt]').forEach(img=>{if(/laundryku/i.test(img.alt))img.alt=img.alt.replace(/Laundryku/gi,'PELAKORKU')});

  $$('.v3-brand').forEach(brand=>{
    brand.setAttribute('aria-label','PELAKORKU beranda');
    brand.innerHTML='<img class="pelakorku-logo" src="img/banner.png" alt="Logo PELAKORKU"><span class="pelakorku-brand-copy"><strong>PELAKORKU</strong><small>Laundry di PELAKORKU</small></span>';
  });

  $$('.v3-flow-grid article').forEach((card,index)=>{
    if(index>3||card.querySelector('.v3-flow-icon'))return;
    const icon=document.createElement('div');icon.className='v3-flow-icon';icon.innerHTML=flowIcons[index];
    const number=card.querySelector(':scope > span');if(number)number.insertAdjacentElement('afterend',icon);else card.prepend(icon);
  });

  if(!document.getElementById('pelakorku-brand-patch')){
    const style=document.createElement('style');style.id='pelakorku-brand-patch';
    style.textContent=`
      .v3-brand{min-width:286px;height:72px;display:flex;align-items:center;gap:14px;margin-right:auto;color:#101726}
      .v3-brand .pelakorku-logo{display:block!important;height:54px!important;width:auto!important;max-width:94px!important;object-fit:contain!important;object-position:left center!important;flex:0 0 auto}
      .pelakorku-brand-copy{display:grid;gap:3px;line-height:1}
      .pelakorku-brand-copy strong{font-size:1.08rem;font-weight:900;letter-spacing:-.035em;color:#101726}
      .pelakorku-brand-copy small{font-size:.59rem;font-weight:800;letter-spacing:.12em;color:#6f7b90;text-transform:uppercase;white-space:nowrap}
      .v3-brand-footer{height:auto!important;min-height:76px}
      .v3-brand-footer .pelakorku-logo{height:58px!important;max-width:100px!important}
      .v3-brand-footer .pelakorku-brand-copy strong,.v3-brand-footer .pelakorku-brand-copy small{color:#fff}
      .v3-flow-grid{display:grid!important;grid-template-columns:repeat(4,minmax(0,1fr))!important;gap:14px!important}
      .v3-flow-grid article{position:relative!important;min-height:340px!important;padding:26px 26px 30px!important;border:1px solid #dbe2ec!important;border-radius:24px!important;background:#fff!important;display:flex!important;flex-direction:column!important;overflow:hidden!important;transition:transform .22s ease,border-color .22s ease,box-shadow .22s ease!important}
      .v3-flow-grid article:hover{transform:translateY(-5px)!important;border-color:#b7c8df!important;box-shadow:0 22px 48px rgba(24,42,72,.09)!important}
      .v3-flow-grid article:nth-child(2){background:#fff9d8!important}.v3-flow-grid article:nth-child(3){background:#eaf2ff!important}.v3-flow-grid article:nth-child(4){background:#101827!important;color:#fff!important;border-color:#101827!important}
      .v3-flow-grid article>span:first-child{font-size:.64rem!important;font-weight:900!important;letter-spacing:.16em!important;color:#748197!important}.v3-flow-grid article:nth-child(4)>span:first-child{color:#8fa0b8!important}
      .v3-flow-icon{width:76px;height:76px;border-radius:23px;display:grid;place-items:center;margin:34px 0 42px;background:#eff4ff;color:#125dff;border:1px solid rgba(18,93,255,.1);position:relative}.v3-flow-icon:after{content:"";position:absolute;inset:8px;border:1px solid currentColor;border-radius:17px;opacity:.12}
      .v3-flow-icon svg{width:38px;height:38px;fill:none;stroke:currentColor;stroke-width:2.2;stroke-linecap:round;stroke-linejoin:round}.v3-flow-grid article:nth-child(2) .v3-flow-icon{background:#fff0a5;color:#8a6500}.v3-flow-grid article:nth-child(3) .v3-flow-icon{background:#d8e8ff;color:#125dff}.v3-flow-grid article:nth-child(4) .v3-flow-icon{background:#1c2a41;color:#83bdff;border-color:#2b405f}
      .v3-flow-grid article>div:last-child{margin-top:auto}.v3-flow-grid h3{font-size:1.55rem!important;letter-spacing:-.045em!important;margin:0 0 9px!important}.v3-flow-grid p{font-size:.86rem!important;line-height:1.65!important;margin:0!important;color:#718096!important;max-width:24ch}.v3-flow-grid article:nth-child(4) p{color:#9facbe!important}
      @media(max-width:980px){.v3-flow-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important}.v3-brand{min-width:235px}.v3-brand .pelakorku-logo{height:49px!important;max-width:86px!important}.pelakorku-brand-copy strong{font-size:1rem}}
      @media(max-width:680px){.v3-flow-grid{grid-template-columns:1fr!important}.v3-flow-grid article{min-height:300px!important}.v3-flow-icon{margin:28px 0 34px}.v3-brand{min-width:0;height:64px;gap:9px}.v3-brand .pelakorku-logo{height:44px!important;max-width:76px!important}.pelakorku-brand-copy strong{font-size:.86rem}.pelakorku-brand-copy small{font-size:.48rem;letter-spacing:.07em}.v3-links{display:none!important}}
    `;
    document.head.appendChild(style);
  }
}

function toast(msg){let el=$('.toast');if(!el){el=document.createElement('div');el.className='toast';document.body.appendChild(el)}el.textContent=msg;el.classList.add('show');clearTimeout(window.__toastTimer);window.__toastTimer=setTimeout(()=>el.classList.remove('show'),2200)}

function initPublicNav(){
  const nav=$('#navActions');if(!nav)return;const state=getState();
  if(state.session){const isV3=document.body.classList.contains('v3-page');nav.innerHTML=isV3?`<a class="v3-btn v3-btn-quiet" href="dashboard.html">${state.session.name.split(' ')[0]}</a><a class="v3-btn v3-btn-dark" href="dashboard.html">Dasbor <span>↗</span></a>`:`<a class="btn btn-ghost" href="dashboard.html">${state.session.name.split(' ')[0]}</a><a class="btn btn-primary" href="dashboard.html">Dasbor</a>`}
}
function initMotion(){if(!window.matchMedia('(hover:hover) and (pointer:fine)').matches||window.matchMedia('(prefers-reduced-motion: reduce)').matches)return;$$('[data-tilt]').forEach(card=>{let frame=0;card.addEventListener('pointermove',e=>{cancelAnimationFrame(frame);frame=requestAnimationFrame(()=>{const r=card.getBoundingClientRect(),x=(e.clientX-r.left)/r.width-.5,y=(e.clientY-r.top)/r.height-.5;card.style.transform=`perspective(1000px) rotateX(${-y*2.5}deg) rotateY(${x*3}deg)`})},{passive:true});card.addEventListener('pointerleave',()=>{cancelAnimationFrame(frame);card.style.transform=''})})}
function initHome(){initPublicNav();const input=$('#agentSearch');if(input)input.addEventListener('input',()=>{const q=input.value.trim().toLowerCase();$$('.agent-card').forEach(c=>c.hidden=!c.dataset.search.includes(q))});initMotion()}
function initFeatures(){initPublicNav();initMotion()}
function setAlert(type,msg){const a=$('#formAlert');if(!a)return;a.className=`alert show ${type}`;a.textContent=msg}

function initLogin(){
  const f=$('#loginForm');if(!f)return;
  f.addEventListener('submit',e=>{e.preventDefault();const email=$('#email').value.trim().toLowerCase(),pass=$('#password').value,s=getState(),u=s.users.find(x=>x.email.toLowerCase()===email&&x.password===pass);if(!u)return setAlert('error','Email atau kata sandi belum cocok.');s.session={name:u.name,email:u.email,role:u.role};saveState(s);setAlert('success','Berhasil masuk. Membuka dasbor…');setTimeout(()=>location.href='dashboard.html',250)});
  $$('[data-login]').forEach(b=>b.addEventListener('click',()=>{const map={customer:'raka@pelakorku.id',agent:'nadia@pelakorku.id',admin:'admin@pelakorku.id'};$('#email').value=map[b.dataset.login];$('#password').value='laundry123';f.requestSubmit()}));
}
function initSignup(){
  const f=$('#signupForm');if(!f)return;
  f.addEventListener('submit',e=>{e.preventDefault();const s=getState(),name=$('#name').value.trim(),email=$('#email').value.trim().toLowerCase(),password=$('#password').value,role=$('#role').value;if(name.length<2||!email.includes('@')||password.length<6)return setAlert('error','Lengkapi data akun dengan benar.');if(s.users.some(u=>u.email.toLowerCase()===email))return setAlert('error','Email tersebut sudah digunakan.');s.users.push({name,email,password,role});s.session={name,email,role};saveState(s);setAlert('success','Akun berhasil dibuat. Membuka dasbor…');setTimeout(()=>location.href='dashboard.html',300)});
}
function initDashboard(){
  const state=getState();if(!state.session){location.replace('login.html');return}saveState(state);const user=state.session;
  $('#userName').textContent=user.name;$('#userRole').textContent=roleLabel(user.role);$('#avatar').textContent=user.name.slice(0,1).toUpperCase();$('#helloName').textContent=user.name.split(' ')[0];$('#roleBadge').textContent=roleLabel(user.role);
  const mine=user.role==='customer'?state.orders.filter(o=>o.customer===user.name):state.orders;
  $('#statOne').textContent=user.role==='admin'?state.users.length:mine.filter(o=>o.status!=='Selesai').length;$('#statOneLabel').textContent=user.role==='admin'?'Total pengguna':'Pesanan aktif';$('#statTwo').textContent=user.role==='agent'?state.orders.length:mine.length;$('#statTwoLabel').textContent=user.role==='agent'?'Pesanan masuk':'Total pesanan';$('#statThree').textContent=money(mine.reduce((a,o)=>a+o.total,0));$('#statThreeLabel').textContent=user.role==='agent'?'Nilai pesanan':'Total transaksi';$('#statFour').textContent=user.role==='admin'?'5':'4.9';$('#statFourLabel').textContent=user.role==='admin'?'Mitra aktif':'Nilai rata-rata';renderOrders(mine);renderHistory(mine);
  $$('[data-section]').forEach(b=>b.addEventListener('click',()=>{const id=b.dataset.section;$$('[data-section]').forEach(x=>x.classList.toggle('active',x===b));$$('.dash-section').forEach(x=>x.classList.toggle('active',x.id===id))}));
  $('#logoutBtn')?.addEventListener('click',()=>{state.session=null;saveState(state);location.href='index.html'});$('#openOrder')?.addEventListener('click',()=>$('#orderModal')?.classList.add('open'));$('#closeOrder')?.addEventListener('click',()=>$('#orderModal')?.classList.remove('open'));
  $('#newOrderForm')?.addEventListener('submit',e=>{e.preventDefault();const s=getState(),service=$('#service').value,shop=$('#shop').value,weight=parseFloat($('#weight').value||'1'),price=(service==='Kilat'||service==='Express')?18000:service==='Cuci + Setrika'?10000:7000;s.orders.unshift({id:'LDY-'+Math.floor(2500+Math.random()*500),customer:user.name,shop,service:service==='Express'?'Kilat':service,weight:`${weight.toFixed(1).replace('.',',')} kg`,status:'Dijemput',total:Math.round(weight*price),date:'10 Agu 2026'});saveState(s);$('#orderModal')?.classList.remove('open');toast('Pesanan berhasil dibuat');setTimeout(()=>location.reload(),350)});
}
function renderOrders(orders){const box=$('#recentOrders');if(!box)return;box.innerHTML=orders.slice(0,4).map(o=>`<div class="order-row"><div><strong>${o.shop}</strong><span>${o.id} · ${o.service}</span></div><span>${o.weight}</span><span>${money(o.total)}</span><span class="badge ${statusClass(o.status)}">${o.status}</span></div>`).join('')||'<p class="empty">Belum ada pesanan.</p>'}
function renderHistory(orders){const body=$('#historyBody');if(!body)return;body.innerHTML=orders.map(o=>`<tr><td><strong>${o.id}</strong></td><td>${o.date}</td><td>${o.shop}</td><td>${o.service}</td><td>${o.weight}</td><td>${money(o.total)}</td><td><span class="badge ${statusClass(o.status)}">${o.status}</span></td></tr>`).join('')}

document.addEventListener('DOMContentLoaded',()=>{applyPelakorkuIdentity();const page=document.body.dataset.page;if(page==='home')initHome();else if(page==='features')initFeatures();else if(page==='login')initLogin();else if(page==='signup')initSignup();else if(page==='dashboard')initDashboard()});