
@extends('layouts.app')
@section('title', 'Rentify — Location d\'engins BTP au Maroc')

@push('styles')
<style>
:root {
    --gold:#D4AF37; --gold-dk:#9A7D20; --gold-lt:#F5E88A;
    --gold-pale:#FEF9E7; --gold-glow:rgba(212,175,55,.25);
    --cream:#FAF7F0; --cream2:#F0EBE0; --cream3:#E8DDD0;
    --txt-dark:#1a1a2e; --txt-mid:#5a5660; --txt-light:#9992a4;
}

/* ── LOADING ── */
#loading-screen {
    position:fixed; inset:0; z-index:9999;
    background:var(--cream);
    display:flex; flex-direction:column;
    align-items:center; justify-content:center;
    transition:opacity .6s,visibility .6s;
}
#loading-screen.hide { opacity:0; visibility:hidden; }
.ls-logo {
    font-family:'Playfair Display',Georgia,serif;
    font-size:2.4rem; color:var(--txt-dark);
    margin-bottom:.3rem;
}
.ls-logo span { color:var(--gold); }
.ls-sub {
    font-size:.7rem; color:var(--txt-light);
    letter-spacing:.2em; margin-bottom:2rem; text-transform:uppercase;
}
.ls-bar-wrap {
    width:180px; height:2px;
    background:rgba(212,175,55,.2);
    border-radius:2px; overflow:hidden;
}
.ls-bar {
    height:100%; width:0; background:var(--gold);
    border-radius:2px; transition:width .3s;
}

*,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
body { background:var(--cream); color:var(--txt-dark); font-family:'DM Sans',system-ui,sans-serif; }
.container { max-width:1140px; margin:0 auto; padding:0 1.75rem; }
.eyebrow {
    font-size:.68rem; font-weight:700; letter-spacing:.16em;
    text-transform:uppercase; display:block; margin-bottom:.85rem;
    color:var(--gold-dk);
}

/* ── BUTTONS ── */
.btn-primary {
    display:inline-flex; align-items:center; gap:8px;
    background:var(--gold); color:var(--txt-dark)!important;
    border:2px solid var(--gold); padding:12px 28px;
    border-radius:8px; font-size:.875rem; font-weight:700;
    text-decoration:none; cursor:pointer;
    transition:all .18s;
}
.btn-primary:hover {
    background:var(--gold-dk); border-color:var(--gold-dk);
    color:#fff!important; transform:translateY(-2px);
    box-shadow:0 6px 20px var(--gold-glow);
}
.btn-secondary {
    display:inline-flex; align-items:center; gap:8px;
    background:#fff; color:var(--gold-dk)!important;
    border:2px solid var(--gold); padding:12px 28px;
    border-radius:8px; font-size:.875rem; font-weight:600;
    text-decoration:none; cursor:pointer; transition:all .18s;
}
.btn-secondary:hover {
    background:var(--gold-pale); transform:translateY(-2px);
}
/* rétrocompat */
.btn-navy   { display:inline-flex;align-items:center;gap:8px;background:var(--gold)!important;color:var(--txt-dark)!important;border:2px solid var(--gold)!important;padding:12px 28px;border-radius:8px;font-size:.875rem;font-weight:700;text-decoration:none;cursor:pointer;transition:all .18s; }
.btn-navy:hover { background:var(--gold-dk)!important;border-color:var(--gold-dk)!important;color:#fff!important;transform:translateY(-2px); }
.btn-outline { display:inline-flex;align-items:center;gap:8px;background:#fff!important;color:var(--gold-dk)!important;border:2px solid var(--gold)!important;padding:12px 28px;border-radius:8px;font-size:.875rem;font-weight:600;text-decoration:none;cursor:pointer;transition:all .18s; }
.btn-outline:hover { background:var(--gold-pale)!important; }
.btn-gold { display:inline-flex;align-items:center;gap:8px;background:var(--gold)!important;color:var(--txt-dark)!important;border:2px solid var(--gold)!important;padding:12px 28px;border-radius:8px;font-size:.875rem;font-weight:700;text-decoration:none;cursor:pointer;transition:all .18s; }
.btn-gold:hover { background:var(--gold-dk)!important;border-color:var(--gold-dk)!important;color:#fff!important;transform:translateY(-2px); }

/* ══ §1 HERO ══ */
.hero {
    background:var(--cream); padding:5rem 0 4rem;
    position:relative; overflow:hidden;
}
.hero::before {
    content:''; position:absolute; width:520px; height:520px;
    border-radius:50%; border:1px solid rgba(212,175,55,.12);
    top:-150px; right:-100px; pointer-events:none;
}
.hero::after {
    content:''; position:absolute; width:280px; height:280px;
    border-radius:50%; border:1px solid rgba(212,175,55,.08);
    bottom:-80px; left:-60px; pointer-events:none;
}
.hero-inner { display:flex; align-items:center; gap:4rem; flex-wrap:wrap; }
.hero-text  { flex:1; min-width:280px; }
.hero-tag {
    display:inline-flex; align-items:center; gap:8px;
    background:var(--gold-pale); border:1px solid rgba(212,175,55,.4);
    color:var(--gold-dk); border-radius:100px; padding:5px 16px;
    font-size:.7rem; font-weight:700; letter-spacing:.1em;
    text-transform:uppercase; margin-bottom:1.4rem;
}
.hero-h1 {
    font-family:'Playfair Display',Georgia,serif;
    font-size:clamp(2rem,4vw,3rem); font-weight:700;
    line-height:1.18; color:var(--txt-dark); margin-bottom:1.2rem;
}
.hero-h1 em { font-style:normal; color:var(--gold); }
.hero-desc {
    font-size:.95rem; color:var(--txt-mid); line-height:1.8;
    max-width:420px; margin-bottom:2rem;
}
.hero-cta { display:flex; gap:14px; flex-wrap:wrap; align-items:center; margin-bottom:2.5rem; }
.hero-stats {
    display:flex; gap:2.5rem; padding-top:2rem;
    border-top:1px solid var(--cream3);
}
.hstat-n { font-size:1.45rem; font-weight:700; color:var(--gold); line-height:1; }
.hstat-l { font-size:.72rem; color:var(--txt-light); margin-top:3px; }

/* Hero visual */
.hero-visual {
    flex-shrink:0; position:relative;
    display:flex; align-items:center; justify-content:center;
    min-width:400px;
}
.hv-ring3 { position:absolute; width:480px; height:480px; border-radius:50%; border:1px dashed rgba(212,175,55,.15); }
.hv-ring2 { position:absolute; width:420px; height:420px; border-radius:50%; border:1px solid rgba(212,175,55,.25); }
.hv-circle {
    width:360px; height:360px; border-radius:50%;
    border:3px solid var(--gold); overflow:hidden;
    position:relative; z-index:1; background:var(--cream2);
    box-shadow:0 12px 48px var(--gold-glow);
}
.hv-circle img { width:100%; height:100%; object-fit:cover; filter:brightness(.92) saturate(.95); }
.hv-dot { position:absolute; border-radius:50%; background:var(--gold); }
.hv-dot1 { width:14px; height:14px; top:12px; right:36px; opacity:.5; z-index:2; }
.hv-dot2 { width:9px;  height:9px;  bottom:28px; left:16px; opacity:.35; z-index:2; }
.hv-dot3 { width:22px; height:22px; top:45%; left:-22px; opacity:.18; z-index:2; }
/* Badges hero — crème fond */
.hv-badge {
    position:absolute; bottom:8px; right:-18px; z-index:3;
    background:var(--cream); color:var(--txt-dark);
    border-radius:100px; padding:8px 16px;
    font-size:.72rem; font-weight:700;
    border:1.5px solid var(--gold); white-space:nowrap;
    box-shadow:0 4px 16px rgba(15,27,45,.08);
}
.hv-badge2 {
    position:absolute; top:0px; left:-28px; z-index:3;
    background:var(--gold); color:var(--txt-dark);
    border-radius:100px; padding:8px 16px;
    font-size:.72rem; font-weight:700; white-space:nowrap;
}

/* Search bar */
.hero-search {
    background:#fff; border:1.5px solid var(--cream3);
    border-radius:14px; padding:1.1rem 1.5rem;
    display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;
    max-width:820px; margin:2.5rem auto 0;
    box-shadow:0 4px 24px rgba(15,27,45,.05);
}
.hsb-f { flex:1; min-width:130px; }
.hsb-f label {
    display:block; font-size:.68rem; font-weight:700;
    color:var(--txt-light); letter-spacing:.1em;
    text-transform:uppercase; margin-bottom:5px;
}
.hsb-f select,.hsb-f input {
    width:100%; border:1px solid var(--cream3);
    border-radius:8px; padding:9px 11px;
    font-size:.85rem; color:var(--txt-dark);
    background:var(--cream); outline:none;
    transition:border-color .15s; -webkit-appearance:none;
}
.hsb-f select:focus,.hsb-f input:focus { border-color:var(--gold); }
.hsb-sep { width:1px; background:var(--cream3); align-self:stretch; margin:4px 0; }

/* Trust band — crème avec bordure gold */
.trust-band {
    background:var(--cream2);
    border-top:2px solid var(--gold);
    border-bottom:1px solid var(--cream3);
    padding:1rem 0;
}
.trust-items { display:flex; justify-content:center; gap:2.5rem; flex-wrap:wrap; }
.trust-item {
    display:flex; align-items:center; gap:9px;
    font-size:.76rem; color:var(--txt-mid); letter-spacing:.04em;
}
.trust-item i { color:var(--gold); font-size:1rem; }

/* ══ §2 CATEGORIES ══ */
.categories { padding:4rem 0 2rem; background:var(--cream); }
.cats-grid {
    display:grid; grid-template-columns:repeat(auto-fill,minmax(120px,1fr));
    gap:1rem; margin-top:2rem;
}
.cat-card {
    background:#fff; border:1.5px solid var(--cream3);
    border-radius:14px; padding:1.2rem .8rem;
    text-align:center; cursor:pointer;
    transition:all .2s; text-decoration:none; display:block;
}
.cat-card:hover,.cat-card.active {
    border-color:var(--gold); background:var(--gold-pale);
    transform:translateY(-3px);
    box-shadow:0 6px 20px var(--gold-glow);
}
.cat-ico {
    width:50px; height:50px; border-radius:50%;
    border:2px solid var(--gold); background:var(--gold-pale);
    display:flex; align-items:center; justify-content:center;
    margin:0 auto .7rem; transition:background .2s;
}
.cat-card:hover .cat-ico,.cat-card.active .cat-ico { background:var(--gold); }
.cat-ico i { font-size:1.2rem; color:var(--gold-dk); transition:color .2s; }
.cat-card:hover .cat-ico i,.cat-card.active .cat-ico i { color:var(--txt-dark); }
.cat-name  { font-size:.78rem; font-weight:600; color:var(--txt-dark); }
.cat-count { font-size:.68rem; color:var(--txt-light); margin-top:2px; }

/* ══ §3 CATALOGUE FEATURED ══ */
.catalogue { padding:3rem 0 5rem; background:var(--cream); }
.section-header { text-align:center; margin-bottom:2.5rem; }
.section-title {
    font-family:'Playfair Display',Georgia,serif;
    font-size:clamp(1.6rem,3vw,2.2rem); font-weight:700;
    color:var(--txt-dark); line-height:1.25; margin-bottom:.9rem;
}
.section-title em { font-style:normal; color:var(--gold); }
.section-lead { font-size:.9rem; color:var(--txt-mid); line-height:1.75; }

.machines-grid {
    display:grid; grid-template-columns:repeat(auto-fill,minmax(270px,1fr)); gap:1.5rem;
}

/* Machine Card */
.mc {
    border-radius:16px; overflow:hidden;
    border:1px solid var(--cream3); background:#fff;
    transition:transform .22s,box-shadow .22s,border-color .2s;
    position:relative;
}
.mc:hover {
    transform:translateY(-6px);
    box-shadow:0 16px 48px rgba(15,27,45,.09);
    border-color:rgba(212,175,55,.5);
}
.mc-fav {
    position:absolute; top:12px; right:12px; z-index:10;
    width:32px; height:32px; border-radius:50%;
    background:#fff; border:1.5px solid var(--cream3);
    display:flex; align-items:center; justify-content:center;
    cursor:pointer; transition:all .2s;
    box-shadow:0 2px 8px rgba(15,27,45,.06);
}
.mc-fav i { font-size:.9rem; color:var(--txt-light); transition:color .2s,transform .2s; }
.mc-fav:hover i { color:#ef4444; transform:scale(1.2); }
.mc-fav.active { background:#fff0f0; border-color:#ef4444; }
.mc-fav.active i { color:#ef4444; }
.mc-head {
    background:var(--cream2); padding:1.6rem 1.2rem 1rem;
    display:flex; flex-direction:column; align-items:center; position:relative;
}
.mc-circle-photo {
    width:110px; height:110px; border-radius:50%;
    border:2.5px solid var(--gold); overflow:hidden;
    background:var(--cream); transition:transform .3s;
    box-shadow:0 4px 16px var(--gold-glow);
}
.mc:hover .mc-circle-photo { transform:scale(1.06); }
.mc-circle-photo img { width:100%; height:100%; object-fit:cover; filter:brightness(.9) saturate(.95); }
/* Type badge — gold bg + dark text (fini navy) */
.mc-type-badge {
    margin-top:.75rem; background:var(--gold);
    color:var(--txt-dark); border-radius:6px;
    font-size:.63rem; font-weight:800;
    letter-spacing:.08em; text-transform:uppercase; padding:3px 10px;
}
.mc-body { padding:1rem 1.25rem 1.25rem; }
.mc-name  { font-size:.95rem; font-weight:600; color:var(--txt-dark); margin-bottom:4px; text-align:center; }
.mc-city  { font-size:.76rem; color:var(--txt-light); margin-bottom:.9rem; text-align:center; }
.mc-city i { color:var(--gold); margin-right:4px; }
.mc-price-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:.9rem; }
.mc-price      { font-size:1.1rem; font-weight:700; color:var(--gold-dk); }
.mc-price small{ font-size:.7rem; font-weight:400; color:var(--txt-light); }
.mc-rating     { font-size:.76rem; color:var(--txt-mid); }
.mc-rating i   { color:var(--gold); }
.mc-actions    { display:flex; gap:6px; }
.mc-btn {
    flex:1; border:none; border-radius:8px; padding:9px 6px;
    font-size:.78rem; font-weight:600; cursor:pointer; transition:all .15s;
    text-decoration:none; display:flex; align-items:center;
    justify-content:center; gap:5px; font-family:inherit;
}
.mc-btn-detail  { background:var(--cream); color:var(--txt-dark); border:1px solid var(--cream3); }
.mc-btn-detail:hover { background:var(--cream2); }
/* Réserver — gold bg + dark text (fini navy) */
.mc-btn-reserver { background:var(--gold); color:var(--txt-dark); }
.mc-btn-reserver:hover { background:var(--gold-dk); color:#fff; }
.mc-btn-whatsapp { background:#25D366; color:#fff; flex:0 0 36px; padding:9px; border-radius:8px; }
.mc-btn-whatsapp:hover { background:#1ebe5d; }

/* ══ §4 LATEST MACHINES ══ */
.latest { padding:4rem 0 5rem; background:#fff; }
.latest-grid {
    display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
    gap:1.2rem; margin-top:2rem;
}
.lm-card {
    background:var(--cream); border:1px solid var(--cream3);
    border-radius:14px; padding:1.1rem;
    display:flex; gap:1rem; align-items:center;
    transition:border-color .2s,transform .2s;
    cursor:pointer; position:relative;
}
.lm-card:hover {
    border-color:rgba(212,175,55,.4); transform:translateY(-2px);
    box-shadow:0 6px 20px rgba(15,27,45,.06);
}
.lm-photo {
    width:72px; height:72px; border-radius:50%;
    border:2px solid var(--gold); overflow:hidden; flex-shrink:0;
}
.lm-photo img { width:100%; height:100%; object-fit:cover; }
.lm-info { flex:1; min-width:0; }
.lm-name  { font-size:.88rem; font-weight:600; color:var(--txt-dark); margin-bottom:3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.lm-meta  { font-size:.74rem; color:var(--txt-light); margin-bottom:5px; }
.lm-meta i{ color:var(--gold); margin-right:3px; }
.lm-price { font-size:.88rem; font-weight:700; color:var(--gold-dk); }
.lm-new-badge {
    position:absolute; top:10px; right:10px;
    background:var(--gold); color:var(--txt-dark);
    font-size:.6rem; font-weight:800;
    letter-spacing:.06em; padding:2px 8px;
    border-radius:100px; text-transform:uppercase;
}
.lm-fav { position:absolute; bottom:10px; right:10px; background:none; border:none; cursor:pointer; color:var(--txt-light); font-size:.88rem; transition:color .2s; }
.lm-fav:hover,.lm-fav.active { color:#ef4444; }

/* ══ §5 GALERIE ══ */
.gallery { padding:5rem 0; background:var(--cream); }
.gallery-grid {
    display:grid; grid-template-columns:2fr 1fr 1fr;
    grid-template-rows:210px 210px; gap:10px; margin-top:2.5rem;
}
.gal-item { border-radius:14px; overflow:hidden; cursor:pointer; position:relative; }
.gal-item img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .4s,filter .3s; filter:brightness(.85); }
.gal-item:hover img { transform:scale(1.06); filter:brightness(.98); }
.gal-item--big { grid-row:span 2; }
.gal-overlay { position:absolute; inset:0; background:linear-gradient(to top,rgba(15,27,45,.6) 0%,transparent 60%); opacity:0; transition:opacity .3s; }
.gal-item:hover .gal-overlay { opacity:1; }
.gal-label { position:absolute; bottom:12px; left:14px; color:#fff; font-size:.76rem; font-weight:600; opacity:0; transform:translateY(4px); transition:opacity .3s,transform .3s; }
.gal-item:hover .gal-label { opacity:1; transform:translateY(0); }
.gal-tag { position:absolute; top:10px; right:10px; background:var(--gold); color:var(--txt-dark); border-radius:4px; padding:3px 9px; font-size:.63rem; font-weight:800; letter-spacing:.07em; }

/* ══ §6 COMMENT ÇA MARCHE ══ */
.how { padding:5.5rem 0; background:#fff; }
.steps-grid {
    display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:1.5rem; margin-top:3rem;
}
.step {
    background:var(--cream); border:1px solid var(--cream3);
    border-radius:16px; padding:2rem 1.4rem;
    text-align:center; position:relative;
    transition:border-color .2s,transform .2s,box-shadow .2s;
}
.step:hover { border-color:var(--gold); transform:translateY(-4px); box-shadow:0 8px 24px var(--gold-glow); }
/* Step num — gold bg + dark text */
.step-num {
    position:absolute; top:-12px; left:50%; transform:translateX(-50%);
    background:var(--gold); color:var(--txt-dark);
    font-size:.68rem; font-weight:800; letter-spacing:.06em;
    padding:3px 12px; border-radius:100px;
}
.step-ico {
    width:64px; height:64px; border-radius:50%;
    border:2px solid var(--gold); background:var(--gold-pale);
    display:flex; align-items:center; justify-content:center;
    margin:.5rem auto 1.1rem;
}
.step-ico i { font-size:1.5rem; color:var(--gold-dk); }
.step-title { font-size:.9rem; font-weight:600; color:var(--txt-dark); margin-bottom:.45rem; }
.step-desc  { font-size:.8rem; color:var(--txt-mid); line-height:1.65; }

/* ══ §7 AVANTAGES — 100% CLAIR ══ */
.advantages {
    padding:5.5rem 0;
    background:var(--cream2);
    position:relative; overflow:hidden;
    border-top:1px solid var(--cream3);
    border-bottom:1px solid var(--cream3);
}
.advantages::before {
    content:''; position:absolute; width:500px; height:500px;
    border-radius:50%; border:1px solid rgba(212,175,55,.1);
    top:-180px; right:-180px; pointer-events:none;
}
.advantages .section-title { color:var(--txt-dark); }
.advantages .eyebrow       { color:var(--gold-dk); }
.adv-inner  { display:flex; align-items:center; gap:4rem; flex-wrap:wrap; }
.adv-left   { flex:1; min-width:260px; }
.adv-list   { margin-top:2rem; display:flex; flex-direction:column; gap:1.1rem; }
.adv-item   { display:flex; gap:1rem; align-items:flex-start; }
.adv-ico {
    flex-shrink:0; width:44px; height:44px; border-radius:50%;
    background:var(--gold-pale); border:1.5px solid rgba(212,175,55,.35);
    display:flex; align-items:center; justify-content:center;
}
.adv-ico i { font-size:1.1rem; color:var(--gold-dk); }
.adv-item-title { font-size:.9rem; font-weight:600; color:var(--txt-dark); margin-bottom:2px; }
.adv-item-desc  { font-size:.8rem; color:var(--txt-mid); line-height:1.6; }
.adv-right { flex:0 0 340px; }
/* Adv card — blanc + border gold */
.adv-card {
    background:#fff;
    border:1.5px solid var(--gold);
    border-radius:20px; padding:2.5rem;
    position:relative; overflow:hidden;
    box-shadow:0 8px 32px var(--gold-glow);
}
.adv-card::before {
    content:''; position:absolute; width:220px; height:220px;
    border-radius:50%; border:1px solid rgba(212,175,55,.12);
    top:-70px; right:-70px;
}
.adv-card-tag {
    font-size:.65rem; font-weight:700; letter-spacing:.15em;
    text-transform:uppercase; color:var(--gold-dk);
    background:var(--gold-pale); border:1px solid rgba(212,175,55,.3);
    border-radius:4px; padding:4px 10px;
    display:inline-block; margin-bottom:1.2rem;
}
.adv-card h3 {
    font-family:'Playfair Display',Georgia,serif;
    font-size:1.35rem; font-weight:700;
    color:var(--txt-dark); line-height:1.3; margin-bottom:1.2rem;
}
.adv-card h3 em { font-style:normal; color:var(--gold); }
.adv-kpis { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.5rem; }
.adv-kpi {
    background:var(--gold-pale); border:1px solid rgba(212,175,55,.2);
    border-radius:10px; padding:.9rem; text-align:center;
}
.adv-kpi-n { font-size:1.4rem; font-weight:700; color:var(--gold-dk); }
.adv-kpi-l { font-size:.7rem; color:var(--txt-light); margin-top:3px; }

/* ══ §8 VILLES ══ */
.cities { padding:5rem 0; background:var(--cream); }
.cities-grid {
    display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr));
    gap:1rem; margin-top:2.5rem;
}
.city-card {
    background:#fff; border:1px solid var(--cream3);
    border-radius:14px; padding:1.4rem 1rem;
    text-align:center; text-decoration:none; display:block;
    transition:transform .2s,border-color .2s,box-shadow .2s;
}
.city-card:hover { border-color:var(--gold); transform:translateY(-4px); box-shadow:0 8px 24px rgba(15,27,45,.07); }
.city-ico {
    width:52px; height:52px; border-radius:50%;
    border:2px solid var(--gold); background:var(--gold-pale);
    display:flex; align-items:center; justify-content:center; margin:0 auto .9rem;
}
.city-ico i { font-size:1.3rem; color:var(--gold-dk); }
.city-name  { font-size:.88rem; font-weight:600; color:var(--txt-dark); margin-bottom:3px; }
.city-count { font-size:.72rem; color:var(--txt-light); }

/* ══ §9 TÉMOIGNAGES ══ */
.testimonials { padding:5rem 0; background:var(--cream2); }
.testi-grid {
    display:grid; grid-template-columns:repeat(auto-fill,minmax(270px,1fr));
    gap:1.5rem; margin-top:2.5rem;
}
.testi {
    background:#fff; border:1px solid var(--cream3);
    border-radius:16px; padding:1.6rem;
    transition:border-color .2s,transform .2s,box-shadow .2s;
}
.testi:hover { border-color:var(--gold); transform:translateY(-3px); box-shadow:0 6px 20px var(--gold-glow); }
.testi-stars { color:var(--gold); font-size:.88rem; margin-bottom:.9rem; letter-spacing:2px; }
.testi-text  { font-size:.85rem; color:var(--txt-mid); line-height:1.7; margin-bottom:1.2rem; font-style:italic; }
.testi-author{ display:flex; align-items:center; gap:.75rem; }
.testi-avatar{
    width:38px; height:38px; border-radius:50%;
    background:var(--gold-pale); border:1.5px solid var(--gold);
    display:flex; align-items:center; justify-content:center;
    font-size:.78rem; font-weight:700; color:var(--gold-dk);
}
.testi-name { font-size:.84rem; font-weight:600; color:var(--txt-dark); }
.testi-role { font-size:.72rem; color:var(--txt-light); }

/* ══ §10 CTA FINAL — clair ══ */
.cta-final {
    background:var(--gold-pale);
    padding:5rem 0; position:relative; overflow:hidden;
    border-top:3px solid var(--gold);
    border-bottom:3px solid var(--gold);
}
.cta-final::before {
    content:''; position:absolute; width:500px; height:500px;
    border-radius:50%; border:1px solid rgba(212,175,55,.2);
    top:-200px; right:-150px;
}
.cta-final-inner { text-align:center; position:relative; z-index:1; }
.cta-final h2 {
    font-family:'Playfair Display',Georgia,serif;
    font-size:clamp(1.6rem,3vw,2.5rem); font-weight:700;
    color:var(--txt-dark); margin-bottom:.9rem; line-height:1.25;
}
.cta-final h2 em { font-style:normal; color:var(--gold-dk); }
.cta-final p {
    font-size:.9rem; color:var(--txt-mid);
    max-width:480px; margin:0 auto 2rem; line-height:1.75;
}
.cta-btns { display:flex; gap:14px; justify-content:center; flex-wrap:wrap; }
/* CTA outline adapté au fond clair */
.cta-outline {
    display:inline-flex; align-items:center; gap:8px;
    background:transparent; color:var(--gold-dk)!important;
    border:2px solid var(--gold); padding:12px 28px;
    border-radius:8px; font-size:.875rem; font-weight:600;
    text-decoration:none; cursor:pointer; transition:all .18s;
}
.cta-outline:hover { background:var(--gold); color:var(--txt-dark)!important; }

/* ── ANIMATIONS ── */
@keyframes fadeUp { from{opacity:0;transform:translateY(28px);}to{opacity:1;transform:translateY(0);} }
.anim-up { opacity:0; }
.anim-up.visible { animation:fadeUp .55s ease forwards; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.5} }

/* ── RESPONSIVE ── */
@media(max-width:900px) {
    .hero-visual { display:none; }
    .adv-inner   { flex-direction:column; }
    .adv-right   { flex:none; width:100%; }
    .gallery-grid{ grid-template-columns:1fr 1fr; grid-template-rows:auto; }
    .gal-item--big { grid-row:span 1; }
}
@media(max-width:600px) {
    .gallery-grid { grid-template-columns:1fr; }
    .hero-stats   { gap:1.5rem; }
    .cats-grid    { grid-template-columns:repeat(4,1fr); }
}
</style>
@endpush

@section('content')

{{-- LOADING --}}
<div id="loading-screen">
    <div class="ls-logo">Rent<span>ify</span></div>
    <div class="ls-sub">Location d'engins BTP au Maroc</div>
    <div class="ls-bar-wrap"><div class="ls-bar" id="ls-bar"></div></div>
</div>

{{-- ════ §1 HERO ════ --}}
<section class="hero">
<div class="container">
    <div class="hero-inner">
        <div class="hero-text">
            <div class="hero-tag">◆ &nbsp;Maroc · BTP · Engins TP</div>
            <h1 class="hero-h1">Louez les meilleurs<br><em>engins BTP</em><br>au Maroc</h1>
            <p class="hero-desc">Excavatrice, grue, bulldozer ou compacteur — trouvez l'engin qu'il vous faut à Casablanca, Rabat, Marrakech et partout au Maroc. Réservation simple, contrat PDF immédiat.</p>
            <div class="hero-cta">
                <a href="/machines" class="btn-primary"><i class="fas fa-search"></i> Voir les engins</a>
                <a href="/register" class="btn-secondary"><i class="fas fa-user-plus"></i> Créer un compte</a>
            </div>
            <div class="hero-stats">
                <div class="hstat"><div class="hstat-n">120+</div><div class="hstat-l">Engins disponibles</div></div>
                <div class="hstat"><div class="hstat-n">8</div><div class="hstat-l">Villes couvertes</div></div>
                <div class="hstat"><div class="hstat-n">480+</div><div class="hstat-l">Clients satisfaits</div></div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hv-ring3"></div>
            <div class="hv-ring2"></div>
            <div class="hv-circle">
                <img src="{{ asset('images/img7.png') }}" alt="Engin BTP Rentify">
            </div>
            <div class="hv-dot hv-dot1"></div>
            <div class="hv-dot hv-dot2"></div>
            <div class="hv-dot hv-dot3"></div>
            <div class="hv-badge2"><i class="fas fa-check-circle" style="margin-right:5px"></i>Engins vérifiés</div>
            <div class="hv-badge"><i class="fas fa-map-marker-alt" style="margin-right:5px"></i>Casablanca</div>
        </div>
    </div>

    {{-- Search bar --}}
    <div class="hero-search">
        <div class="hsb-f">
            <label><i class="fas fa-cog" style="margin-right:4px"></i>Type d'engin</label>
            <select id="search-type">
                <option value="">Tous les types</option>
                <option value="excavatrice">Excavatrice</option>
                <option value="grue">Grue</option>
                <option value="bulldozer">Bulldozer</option>
                <option value="chargeuse">Chargeuse</option>
                <option value="compacteur">Compacteur</option>
                <option value="tractopelle">Tractopelle</option>
                <option value="nacelle">Nacelle</option>
                <option value="camion">Camion</option>
            </select>
        </div>
        <div class="hsb-sep"></div>
        <div class="hsb-f">
            <label><i class="fas fa-map-marker-alt" style="margin-right:4px"></i>Ville</label>
            <select id="search-city">
                <option value="">Toutes les villes</option>
                <option value="Casablanca">Casablanca</option>
                <option value="Rabat">Rabat</option>
                <option value="Marrakech">Marrakech</option>
                <option value="Fès">Fès</option>
                <option value="Tanger">Tanger</option>
                <option value="Agadir">Agadir</option>
            </select>
        </div>
        <div class="hsb-sep"></div>
        <div class="hsb-f">
            <label><i class="fas fa-money-bill-wave" style="margin-right:4px"></i>Budget max/jour</label>
            <input type="number" id="search-budget" placeholder="Ex : 3000 MAD" min="0" step="100">
        </div>
        <button class="btn-primary" onclick="doSearch()" style="padding:11px 24px;white-space:nowrap;flex-shrink:0">
            <i class="fas fa-search"></i> Rechercher
        </button>
    </div>
</div>
</section>



{{-- ════ §2 CATEGORIES ════ --}}
<section class="categories">
<div class="container">
    <div class="section-header anim-up">
        <span class="eyebrow">Parcourir par type</span>
        <h2 class="section-title">Toutes les <em>catégories</em></h2>
    </div>
    <div class="cats-grid" id="cats-grid">
        <div class="cat-card active" onclick="filterByType('',this)">
            <div class="cat-ico"><i class="fas fa-th-large"></i></div>
            <div class="cat-name">Tous</div>
            <div class="cat-count" id="count-all">—</div>
        </div>
        <div class="cat-card" onclick="filterByType('excavatrice',this)">
            <div class="cat-ico"><i class="fas fa-hard-hat"></i></div>
            <div class="cat-name">Excavatrice</div>
            <div class="cat-count" id="count-excavatrice">—</div>
        </div>
        <div class="cat-card" onclick="filterByType('grue',this)">
            <div class="cat-ico"><i class="fas fa-building"></i></div>
            <div class="cat-name">Grue</div>
            <div class="cat-count" id="count-grue">—</div>
        </div>
        <div class="cat-card" onclick="filterByType('bulldozer',this)">
            <div class="cat-ico"><i class="fas fa-tractor"></i></div>
            <div class="cat-name">Bulldozer</div>
            <div class="cat-count" id="count-bulldozer">—</div>
        </div>
        <div class="cat-card" onclick="filterByType('chargeuse',this)">
            <div class="cat-ico"><i class="fas fa-truck-loading"></i></div>
            <div class="cat-name">Chargeuse</div>
            <div class="cat-count" id="count-chargeuse">—</div>
        </div>
        <div class="cat-card" onclick="filterByType('compacteur',this)">
            <div class="cat-ico"><i class="fas fa-compress-alt"></i></div>
            <div class="cat-name">Compacteur</div>
            <div class="cat-count" id="count-compacteur">—</div>
        </div>
        <div class="cat-card" onclick="filterByType('tractopelle',this)">
            <div class="cat-ico"><i class="fas fa-truck-monster"></i></div>
            <div class="cat-name">Tractopelle</div>
            <div class="cat-count" id="count-tractopelle">—</div>
        </div>
        <div class="cat-card" onclick="filterByType('camion',this)">
            <div class="cat-ico"><i class="fas fa-truck"></i></div>
            <div class="cat-name">Camion</div>
            <div class="cat-count" id="count-camion">—</div>
        </div>
    </div>
</div>
</section>

{{-- ════ §3 FEATURED MACHINES ════ --}}
<section class="catalogue">
<div class="container">
    <div class="section-header anim-up">
        <span class="eyebrow">Engins vedettes</span>
        <h2 class="section-title">Engins <em>disponibles</em> maintenant</h2>
        <p class="section-lead" style="margin:0 auto;max-width:500px">Des machines de qualité, géolocalisées et prêtes à louer partout au Maroc.</p>
    </div>
    <div class="machines-grid" id="machines-grid">
        @for($i=0;$i<6;$i++)
        <div style="border-radius:16px;overflow:hidden;border:1px solid var(--cream3);background:#fff">
            <div style="background:var(--cream2);height:170px;display:flex;align-items:center;justify-content:center">
                <div style="width:110px;height:110px;border-radius:50%;background:var(--cream3);animation:pulse 1.5s infinite"></div>
            </div>
            <div style="padding:1rem 1.25rem">
                <div style="height:14px;background:var(--cream3);border-radius:4px;margin-bottom:8px;animation:pulse 1.5s infinite"></div>
                <div style="height:10px;background:var(--cream3);border-radius:4px;width:60%;margin:0 auto 16px;animation:pulse 1.5s infinite"></div>
                <div style="height:36px;background:var(--cream3);border-radius:8px;animation:pulse 1.5s infinite"></div>
            </div>
        </div>
        @endfor
    </div>
    <div class="catalogue-cta anim-up" style="text-align:center;margin-top:2.5rem">
        <a href="/machines" class="btn-primary"><i class="fas fa-th-large"></i> Voir tous les engins</a>
    </div>
</div>
</section>

{{-- ════ §4 LATEST MACHINES ════ --}}
<section class="latest">
<div class="container">
    <div class="section-header anim-up">
        <span class="eyebrow">Nouveautés</span>
        <h2 class="section-title">Derniers engins <em>ajoutés</em></h2>
        <p class="section-lead" style="margin:0 auto;max-width:500px">Les machines les plus récentes sur la plateforme.</p>
    </div>
    <div class="latest-grid" id="latest-grid">
        @for($i=0;$i<6;$i++)
        <div style="background:var(--cream);border:1px solid var(--cream3);border-radius:14px;padding:1.1rem;display:flex;gap:1rem;align-items:center">
            <div style="width:72px;height:72px;border-radius:50%;background:var(--cream3);flex-shrink:0;animation:pulse 1.5s infinite"></div>
            <div style="flex:1">
                <div style="height:12px;background:var(--cream3);border-radius:4px;margin-bottom:8px;animation:pulse 1.5s infinite"></div>
                <div style="height:10px;background:var(--cream3);border-radius:4px;width:70%;animation:pulse 1.5s infinite"></div>
            </div>
        </div>
        @endfor
    </div>
</div>
</section>

{{-- ════ §5 GALERIE ════ --}}
<section class="gallery">
<div class="container">
    <div class="section-header anim-up" style="text-align:center">
        <span class="eyebrow">Notre flotte</span>
        <h2 class="section-title">Des engins <em>professionnels</em></h2>
    </div>
    <div class="gallery-grid anim-up">
        <div class="gal-item gal-item--big">
            <img src="{{ asset('images/img6.png') }}" alt="Parc d'engins">
            <div class="gal-overlay"></div>
            <div class="gal-label"><i class="fas fa-map-marker-alt" style="margin-right:5px"></i>Casablanca</div>
            <div class="gal-tag">Tractopelle</div>
        </div>
        <div class="gal-item"><img src="{{ asset('images/img2.png') }}" alt="JCB"><div class="gal-overlay"></div><div class="gal-label">Chargeuse JCB</div></div>
        <div class="gal-item"><img src="{{ asset('images/img4.png') }}" alt="Grue"><div class="gal-overlay"></div><div class="gal-label">Grue Manitou</div></div>
        <div class="gal-item"><img src="{{ asset('images/img1.png') }}" alt="CAT"><div class="gal-overlay"></div><div class="gal-label">CAT 963D</div></div>
        <div class="gal-item"><img src="{{ asset('images/img5.png') }}" alt="MT"><div class="gal-overlay"></div><div class="gal-label">Manitou MT</div></div>
    </div>
</div>
</section>

{{-- ════ §6 COMMENT ÇA MARCHE ════ --}}
<section class="how">
<div class="container">
    <div class="section-header anim-up" style="text-align:center">
        <span class="eyebrow">Processus</span>
        <h2 class="section-title">Comment <em>ça marche</em> ?</h2>
    </div>
    <div class="steps-grid">
        <div class="step anim-up" style="animation-delay:.05s">
            <div class="step-num">01</div>
            <div class="step-ico"><i class="fas fa-search"></i></div>
            <div class="step-title">Cherchez un engin</div>
            <div class="step-desc">Filtrez par type, ville et budget pour trouver l'engin adapté.</div>
        </div>
        <div class="step anim-up" style="animation-delay:.1s">
            <div class="step-num">02</div>
            <div class="step-ico"><i class="fas fa-calendar-check"></i></div>
            <div class="step-title">Choisissez vos dates</div>
            <div class="step-desc">Sélectionnez votre période et vérifiez la disponibilité.</div>
        </div>
        <div class="step anim-up" style="animation-delay:.15s">
            <div class="step-num">03</div>
            <div class="step-ico"><i class="fas fa-file-signature"></i></div>
            <div class="step-title">Contrat PDF signé</div>
            <div class="step-desc">Un contrat légal est généré automatiquement et envoyé.</div>
        </div>
        <div class="step anim-up" style="animation-delay:.2s">
            <div class="step-num">04</div>
            <div class="step-ico"><i class="fas fa-hard-hat"></i></div>
            <div class="step-title">Démarrez le chantier</div>
            <div class="step-desc">L'engin est livré sur site, votre projet peut démarrer.</div>
        </div>
    </div>
</div>
</section>

{{-- ════ §7 AVANTAGES — CLAIR ════ --}}
<section class="advantages">
<div class="container">
    <div class="adv-inner">
        <div class="adv-left anim-up">
            <span class="eyebrow">Pourquoi Rentify ?</span>
            <h2 class="section-title">La plateforme BTP <em>de référence</em></h2>
            <div class="adv-list">
                <div class="adv-item">
                    <div class="adv-ico"><i class="fas fa-map-marked-alt"></i></div>
                    <div><div class="adv-item-title">Géolocalisation précise</div><div class="adv-item-desc">Trouvez l'engin le plus proche de votre chantier via notre carte Leaflet.</div></div>
                </div>
                <div class="adv-item">
                    <div class="adv-ico"><i class="fas fa-file-pdf"></i></div>
                    <div><div class="adv-item-title">Contrats PDF automatiques</div><div class="adv-item-desc">Chaque réservation génère un contrat légal téléchargeable immédiatement.</div></div>
                </div>
                <div class="adv-item">
                    <div class="adv-ico"><i class="fas fa-star"></i></div>
                    <div><div class="adv-item-title">Avis clients vérifiés</div><div class="adv-item-desc">Consultez les évaluations réelles pour choisir en toute confiance.</div></div>
                </div>
                <div class="adv-item">
                    <div class="adv-ico"><i class="fas fa-robot"></i></div>
                    <div><div class="adv-item-title">Assistant IA 24h/24</div><div class="adv-item-desc">Notre chatbot répond à toutes vos questions sur les disponibilités.</div></div>
                </div>
            </div>
        </div>
        <div class="adv-right anim-up" style="animation-delay:.12s">
            <div class="adv-card">
                <div class="adv-card-tag">Chiffres clés</div>
                <h3>Rentify en <em>quelques chiffres</em></h3>
                <div class="adv-kpis">
                    <div class="adv-kpi"><div class="adv-kpi-n">120+</div><div class="adv-kpi-l">Engins</div></div>
                    <div class="adv-kpi"><div class="adv-kpi-n">480+</div><div class="adv-kpi-l">Clients</div></div>
                    <div class="adv-kpi"><div class="adv-kpi-n">8</div><div class="adv-kpi-l">Villes</div></div>
                    <div class="adv-kpi"><div class="adv-kpi-n">98%</div><div class="adv-kpi-l">Satisfaction</div></div>
                </div>
                <a href="/register" class="btn-primary" style="width:100%;justify-content:center">
                    <i class="fas fa-user-plus"></i> Rejoindre Rentify
                </a>
            </div>
        </div>
    </div>
</div>
</section>

{{-- ════ §8 VILLES ════ --}}
<section class="cities">
<div class="container">
    <div class="section-header anim-up" style="text-align:center">
        <span class="eyebrow">Couverture nationale</span>
        <h2 class="section-title">Disponible dans <em>toutes les grandes villes</em></h2>
    </div>
    <div class="cities-grid">
        <a href="/machines?city=Casablanca" class="city-card"><div class="city-ico"><i class="fas fa-city"></i></div><div class="city-name">Casablanca</div><div class="city-count">Hub principal</div></a>
        <a href="/machines?city=Rabat"      class="city-card"><div class="city-ico"><i class="fas fa-landmark"></i></div><div class="city-name">Rabat</div><div class="city-count">Capitale</div></a>
        <a href="/machines?city=Marrakech"  class="city-card"><div class="city-ico"><i class="fas fa-sun"></i></div><div class="city-name">Marrakech</div><div class="city-count">Ville ocre</div></a>
        <a href="/machines?city=Fès"        class="city-card"><div class="city-ico"><i class="fas fa-mosque"></i></div><div class="city-name">Fès</div><div class="city-count">Ville impériale</div></a>
        <a href="/machines?city=Tanger"     class="city-card"><div class="city-ico"><i class="fas fa-ship"></i></div><div class="city-name">Tanger</div><div class="city-count">Porte du détroit</div></a>
        <a href="/machines?city=Agadir"     class="city-card"><div class="city-ico"><i class="fas fa-umbrella-beach"></i></div><div class="city-name">Agadir</div><div class="city-count">Façade atlantique</div></a>
    </div>
</div>
</section>

{{-- ════ §9 TÉMOIGNAGES ════ --}}
<section class="testimonials">
<div class="container">
    <div class="section-header anim-up" style="text-align:center">
        <span class="eyebrow">Ils nous font confiance</span>
        <h2 class="section-title">Ce que disent <em>nos clients</em></h2>
    </div>
    <div class="testi-grid">
        <div class="testi anim-up" style="animation-delay:.05s">
            <div class="testi-stars">★★★★★</div>
            <p class="testi-text">"Réservation rapide et sans complications. Le contrat PDF a été généré instantanément, très professionnel."</p>
            <div class="testi-author"><div class="testi-avatar">AK</div><div><div class="testi-name">Amine Karimi</div><div class="testi-role">Chef de chantier · Casablanca</div></div></div>
        </div>
        <div class="testi anim-up" style="animation-delay:.1s">
            <div class="testi-stars">★★★★★</div>
            <p class="testi-text">"La carte interactive m'a permis de trouver une excavatrice à 3km de mon chantier. Excellent service."</p>
            <div class="testi-author"><div class="testi-avatar">SB</div><div><div class="testi-name">Sara Benali</div><div class="testi-role">Directrice BTP · Rabat</div></div></div>
        </div>
        <div class="testi anim-up" style="animation-delay:.15s">
            <div class="testi-stars">★★★★☆</div>
            <p class="testi-text">"En tant que propriétaire, Rentify m'a permis de rentabiliser ma flotte pendant les périodes creuses."</p>
            <div class="testi-author"><div class="testi-avatar">KR</div><div><div class="testi-name">Karim Radi</div><div class="testi-role">Propriétaire · Marrakech</div></div></div>
        </div>
    </div>
</div>
</section>

{{-- ════ §10 À PROPOS ════ --}}
<section id="a-propos" style="padding:5.5rem 0;background:var(--cream2);">
<div class="container">
    <div class="section-header anim-up" style="text-align:center;margin-bottom:4rem">
        <span class="eyebrow">À propos de nous</span>
        <h2 class="section-title">La plateforme BTP <em>pensée pour le Maroc</em></h2>
        <p class="section-lead" style="max-width:560px;margin:0 auto">Rentify est née d'un constat simple : la location d'engins de chantier au Maroc manquait d'une solution digitale fiable, rapide et sécurisée.</p>
    </div>

    {{-- Mission / Vision --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:4rem" class="anim-up">
        <div style="background:#fff;border:1px solid var(--cream3);border-radius:16px;padding:2rem;border-left:3px solid var(--gold)">
            <div style="width:48px;height:48px;border-radius:50%;border:2px solid var(--gold);background:var(--gold-pale);display:flex;align-items:center;justify-content:center;margin-bottom:1rem">
                <i class="fas fa-bullseye" style="color:var(--gold-dk);font-size:1.2rem"></i>
            </div>
            <h3 style="font-family:'Playfair Display',Georgia,serif;font-size:1.2rem;color:var(--txt-dark);margin-bottom:.75rem">Notre Mission</h3>
            <p style="font-size:.88rem;color:var(--txt-mid);line-height:1.75">Connecter les propriétaires d'engins BTP avec les entreprises et particuliers qui en ont besoin — simplement, rapidement et en toute sécurité. Chaque réservation génère un contrat PDF légal immédiat.</p>
        </div>
        {{-- Vision card — crème + border gold (fini navy) --}}
        <div style="background:var(--gold-pale);border:1.5px solid var(--gold);border-radius:16px;padding:2rem;border-left:3px solid var(--gold-dk)">
            <div style="width:48px;height:48px;border-radius:50%;border:2px solid var(--gold);background:#fff;display:flex;align-items:center;justify-content:center;margin-bottom:1rem">
                <i class="fas fa-eye" style="color:var(--gold-dk);font-size:1.2rem"></i>
            </div>
            <h3 style="font-family:'Playfair Display',Georgia,serif;font-size:1.2rem;color:var(--txt-dark);margin-bottom:.75rem">Notre Vision</h3>
            <p style="font-size:.88rem;color:var(--txt-mid);line-height:1.75">Devenir la référence nationale de la location d'engins BTP, en offrant la meilleure expérience digitale du secteur au Maroc et en accompagnant la transformation numérique du BTP.</p>
        </div>
    </div>

    {{-- Valeurs --}}
    <div class="section-header anim-up" style="text-align:center;margin-bottom:2rem">
        <span class="eyebrow">Nos valeurs</span>
        <h2 class="section-title">Ce qui nous <em>distingue</em></h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem;margin-bottom:4rem">
        <div class="anim-up" style="animation-delay:.05s;text-align:center;padding:1.75rem 1.25rem;background:#fff;border:1px solid var(--cream3);border-radius:16px">
            <div style="width:60px;height:60px;border-radius:50%;border:2px solid var(--gold);background:var(--gold-pale);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem"><i class="fas fa-shield-alt" style="font-size:1.4rem;color:var(--gold-dk)"></i></div>
            <div style="font-size:.95rem;font-weight:700;color:var(--txt-dark);margin-bottom:.4rem">Confiance</div>
            <div style="font-size:.8rem;color:var(--txt-mid);line-height:1.6">Contrats légaux, propriétaires vérifiés, avis authentiques.</div>
        </div>
        <div class="anim-up" style="animation-delay:.1s;text-align:center;padding:1.75rem 1.25rem;background:#fff;border:1px solid var(--cream3);border-radius:16px">
            <div style="width:60px;height:60px;border-radius:50%;border:2px solid var(--gold);background:var(--gold-pale);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem"><i class="fas fa-bolt" style="font-size:1.4rem;color:var(--gold-dk)"></i></div>
            <div style="font-size:.95rem;font-weight:700;color:var(--txt-dark);margin-bottom:.4rem">Rapidité</div>
            <div style="font-size:.8rem;color:var(--txt-mid);line-height:1.6">Réservation en 2 minutes, contrat immédiat, réponse rapide.</div>
        </div>
        <div class="anim-up" style="animation-delay:.15s;text-align:center;padding:1.75rem 1.25rem;background:#fff;border:1px solid var(--cream3);border-radius:16px">
            <div style="width:60px;height:60px;border-radius:50%;border:2px solid var(--gold);background:var(--gold-pale);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem"><i class="fas fa-map-marked-alt" style="font-size:1.4rem;color:var(--gold-dk)"></i></div>
            <div style="font-size:.95rem;font-weight:700;color:var(--txt-dark);margin-bottom:.4rem">Proximité</div>
            <div style="font-size:.8rem;color:var(--txt-mid);line-height:1.6">Géolocalisation précise, engins proches de votre chantier.</div>
        </div>
        <div class="anim-up" style="animation-delay:.2s;text-align:center;padding:1.75rem 1.25rem;background:#fff;border:1px solid var(--cream3);border-radius:16px">
            <div style="width:60px;height:60px;border-radius:50%;border:2px solid var(--gold);background:var(--gold-pale);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem"><i class="fas fa-handshake" style="font-size:1.4rem;color:var(--gold-dk)"></i></div>
            <div style="font-size:.95rem;font-weight:700;color:var(--txt-dark);margin-bottom:.4rem">Transparence</div>
            <div style="font-size:.8rem;color:var(--txt-mid);line-height:1.6">Prix clairs, avis vérifiés, aucun frais caché.</div>
        </div>
    </div>

    {{-- Équipe --}}
    <div class="section-header anim-up" style="text-align:center;margin-bottom:2rem">
        <span class="eyebrow">L'équipe</span>
        <h2 class="section-title">Derrière <em>Rentify</em></h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem" class="anim-up">
        <div style="text-align:center;padding:2rem 1.25rem;background:#fff;border:1px solid var(--cream3);border-radius:16px">
            <div style="width:70px;height:70px;border-radius:50%;background:var(--gold);border:3px solid rgba(212,175,55,.3);display:flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:800;color:var(--txt-dark);margin:0 auto 1rem">F</div>
            <div style="font-size:.95rem;font-weight:700;color:var(--txt-dark)">Fadwa Ait lahbib</div>
            <div style="font-size:.78rem;color:var(--gold-dk);margin-top:3px;font-weight:600">Développeuse Full Stack</div>
            <div style="font-size:.78rem;color:var(--txt-light);margin-top:6px">Laravel · Vue.js · MySQL</div>
        </div>
        <div style="text-align:center;padding:2rem 1.25rem;background:#fff;border:1px solid var(--cream3);border-radius:16px">
            {{-- Avatar S — crème+gold (fini navy) --}}
            <div style="width:70px;height:70px;border-radius:50%;background:var(--gold-pale);border:3px solid var(--gold);display:flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:800;color:var(--gold-dk);margin:0 auto 1rem">S</div>
            <div style="font-size:.95rem;font-weight:700;color:var(--txt-dark)">Salma Najem</div>
            <div style="font-size:.78rem;color:var(--gold-dk);margin-top:3px;font-weight:600">Développeuse Full Stack</div>
            <div style="font-size:.78rem;color:var(--txt-light);margin-top:6px">Laravel · Blade · JS</div>
        </div>
        <div style="text-align:center;padding:2rem 1.25rem;background:#fff;border:1px solid var(--cream3);border-radius:16px">
            <div style="width:70px;height:70px;border-radius:50%;background:var(--cream2);border:3px solid rgba(212,175,55,.3);display:flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:800;color:var(--txt-dark);margin:0 auto 1rem">&#x1F3D7;</div>
            <div style="font-size:.95rem;font-weight:700;color:var(--txt-dark)">Rentify</div>
            <div style="font-size:.78rem;color:var(--gold-dk);margin-top:3px;font-weight:600">Projet de Fin d'Études</div>
            <div style="font-size:.78rem;color:var(--txt-light);margin-top:6px">Développement Digital</div>
        </div>
    </div>
</div>
</section>

{{-- ════ §11 CTA FINAL ════ --}}
<section class="cta-final">
<div class="container">
    <div class="cta-final-inner anim-up">
        <span class="eyebrow" style="display:block;margin-bottom:1rem">Commencez maintenant</span>
        <h2>Votre prochain chantier mérite<br>les <em>meilleurs engins</em></h2>
        <p>Inscrivez-vous gratuitement et accédez à plus de 120 engins BTP disponibles partout au Maroc.</p>
        <div class="cta-btns">
            <a href="/register" class="btn-primary"><i class="fas fa-user-plus"></i> Créer un compte gratuit</a>
            <a href="/machines" class="cta-outline"><i class="fas fa-search"></i> Parcourir le catalogue</a>
        </div>
    </div>
</div>
</section>

@endsection

@push('scripts')
<script>
/* ── PHOTO MAPPING ── */
const TYPE_PHOTO = {
    excavatrice:'/images/img3.png', grue:'/images/img4.png',
    bulldozer:'/images/img1.png',   chargeuse:'/images/img2.png',
    compacteur:'/images/img8.png',  nacelle:'/images/img5.png',
    tractopelle:'/images/img7.png', camion:'/images/img9.png',
};
const FALLBACKS=['/images/img7.png','/images/img2.png','/images/img4.png','/images/img1.png'];
let _pi=0;
function getPhoto(m){ return TYPE_PHOTO[(m.type||'').toLowerCase().trim()] || FALLBACKS[_pi++%FALLBACKS.length]; }

/* ── LOADING ── */
(function(){
    const bar=document.getElementById('ls-bar'),ls=document.getElementById('loading-screen');
    let p=0;
    const iv=setInterval(()=>{p+=Math.random()*18;if(p>90)p=90;bar.style.width=p+'%';},120);
    window.addEventListener('load',()=>{clearInterval(iv);bar.style.width='100%';setTimeout(()=>ls.classList.add('hide'),400);});
})();

/* ── FAVORIS ── */
function buildFavBtn(machineId,extraClass='mc-fav'){
    const active=isFavorite(machineId);
    return `<button class="${extraClass}${active?' active':''}"
        onclick="event.stopPropagation();handleFav(this,${machineId})" title="Favori">
        <i class="${active?'fas':'far'} fa-heart"></i></button>`;
}
function handleFav(btn,id){
    const added=toggleFavorite(id);
    btn.classList.toggle('active',added);
    btn.querySelector('i').className=added?'fas fa-heart':'far fa-heart';
    showFlash(added?'❤️ Ajouté aux favoris':'Retiré des favoris',added?'success':'warning');
}

/* ── WHATSAPP ── */
function waLink(phone,machineName){
    const msg=encodeURIComponent(`Bonjour, je suis intéressé par la location de "${machineName}" sur Rentify.`);
    const p=(phone||'212600000000').replace(/\D/g,'');
    return `https://wa.me/${p}?text=${msg}`;
}

/* ── BUILD FEATURED CARD ── */
function buildCard(m){
    const photo=getPhoto(m);
    const price=Number(m.price_per_day||0).toLocaleString('fr-MA');
    const rating=m.avg_rating
        ?`<i class="fas fa-star"></i> ${parseFloat(m.avg_rating).toFixed(1)}`
        :'<i class="fas fa-star"></i> Nouveau';
    const wa=waLink(m.owner?.phone,m.name);
    return `
    <div class="mc" onclick="window.location='/machines/${m.id}'">
      ${buildFavBtn(m.id)}
      <div class="mc-head">
        <div class="mc-circle-photo">
          <img src="${photo}" alt="${m.name}" onerror="this.src='/images/img7.png'">
        </div>
        <span class="mc-type-badge">${m.type||'Engin'}</span>
      </div>
      <div class="mc-body">
        <div class="mc-name">${m.name||'—'}</div>
        <div class="mc-city"><i class="fas fa-map-marker-alt"></i>${m.city||m.location||'Maroc'}</div>
        <div class="mc-price-row">
          <div class="mc-price">${price} <small>MAD/j</small></div>
          <div class="mc-rating">${rating}</div>
        </div>
        <div class="mc-actions" onclick="event.stopPropagation()">
          <a class="mc-btn mc-btn-detail"   href="/machines/${m.id}"><i class="fas fa-eye"></i> Détails</a>
          <a class="mc-btn mc-btn-reserver" href="/machines/${m.id}"><i class="fas fa-calendar-check"></i> Réserver</a>
          <a class="mc-btn mc-btn-whatsapp" href="${wa}" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
        </div>
      </div>
    </div>`;
}

/* ── BUILD LATEST CARD ── */
function buildLatestCard(m){
    const photo=getPhoto(m);
    const price=Number(m.price_per_day||0).toLocaleString('fr-MA');
    return `
    <div class="lm-card" onclick="window.location='/machines/${m.id}'">
        <div class="lm-new-badge">Nouveau</div>
        <div class="lm-photo"><img src="${photo}" alt="${m.name}" onerror="this.src='/images/img7.png'"></div>
        <div class="lm-info">
            <div class="lm-name">${m.name||'—'}</div>
            <div class="lm-meta"><i class="fas fa-map-marker-alt"></i>${m.city||'Maroc'} &nbsp;·&nbsp; <i class="fas fa-tag"></i>${m.type||'—'}</div>
            <div class="lm-price">${price} MAD/j</div>
        </div>
        <button class="lm-fav${isFavorite(m.id)?' active':''}"
            onclick="event.stopPropagation();handleFav(this,${m.id})" title="Favori">
            <i class="${isFavorite(m.id)?'fas':'far'} fa-heart"></i>
        </button>
    </div>`;
}

/* ── DEMO DATA ── */
const DEMO_FEATURED=[
    {id:1,name:'Chargeuse CAT 963D',    type:'chargeuse',   price_per_day:2200,city:'Casablanca',owner:{phone:'212600000001'}},
    {id:2,name:'Excavatrice Poclain 90',type:'excavatrice', price_per_day:1800,city:'Rabat',     owner:{phone:'212600000002'}},
    {id:3,name:'Grue Manitou LTM',      type:'grue',        price_per_day:3200,city:'Marrakech', owner:{phone:'212600000003'}},
    {id:4,name:'Tractopelle JCB 3CX',   type:'tractopelle', price_per_day:1600,city:'Fès',       owner:{phone:'212600000004'}},
    {id:5,name:'Nacelle Manitou MT',    type:'nacelle',     price_per_day:2800,city:'Tanger',    owner:{phone:'212600000005'}},
    {id:6,name:'Compacteur Komatsu',    type:'compacteur',  price_per_day:1900,city:'Agadir',    owner:{phone:'212600000006'}},
];
const DEMO_LATEST=[
    {id:7,name:'Bulldozer D65 Komatsu',type:'bulldozer',  price_per_day:2600,city:'Casablanca'},
    {id:8,name:'Camion Benne Scania',  type:'camion',     price_per_day:1400,city:'Rabat'},
    {id:9,name:'Chargeuse JCB 206',    type:'chargeuse',  price_per_day:1900,city:'Marrakech'},
    {id:1,name:'Grue Liebherr LTM',    type:'grue',       price_per_day:4200,city:'Tanger'},
    {id:2,name:'Excavatrice CAT 320',  type:'excavatrice',price_per_day:2900,city:'Fès'},
    {id:3,name:'Nacelle UpRight',      type:'nacelle',    price_per_day:1200,city:'Agadir'},
];

let allMachines=[];

async function loadMachines(){
    try {
        const d=await API.get('/api/machines?status=available&per_page=50');
        allMachines=d?.data||(Array.isArray(d)?d:[]);
        if(!allMachines.length) allMachines=DEMO_FEATURED;
    } catch(e){ allMachines=DEMO_FEATURED; }
    renderFeatured(allMachines.slice(0,6));
    updateCategoryCounts(allMachines);
    const latest=[...allMachines].sort((a,b)=>new Date(b.created_at||0)-new Date(a.created_at||0)).slice(0,6);
    renderLatest(latest.length?latest:DEMO_LATEST);
}

function renderFeatured(list){
    document.getElementById('machines-grid').innerHTML=list.length
        ?list.map(buildCard).join('')
        :`<div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--txt-light)">
           <i class="fas fa-search" style="font-size:2rem;display:block;margin-bottom:.75rem;opacity:.3"></i>
           Aucun engin trouvé</div>`;
}
function renderLatest(list){ document.getElementById('latest-grid').innerHTML=list.map(buildLatestCard).join(''); }

function updateCategoryCounts(list){
    const types=['excavatrice','grue','bulldozer','chargeuse','compacteur','tractopelle','camion'];
    document.getElementById('count-all').textContent=list.length+' engins';
    types.forEach(t=>{
        const el=document.getElementById('count-'+t);
        if(el){ const n=list.filter(m=>(m.type||'').toLowerCase()===t).length; el.textContent=n+' engin'+(n>1?'s':''); }
    });
}

function filterByType(type,btn){
    document.querySelectorAll('.cat-card').forEach(c=>c.classList.remove('active'));
    btn.classList.add('active');
    const filtered=type?allMachines.filter(m=>(m.type||'').toLowerCase()===type):allMachines;
    renderFeatured(filtered.slice(0,6));
    document.getElementById('search-type').value=type;
}

function doSearch(){
    const type=document.getElementById('search-type').value;
    const city=document.getElementById('search-city').value;
    const budget=document.getElementById('search-budget').value;
    const p=new URLSearchParams();
    if(type)   p.set('type',type);
    if(city)   p.set('city',city);
    if(budget) p.set('max_price',budget);
    window.location='/machines'+(p.toString()?'?'+p:'');
}
document.getElementById('search-budget')?.addEventListener('keydown',e=>{ if(e.key==='Enter') doSearch(); });

(function(){
    const obs=new IntersectionObserver(entries=>{
        entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('visible');obs.unobserve(e.target);}});
    },{threshold:0.1});
    document.querySelectorAll('.anim-up').forEach(el=>obs.observe(el));
})();

loadMachines();
</script>
@endpush
