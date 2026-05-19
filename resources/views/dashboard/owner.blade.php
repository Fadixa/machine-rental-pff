@extends('layouts.app')
@section('title', 'Espace propriétaire — Rentify')

@push('styles')
<style>
.dash-wrap {
    max-width: 1280px; margin: 0 auto; padding: 28px 32px;
    display: grid; grid-template-columns: 240px 1fr; gap: 24px; align-items: start;
}
.dash-sidebar {
    background: #fff; border: 1px solid #F0F0F0;
    border-radius: var(--radius-lg); overflow: hidden;
    position: sticky; top: 90px;
}
.dash-user-block {
    background: var(--navy); padding: 20px 18px;
    display: flex; align-items: center; gap: 12px;
}
.dash-avatar {
    width: 44px; height: 44px; background: var(--orange);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 18px; font-weight: 800; color: #111; flex-shrink: 0;
}
.dash-user-name { color: #fff; font-size: 14px; font-weight: 700; }
.dash-user-role {
    display: inline-flex; align-items: center; gap: 4px;
    background: rgba(245,158,11,.2); color: var(--orange);
    font-size: 10px; font-weight: 700; padding: 2px 8px;
    border-radius: 100px; margin-top: 3px;
}
.dash-nav { padding: 10px 0; }
.dash-nav-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 18px; font-size: 13px; font-weight: 500;
    color: var(--text-gray); cursor: pointer; transition: all .15s;
    border-left: 3px solid transparent; text-decoration: none;
}
.dash-nav-item:hover { background: #F9FAFB; color: var(--navy); }
.dash-nav-item.active {
    background: rgba(245,158,11,.06); color: var(--orange);
    border-left-color: var(--orange); font-weight: 700;
}
.dash-nav-item i { width: 16px; text-align: center; font-size: 13px; }
.dash-nav-sep { height: 1px; background: #F5F5F5; margin: 6px 0; }
.btn-logout {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 18px; font-size: 13px; font-weight: 500;
    color: #EF4444; cursor: pointer; width: 100%; border: none;
    background: none; text-align: left; transition: background .15s;
}
.btn-logout:hover { background: #FEF2F2; }
.dash-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 22px; flex-wrap: wrap; gap: 12px;
}
.dash-title    { font-size: 22px; font-weight: 900; color: var(--navy); letter-spacing: -.4px; }
.dash-subtitle { font-size: 13px; color: var(--text-gray); margin-top: 3px; }
.kpi-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 22px; }
.kpi-card {
    background: #fff; border: 1px solid #F0F0F0;
    border-radius: var(--radius-lg); padding: 16px 18px; transition: box-shadow .2s;
}
.kpi-card:hover { box-shadow: var(--shadow-md); }
.kpi-label { font-size: 10px; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
.kpi-label i { color: var(--orange); }
.kpi-value { font-size: 26px; font-weight: 900; color: var(--navy); letter-spacing: -1px; }
.kpi-sub   { font-size: 11px; color: var(--text-light); margin-top: 2px; }
.owner-tabs {
    display: flex; gap: 4px;
    background: #F3F4F6; border-radius: 10px; padding: 4px; margin-bottom: 20px;
}
.owner-tab {
    flex: 1; text-align: center; padding: 9px 12px;
    font-size: 13px; font-weight: 600; color: var(--text-gray);
    border-radius: 8px; cursor: pointer; transition: all .2s;
    display: flex; align-items: center; justify-content: center; gap: 6px;
}
.owner-tab.active { background: #fff; color: var(--navy); box-shadow: 0 1px 4px rgba(0,0,0,.08); }
.tab-count {
    background: var(--orange); color: #111;
    font-size: 10px; font-weight: 800; padding: 1px 6px;
    border-radius: 100px; min-width: 18px; text-align: center;
}
.machines-owner-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; }
.mowner-card {
    background: #fff; border: 1px solid #F0F0F0; border-radius: var(--radius-lg);
    overflow: hidden; transition: box-shadow .2s, border-color .2s;
    animation: cardIn .4s ease both;
}
@keyframes cardIn { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
.mowner-card:hover { box-shadow: var(--shadow-md); border-color: var(--orange); }

/* ── Mini viewer 3D ── */
.mowner-img {
    height: 140px; background: #071018;
    position: relative; overflow: hidden; display: block;
}
.mowner-img canvas {
    display: block; width: 100% !important; height: 100% !important;
    cursor: pointer;
}
@keyframes v3dspin-mini { to { transform: rotate(360deg); } }
.mini-loader {
    position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
    background: #071018; z-index: 3; pointer-events: none;
}
.mini-loader-dot {
    width: 22px; height: 22px; border: 2px solid #162333;
    border-top-color: #F59E0B; border-radius: 50%;
    animation: v3dspin-mini .65s linear infinite;
}

.mowner-status {
    position: absolute; top: 8px; left: 8px; z-index: 4;
    font-size: 10px; font-weight: 700; padding: 3px 9px; border-radius: 100px;
}
.ms-available  { background: #D1FAE5; color: #065F46; }
.ms-rented     { background: #FEF3C7; color: #92400E; }
.ms-maintenance{ background: #FEE2E2; color: #991B1B; }
.ms-pending    { background: #EDE9FE; color: #5B21B6; }

/* hint "cliquer pour détail" */
.mowner-img-hint {
    position: absolute; bottom: 6px; right: 8px; z-index: 4;
    font-size: 9px; color: rgba(255,255,255,.35); pointer-events: none;
    letter-spacing: .04em;
}

.mowner-body   { padding: 12px 14px; }
.mowner-type   { font-size: 9px; font-weight: 800; color: var(--orange); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 2px; }
.mowner-name   { font-size: 13px; font-weight: 800; color: var(--navy); margin-bottom: 4px; }
.mowner-loc    { font-size: 11px; color: var(--text-light); margin-bottom: 8px; display: flex; align-items: center; gap: 4px; }
.mowner-loc i  { color: var(--orange); font-size: 10px; }
.mowner-footer {
    display: flex; align-items: center; justify-content: space-between;
    border-top: 1px solid #F5F5F5; padding-top: 10px;
}
.mowner-price       { font-size: 15px; font-weight: 900; color: var(--navy); }
.mowner-price small { font-size: 10px; color: var(--text-light); font-weight: 400; }
.mowner-actions { display: flex; gap: 5px; }
.mowner-stats   { display: flex; gap: 10px; margin-bottom: 8px; }
.mowner-stat    { font-size: 11px; color: var(--text-light); }
.mowner-stat strong { color: var(--navy); font-weight: 700; }
.btn-icon {
    width: 30px; height: 30px; border-radius: 7px; border: none;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; cursor: pointer; transition: all .15s;
}
.btn-icon-edit { background: #EFF6FF; color: #2563EB; }
.btn-icon-edit:hover { background: #2563EB; color: #fff; }
.btn-icon-del  { background: #FEF2F2; color: #EF4444; }
.btn-icon-del:hover  { background: #EF4444; color: #fff; }
.add-machine-card {
    border: 2px dashed #D1D5DB; border-radius: var(--radius-lg);
    min-height: 200px; display: flex; align-items: center; justify-content: center;
    flex-direction: column; gap: 10px; cursor: pointer; transition: all .2s;
    color: var(--text-light);
}
.add-machine-card:hover { border-color: var(--orange); color: var(--orange); background: rgba(245,158,11,.04); }
.add-machine-icon { font-size: 28px; opacity: .5; }
.add-machine-text { font-size: 13px; font-weight: 600; }
.dash-panel {
    background: #fff; border: 1px solid #F0F0F0;
    border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 20px;
}
.panel-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; border-bottom: 1px solid #F5F5F5;
}
.panel-title { font-size: 15px; font-weight: 800; color: var(--navy); }
.panel-badge { font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 100px; background: rgba(245,158,11,.12); color: var(--orange); }
.res-table { width: 100%; border-collapse: collapse; }
.res-table th { text-align: left; padding: 10px 20px; font-size: 10px; font-weight: 800; color: var(--text-light); letter-spacing: 1px; text-transform: uppercase; background: #FAFAFA; border-bottom: 1px solid #F5F5F5; }
.res-table td { padding: 14px 20px; border-bottom: 1px solid #F9F9F9; font-size: 13px; color: var(--navy); vertical-align: middle; }
.res-table tr:last-child td { border-bottom: none; }
.res-table tr:hover td { background: #FAFAFA; }
.stat-badge  { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 100px; }
.sb-pending  { background: #FEF3C7; color: #92400E; }
.sb-accepted { background: #D1FAE5; color: #065F46; }
.sb-rejected { background: #FEE2E2; color: #991B1B; }
.sb-completed{ background: #EDE9FE; color: #5B21B6; }
.sb-default  { background: #F3F4F6; color: #6B7280; }
.btn-accept  { background: #10B981; color: #fff; border: none; border-radius: 6px; padding: 5px 12px; font-size: 11px; font-weight: 700; cursor: pointer; transition: opacity .15s; }
.btn-accept:hover { opacity: .85; }
.btn-reject  { background: #F3F4F6; color: var(--text-gray); border: none; border-radius: 6px; padding: 5px 12px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all .15s; }
.btn-reject:hover { background: #FEE2E2; color: #EF4444; }
.empty-state { padding: 48px 20px; text-align: center; color: var(--text-light); }
.empty-state-icon  { font-size: 40px; margin-bottom: 10px; opacity: .5; }
.empty-state-title { font-size: 15px; font-weight: 700; color: var(--navy); margin-bottom: 5px; }
.revenue-chart {
    background: linear-gradient(135deg, var(--navy), #1D3557);
    border-radius: var(--radius-lg); padding: 24px 28px; margin-bottom: 20px; color: #fff;
}
.rev-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.rev-title  { font-size: 15px; font-weight: 800; }
.rev-total  { font-size: 32px; font-weight: 900; color: var(--orange); letter-spacing: -1.5px; }
.rev-sub    { font-size: 12px; color: rgba(255,255,255,.4); margin-top: 2px; }
.rev-bars   { display: flex; align-items: flex-end; gap: 8px; height: 80px; }
.rev-bar-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; }
.rev-bar    { width: 100%; border-radius: 4px 4px 0 0; background: rgba(245,158,11,.3); transition: background .2s; min-height: 4px; }
.rev-bar.highlight { background: var(--orange); }
.rev-bar:hover { background: var(--orange); }
.rev-month  { font-size: 9px; color: rgba(255,255,255,.35); font-weight: 600; }

@media (max-width: 1100px) { .machines-owner-grid { grid-template-columns: repeat(2,1fr); } .kpi-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 900px)  { .dash-wrap { grid-template-columns: 1fr; } .dash-sidebar { position: static; } }
@media (max-width: 600px)  { .dash-wrap { padding: 16px; } .kpi-grid { grid-template-columns: 1fr 1fr; } .machines-owner-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="dash-wrap" id="dash-root">
    <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--text-light)">
        <div style="font-size:32px;animation:spin 1s linear infinite;display:inline-block">⚙️</div>
        <p style="margin-top:12px">Chargement...</p>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>
/* ═══════════════════════════════════════════════
   GUARD
═══════════════════════════════════════════════ */
if (!getToken() || !getUser()) {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('auth_user');
    window.location.replace('/login');
}
const user = getUser();
if (user?.role === 'client') window.location.replace('/dashboard/client');

let currentTab      = 'machines';
let allMachines     = [];
let allReservations = [];

/* ═══════════════════════════════════════════════
   MINI 3D VIEWERS — instances actives
═══════════════════════════════════════════════ */
var _miniViewers = [];   // { renderer, animId }

function destroyMiniViewers() {
    _miniViewers.forEach(function(v) {
        cancelAnimationFrame(v.animId);
        v.renderer.dispose();
    });
    _miniViewers = [];
}

/* ─── Palette par type ─── */
var MINI_PAL = {
    excavatrice: { main:0xF0900C, dark:0xBB6800 },
    grue:        { main:0xf5e230, dark:0xb8a800 },
    chargeuse:   { main:0xF0900C, dark:0xBB6800 },
    bulldozer:   { main:0xf5a623, dark:0xc07a00 },
    compacteur:  { main:0xff6b35, dark:0xc84b1c },
    nacelle:     { main:0x42a5f5, dark:0x1565c0 },
    camion:      { main:0xF0900C, dark:0xBB6800 },
    manitou:     { main:0xf5a623, dark:0xc07a00 },
    niveleuse:   { main:0xf5e230, dark:0xb8a800 },
};

function initMiniViewer(canvasId, machineType) {
    var canvas = document.getElementById(canvasId);
    if (!canvas) return;
    var wrap = canvas.parentElement;
    var W = wrap.clientWidth  || 280;
    var H = wrap.clientHeight || 140;

    var TYPE = (machineType || 'excavatrice').toLowerCase();
    var PAL  = MINI_PAL[TYPE] || MINI_PAL.excavatrice;

    /* Renderer */
    var renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: false });
    renderer.setPixelRatio(Math.min(devicePixelRatio, 1.5));
    renderer.setSize(W, H);
    renderer.shadowMap.enabled = false;

    /* Scene */
    var scene  = new THREE.Scene();
    scene.background = new THREE.Color(0x071018);
    var camera = new THREE.PerspectiveCamera(46, W/H, 0.1, 80);

    /* Lumières */
    scene.add(new THREE.AmbientLight(0xffffff, 0.6));
    var sun = new THREE.DirectionalLight(0xfff5dd, 1.6);
    sun.position.set(6, 10, 7); scene.add(sun);
    var fill = new THREE.DirectionalLight(0x3355bb, 0.3);
    fill.position.set(-5, 3, -5); scene.add(fill);

    /* Sol léger */
    scene.add(new THREE.GridHelper(14, 14, 0x1b3246, 0x111f2d));

    /* ── Helpers matériaux ── */
    var M  = function(c) { return new THREE.MeshLambertMaterial({ color: c }); };
    var MT = function(c,o) { return new THREE.MeshLambertMaterial({ color:c, transparent:true, opacity:o }); };
    var Y=M(PAL.main), YD=M(PAL.dark), BK=M(0x0d0d0d), SL=M(0x7a8898), DK=M(0x060c12);
    var GL=MT(0x88c4ea,.4), RD=MT(0xff2200,.9), AM=MT(0xffbb00,.85);

    /* ── Helpers géométrie ── */
    var B = function(w,h,d,mat,x,y,z,rx,ry,rz) {
        x=x||0;y=y||0;z=z||0;rx=rx||0;ry=ry||0;rz=rz||0;
        var o = new THREE.Mesh(new THREE.BoxGeometry(w,h,d), mat);
        o.position.set(x,y,z); o.rotation.set(rx,ry,rz); return o;
    };
    var C = function(rt,rb,h,s,mat,x,y,z,rx,ry,rz) {
        x=x||0;y=y||0;z=z||0;rx=rx||0;ry=ry||0;rz=rz||0;
        var o = new THREE.Mesh(new THREE.CylinderGeometry(rt,rb,h,s), mat);
        o.position.set(x,y,z); o.rotation.set(rx,ry,rz); return o;
    };

    /* ══════════════════════════════════════
       MODÈLE MINI JCB 3CX (simplifié)
    ══════════════════════════════════════ */
    var JCB = new THREE.Group();

    /* Chassis */
    JCB.add(B(3.8,.19,1.8,YD,0,.48,0));
    JCB.add(B(3.5,.36,1.6,Y, 0,.74,0));

    /* Cab */
    var cab = new THREE.Group(); cab.position.set(-.5,.92,0);
    cab.add(B(1.2,1.3,1.4,Y, 0,.65,0));
    cab.add(B(.06,.7,1.05,GL,-.6,.75,0));
    cab.add(B(.06,.7,1.05,GL,.6,.75,0));
    cab.add(B(1.1,.06,1.05,GL,0,1.32,0));
    cab.add(B(1.2,.06,1.4,YD,0,1.33,0));
    cab.add(B(.06,.7,.06,DK,-.6,.75,.7)); cab.add(B(.06,.7,.06,DK,-.6,.75,-.7));
    cab.add(B(.06,.7,.06,DK,.6,.75,.7));  cab.add(B(.06,.7,.06,DK,.6,.75,-.7));
    cab.add(B(.06,.13,.2,GL,-.6,.86,.6)); cab.add(B(.06,.13,.2,GL,-.6,.86,-.6));
    JCB.add(cab);

    /* Capot */
    JCB.add(B(1.5,.82,1.55,Y,.88,1.16,0));
    JCB.add(B(.06,.5,1.35,DK,1.65,1.1,0));
    for(var i=0;i<4;i++) JCB.add(B(.07,.04,1.3,DK,1.64,.88+i*.1,0));
    JCB.add(C(.05,.05,.55,8,SL,.68,1.83,.5));
    JCB.add(B(.06,.14,.2,RD,1.65,1.02,.6));
    JCB.add(B(.06,.14,.2,RD,1.65,1.02,-.6));

    /* Roues (simplifiées) */
    var addW = function(x,y,z,r,w) {
        JCB.add(C(r,r,w,20,BK,x,y,z,Math.PI/2,0,0));
        JCB.add(C(r*.48,r*.48,w+.02,14,SL,x,y,z,Math.PI/2,0,0));
        JCB.add(C(r*.14,r*.14,w+.04,6,DK,x,y,z,Math.PI/2,0,0));
    };
    addW(-1.25,.44,.9,.44,.36); addW(-1.25,.44,-.9,.44,.36);
    addW( 1.25,.48,.93,.48,.4); addW( 1.25,.48,-.93,.48,.4);
    JCB.add(C(.06,.06,1.88,6,SL,-1.25,.44,0,Math.PI/2,0,0));
    JCB.add(C(.06,.06,1.95,6,SL, 1.25,.48,0,Math.PI/2,0,0));

    /* Loader avant */
    var la = new THREE.Group(); la.position.set(-1.82,.75,0);
    la.add(B(.11,1.2,.11,YD,0,.52,.63,-.22,0,0));
    la.add(B(.11,1.2,.11,YD,0,.52,-.63,-.22,0,0));
    la.add(B(.09,.09,1.38,YD,0,.92,0));
    var lb = new THREE.Group(); lb.position.set(-.12,1.16,0);
    lb.add(B(.46,.26,1.62,Y)); lb.add(B(.34,.06,1.62,YD,-.14,-.12,0,.32,0,0));
    la.add(lb); JCB.add(la);

    /* Backhoe arrière */
    var bh = new THREE.Group(); bh.position.set(1.82,.75,0);
    bh.add(B(.36,.6,.44,Y,0,.3,0));
    var addStab = function(zs){
        bh.add(B(.11,.06,.11,Y,.11,-.05,zs));
        bh.add(B(.06,.62,.06,Y,.11,-.37,zs,.17*Math.sign(zs),0,0));
        bh.add(B(.35,.06,.2,YD,.13,-.7,zs));
    };
    addStab(.98); addStab(-.98);
    var boom = new THREE.Group(); boom.position.set(0,.6,0); boom.rotation.z=-.45;
    boom.add(B(.16,1.9,.16,Y,0,.95,0));
    var dipper = new THREE.Group(); dipper.position.set(0,1.9,0); dipper.rotation.z=.55;
    dipper.add(B(.13,1.25,.13,Y,0,.62,0));
    var bkt = new THREE.Group(); bkt.position.set(0,1.25,0); bkt.rotation.z=-.45;
    bkt.add(B(.4,.32,.52,YD)); bkt.add(B(.33,.06,.52,Y,-.14,-.13,0,.28,0,0));
    dipper.add(bkt); boom.add(dipper); bh.add(boom); JCB.add(bh);

    scene.add(JCB);

    /* ── Caméra orbitale auto ── */
    var theta = .9, phi = .32, R = 9.2;
    function camUpdate() {
        camera.position.set(
            R*Math.sin(theta)*Math.cos(phi),
            R*Math.sin(phi)+1.2,
            R*Math.cos(theta)*Math.cos(phi)
        );
        camera.lookAt(0, 1.2, 0);
    }

    /* ── Hover : animation du bras ── */
    var hoverAnim = false, aT = 0;
    var BOOM0=-.45, DIP0=.55, BKT0=-.45;
    canvas.addEventListener('mouseenter', function(){ hoverAnim=true; });
    canvas.addEventListener('mouseleave', function(){
        hoverAnim=false;
        boom.rotation.z=BOOM0; dipper.rotation.z=DIP0; bkt.rotation.z=BKT0;
        la.rotation.z=0; aT=0;
    });

    /* ── Loader ── */
    var loader = document.getElementById('mini-loader-'+canvasId);
    setTimeout(function(){ if(loader) loader.style.display='none'; }, 480);

    /* ── Boucle ── */
    var animId;
    function loop() {
        animId = requestAnimationFrame(loop);
        theta += .006;
        camUpdate();
        if (hoverAnim) {
            aT += .025;
            boom.rotation.z   = BOOM0+Math.sin(aT)*.7;
            dipper.rotation.z = DIP0 +Math.sin(aT*1.3+.9)*.5;
            bkt.rotation.z    = BKT0 +Math.sin(aT*1.8+1.4)*.5;
            la.rotation.z     = Math.sin(aT*.5)*.16;
        }
        renderer.render(scene, camera);
    }
    camUpdate(); loop();

    _miniViewers.push({ renderer: renderer, animId: animId });
}

/* ═══════════════════════════════════════════════
   LOAD DASHBOARD
═══════════════════════════════════════════════ */
async function loadOwnerDash() {
    const root      = document.getElementById('dash-root');
    const initial   = (user?.name || 'O').charAt(0).toUpperCase();
    const firstName = (user?.name || 'Propriétaire').split(' ')[0];

    try {
        const d     = await API.get('/api/my-machines');
        allMachines = d?.data || (Array.isArray(d) ? d : []);
    } catch(e) { allMachines = getDemoMachines(); }

    try {
        const d         = await API.get('/api/reservations');
        allReservations = d?.data || (Array.isArray(d) ? d : []);
    } catch(e) { allReservations = getDemoReservations(); }

    const totalRevenue = Array.isArray(allReservations)
        ? allReservations.filter(r => r.status==='completed').reduce((s,r)=>s+(r.total_price||0),0) : 0;
    const pending = Array.isArray(allReservations)
        ? allReservations.filter(r=>r.status==='pending').length : 0;
    const active  = Array.isArray(allReservations)
        ? allReservations.filter(r=>r.status==='accepted').length : 0;

    root.innerHTML = `
    <aside class="dash-sidebar">
        <div class="dash-user-block">
            <div class="dash-avatar">${initial}</div>
            <div>
                <div class="dash-user-name">${user?.name||'Propriétaire'}</div>
                <div class="dash-user-role"><i class="fas fa-circle" style="font-size:5px"></i> Propriétaire</div>
            </div>
        </div>
        <nav class="dash-nav">
            <a href="/dashboard/owner" class="dash-nav-item active"><i class="fas fa-th-large"></i> Tableau de bord</a>
            <a href="/machines/create" class="dash-nav-item"><i class="fas fa-plus-circle"></i> Publier un engin</a>
            <a href="/machines"        class="dash-nav-item"><i class="fas fa-search"></i> Parcourir</a>
            <div class="dash-nav-sep"></div>
            <a href="/dashboard/owner" class="dash-nav-item"><i class="fas fa-user"></i> Mon profil</a>
            <button class="btn-logout" onclick="doLogout()"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
        </nav>
    </aside>

    <main>
        <div class="dash-header">
            <div>
                <div class="dash-title">Bonjour, ${firstName} 👋</div>
                <div class="dash-subtitle">Gérez votre flotte et vos réservations</div>
            </div>
            <a href="/machines/create" class="btn-orange"><i class="fas fa-plus"></i> Publier un engin</a>
        </div>

        <div class="kpi-grid">
            <div class="kpi-card fade-up" data-delay="0">
                <div class="kpi-label"><i class="fas fa-truck"></i> Machines</div>
                <div class="kpi-value">${allMachines.length}</div>
                <div class="kpi-sub">dans ma flotte</div>
            </div>
            <div class="kpi-card fade-up" data-delay="70">
                <div class="kpi-label"><i class="fas fa-bell"></i> En attente</div>
                <div class="kpi-value">${pending}</div>
                <div class="kpi-sub">demandes à traiter</div>
            </div>
            <div class="kpi-card fade-up" data-delay="140">
                <div class="kpi-label"><i class="fas fa-check-circle"></i> Actives</div>
                <div class="kpi-value">${active}</div>
                <div class="kpi-sub">locations en cours</div>
            </div>
            <div class="kpi-card fade-up" data-delay="210">
                <div class="kpi-label"><i class="fas fa-coins"></i> Revenus</div>
                <div class="kpi-value">${parseInt(totalRevenue).toLocaleString('fr')}</div>
                <div class="kpi-sub">DH total généré</div>
            </div>
        </div>

        <div class="revenue-chart fade-up" data-delay="280">
            <div class="rev-header">
                <div>
                    <div class="rev-title">Revenus mensuels</div>
                    <div class="rev-total">${parseInt(totalRevenue||37800).toLocaleString('fr')} DH</div>
                    <div class="rev-sub">Total 2025 <span style="color:#10B981">↑ +18%</span></div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:11px;color:rgba(255,255,255,.4);margin-bottom:4px">Meilleur mois</div>
                    <div style="font-size:16px;font-weight:800;color:var(--orange)">Mai 2025</div>
                </div>
            </div>
            <div class="rev-bars">
                ${[['Jan',35],['Fév',55],['Mar',45],['Avr',70],['Mai',100],['Jun',80],
                   ['Jui',65],['Aoû',75],['Sep',60],['Oct',85],['Nov',55],['Déc',90]]
                  .map(([m,h],i)=>`
                    <div class="rev-bar-wrap">
                        <div class="rev-bar ${i===4?'highlight':''}" style="height:${h}%"></div>
                        <div class="rev-month">${m}</div>
                    </div>`).join('')}
            </div>
        </div>

        <div class="owner-tabs">
            <div class="owner-tab active" id="tab-machines" onclick="switchTab('machines',this)">
                <i class="fas fa-truck"></i> Mes engins
                <span class="tab-count">${allMachines.length}</span>
            </div>
            <div class="owner-tab" id="tab-demandes" onclick="switchTab('demandes',this)">
                <i class="fas fa-inbox"></i> Demandes reçues
                <span class="tab-count">${pending}</span>
            </div>
            <div class="owner-tab" id="tab-history" onclick="switchTab('history',this)">
                <i class="fas fa-history"></i> Historique
            </div>
        </div>

        <div id="tab-content"></div>
    </main>`;

    document.querySelectorAll('.fade-up').forEach(el => {
        el.style.transitionDelay = (el.dataset.delay||0)+'ms';
        setTimeout(()=>el.classList.add('visible'), 50);
    });

    switchTab('machines', document.getElementById('tab-machines'));
}

/* ═══════════════════════════════════════════════
   TABS
═══════════════════════════════════════════════ */
function switchTab(tab, btn) {
    currentTab = tab;
    document.querySelectorAll('.owner-tab').forEach(t=>t.classList.remove('active'));
    btn.classList.add('active');
    const content = document.getElementById('tab-content');

    /* Détruire les viewers précédents avant de reconstruire */
    destroyMiniViewers();

    if (tab==='machines') {
        content.innerHTML = renderMachinesGrid();
        /* Initialiser les mini viewers après que le DOM est prêt */
        setTimeout(function() {
            allMachines.forEach(function(m) {
                initMiniViewer('mini3d-' + m.id, m.type);
            });
        }, 60);
    }
    if (tab==='demandes') content.innerHTML = renderDemandesTable(allReservations.filter(r=>r.status==='pending'));
    if (tab==='history')  content.innerHTML = renderHistoryTable(allReservations.filter(r=>r.status!=='pending'));
}

/* ═══════════════════════════════════════════════
   GRILLE MACHINES — canvas à la place de l'emoji
═══════════════════════════════════════════════ */
function renderMachinesGrid() {
    const statusMap = {
        available:   { label:'Disponible',  cls:'ms-available'   },
        unavailable: { label:'En location', cls:'ms-rented'      },
        rented:      { label:'En location', cls:'ms-rented'      },
        maintenance: { label:'Maintenance', cls:'ms-maintenance'  },
    };
    let html = '<div class="machines-owner-grid">';
    allMachines.forEach(function(m, i) {
        const st = statusMap[m.status] || { label: m.status, cls:'ms-pending' };
        html += `
        <div class="mowner-card" style="animation-delay:${i*60}ms">

            {{-- ── Mini viewer 3D ── --}}
            <div class="mowner-img">
                <canvas id="mini3d-${m.id}"></canvas>
                <div id="mini-loader-mini3d-${m.id}" class="mini-loader">
                    <div class="mini-loader-dot"></div>
                </div>
                <div class="mowner-status ${st.cls}">${st.label}</div>
                <div class="mowner-img-hint">⟳ survol = animation</div>
            </div>

            <div class="mowner-body">
                <div class="mowner-type">${m.type||'—'}</div>
                <div class="mowner-name">${m.name}</div>
                <div class="mowner-loc">
                    <i class="fas fa-map-marker-alt"></i>${m.city||m.location||'—'}
                </div>
                <div class="mowner-stats">
                    <div class="mowner-stat"><strong>${m.reservations_count||0}</strong> locations</div>
                    <div class="mowner-stat">·</div>
                    <div class="mowner-stat">⭐ ${m.ratings_avg?parseFloat(m.ratings_avg).toFixed(1):'—'}</div>
                </div>
                <div class="mowner-footer">
                    <div class="mowner-price">
                        ${parseInt(m.price_per_day).toLocaleString('fr')} <small>dh/j</small>
                    </div>
                    <div class="mowner-actions">
                        <button class="btn-icon btn-icon-edit" onclick="editMachine(${m.id})" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon btn-icon-del" onclick="deleteMachine(${m.id})" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    });
    html += `
    <div class="add-machine-card" onclick="window.location.href='/machines/create'">
        <div class="add-machine-icon">+</div>
        <div class="add-machine-text">Publier un nouvel engin</div>
    </div></div>`;
    return html;
}

/* ═══════════════════════════════════════════════
   TABLES DEMANDES / HISTORIQUE
═══════════════════════════════════════════════ */
function renderDemandesTable(reservations) {
    if (!reservations.length) {
        return `<div class="dash-panel"><div class="empty-state">
            <div class="empty-state-icon">📬</div>
            <div class="empty-state-title">Aucune demande en attente</div>
            <div style="font-size:13px;color:var(--text-light)">Les nouvelles demandes apparaîtront ici</div>
        </div></div>`;
    }
    return `
    <div class="dash-panel">
        <div class="panel-header">
            <div class="panel-title">Demandes en attente</div>
            <div class="panel-badge">${reservations.length} à traiter</div>
        </div>
        <table class="res-table">
            <thead><tr>
                <th>Client</th><th>Machine</th><th>Dates</th><th>Total</th><th>Actions</th>
            </tr></thead>
            <tbody>${reservations.map((r,i)=>`
            <tr id="res-row-${i}">
                <td>
                    <div style="font-weight:700">${r.client?.name||'—'}</div>
                    <div style="font-size:11px;color:var(--text-light)">${r.client?.phone||''}</div>
                </td>
                <td>
                    <div style="font-weight:700">${r.machine?.name||'—'}</div>
                    <div style="font-size:11px;color:var(--text-light)">${r.machine?.type||''}</div>
                </td>
                <td>
                    ${r.start_date||'—'}<br>
                    <span style="color:var(--text-light);font-size:11px">→ ${r.end_date||'—'} · ${r.nb_days||'—'} j</span>
                </td>
                <td style="font-weight:800">${parseInt(r.total_price||0).toLocaleString('fr')} DH</td>
                <td>
                    <div style="display:flex;gap:6px">
                        <button class="btn-accept" onclick="respondRes(${r.id},'accept',${i})">
                            <i class="fas fa-check"></i> Accepter
                        </button>
                        <button class="btn-reject" onclick="respondRes(${r.id},'reject',${i})">
                            <i class="fas fa-times"></i> Refuser
                        </button>
                    </div>
                </td>
            </tr>`).join('')}</tbody>
        </table>
    </div>`;
}

function renderHistoryTable(reservations) {
    if (!reservations.length) {
        return `<div class="dash-panel"><div class="empty-state">
            <div class="empty-state-icon">📋</div>
            <div class="empty-state-title">Aucun historique</div>
        </div></div>`;
    }
    const statusMap   = { accepted:'sb-accepted', rejected:'sb-rejected', completed:'sb-completed', cancelled:'sb-default' };
    const statusLabel = { accepted:'Confirmée',   rejected:'Refusée',     completed:'Terminée',    cancelled:'Annulée' };
    return `
    <div class="dash-panel">
        <div class="panel-header">
            <div class="panel-title">Historique des locations</div>
            <div class="panel-badge">${reservations.length} au total</div>
        </div>
        <table class="res-table">
            <thead><tr>
                <th>Client</th><th>Machine</th><th>Dates</th><th>Durée</th><th>Total</th><th>Statut</th>
            </tr></thead>
            <tbody>${reservations.map(r=>`
            <tr>
                <td style="font-weight:600">${r.client?.name||'—'}</td>
                <td><div style="font-weight:700">${r.machine?.name||'—'}</div></td>
                <td><small>${r.start_date||'—'} → ${r.end_date||'—'}</small></td>
                <td>${r.nb_days||'—'} jour(s)</td>
                <td style="font-weight:800">${parseInt(r.total_price||0).toLocaleString('fr')} DH</td>
                <td><span class="stat-badge ${statusMap[r.status]||'sb-default'}">${statusLabel[r.status]||r.status}</span></td>
            </tr>`).join('')}</tbody>
        </table>
    </div>`;
}

/* ═══════════════════════════════════════════════
   ACTIONS
═══════════════════════════════════════════════ */
async function respondRes(id, action, rowIdx) {
    try { await API.patch(`/api/reservations/${id}/${action}`); } catch(e) {}
    const row = document.getElementById(`res-row-${rowIdx}`);
    if (row) {
        row.style.background = action==='accept' ? '#F0FDF4' : '#FEF2F2';
        row.querySelector('td:last-child').innerHTML =
            `<span class="stat-badge ${action==='accept'?'sb-accepted':'sb-rejected'}">
                ${action==='accept'?'✓ Acceptée':'✗ Refusée'}
             </span>`;
    }
    showFlash(action==='accept'?'Réservation confirmée ✓':'Réservation refusée', action==='accept'?'success':'warning');
}

function editMachine(id) { window.location.href=`/machines/${id}/edit`; }

async function deleteMachine(id) {
    if (!confirm('Voulez-vous vraiment supprimer cette machine ?')) return;
    try { await API.delete(`/api/machines/${id}`); } catch(e) {}
    allMachines = allMachines.filter(m=>m.id!==id);
    switchTab('machines', document.getElementById('tab-machines'));
    showFlash('Machine supprimée', 'success');
}

/* ═══════════════════════════════════════════════
   DEMO DATA
═══════════════════════════════════════════════ */
function getDemoMachines() {
    return [
        { id:1, type:'Excavatrice', name:'JCB 3CX Backhoe Loader',  city:'Casablanca', status:'available',   price_per_day:2400, reservations_count:12, ratings_avg:4.9 },
        { id:2, type:'Camion',      name:'Camion Benne Volvo FH16',  city:'Rabat',      status:'rented',      price_per_day:1300, reservations_count:8,  ratings_avg:4.7 },
        { id:3, type:'Grue',        name:'Grue Mobile Liebherr LTM', city:'Marrakech',  status:'available',   price_per_day:3600, reservations_count:5,  ratings_avg:4.9 },
    ];
}
function getDemoReservations() {
    return [
        { id:1, client:{name:'BTP Atlas',     phone:'+212600000001'}, machine:{name:'JCB 3CX',           type:'Excavatrice'}, start_date:'2025-05-10', end_date:'2025-05-14', nb_days:5, total_price:12600, status:'pending'   },
        { id:2, client:{name:'Travaux Maroc', phone:'+212600000002'}, machine:{name:'Camion Benne Volvo', type:'Camion'},      start_date:'2025-04-28', end_date:'2025-04-30', nb_days:3, total_price:4095,  status:'completed' },
        { id:3, client:{name:'Bâtiment SA',   phone:'+212600000003'}, machine:{name:'Grue Liebherr',      type:'Grue'},        start_date:'2025-04-05', end_date:'2025-04-11', nb_days:7, total_price:26460, status:'completed' },
    ];
}

/* ═══════════════════════════════════════════════
   LOGOUT
═══════════════════════════════════════════════ */
window.doLogout = function() {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('auth_user');
    API.post('/api/logout',{}).finally(()=>window.location.replace('/'));
};

loadOwnerDash();
</script>
@endpush