# 🏗️ RENTIFY — PROMPT COMPLET V13 — NOUVELLE CONVERSATION

## 🧠 CONTEXTE PROJET

Je travaille sur **Rentify** — une plateforme de location de machines de travaux publics (BTP) au Maroc.

**Stack:** Laravel 11 + Blade + Sanctum + MySQL (`rentify`)
**Auth:** 100% JS via `localStorage` (`auth_token`, `auth_user`)
**Frontend:** Blade + Vanilla JS
**Fonts:** Playfair Display + DM Sans (Google Fonts)

---

## 🎨 DESIGN SYSTEM V12 — Gold / Crème (STRICT — 100% CLAIR, ZÉRO NAVY BG)

```css
--gold:     #D4AF37
--gold-dk:  #9A7D20
--gold-lt:  #F5E88A
--gold-pale:#FEF9E7
--gold-glow:rgba(212,175,55,.25)
--navy:     #0F1B2D   /* textes seulement — JAMAIS en background */
--navy2:    #162540   /* textes seulement — JAMAIS en background */
--navy3:    #1E3356   /* textes seulement — JAMAIS en background */
--cream:    #FAF7F0
--cream2:   #F0EBE0
--cream3:   #E8DDD0
--txt-dark: #1a1a2e
--txt-mid:  #5a5660
--txt-light:#9992a4
```

**⛔ INTERDIT absolument :**
- `#F59E0B`, `#D97706`, `#B45309`, `#ca8a04`, `#c07a00`, `#BB6800`
- Classes Tailwind : `amber-*`, `orange-*`
- Variables CSS : `--orange`, `--orange2`
- `rgba(245,158,11,...)` → remplacer par `rgba(212,175,55,...)`
- `#fef9c3`, `#FEF3C7`, `#FFFBEB` → remplacer par `#FEF9E7`
- **Navy en background** partout (sidebar, header, modal, toast, photo bg…)

**Règles backgrounds :**
- Fonds principaux → `var(--cream)` ou `#fff`
- Blocs colorés → `var(--gold-pale)` + `border: 1.5px solid var(--gold)`
- Photos/médias → `var(--cream2)`
- Toasts → `#fff` + `color:var(--txt-dark)` + `border-left:4px solid var(--gold/green/red)`
- Sidebars → `background:var(--cream)` + `border-right:1.5px solid rgba(212,175,55,.2)`
- Profile header → `var(--gold-pale)` + border gold

**Boutons :**
- `.btn-gold` → `background:var(--gold); color:var(--txt-dark)`
- `.btn-outline` → `background:#fff; border:2px solid var(--gold); color:var(--gold-dk)`
- `.btn-navy` → **alias rétrocompat** = même style que `.btn-gold`
- `.btn-save`, `.btn-cta`, `.btn-reserve` → gold bg + txt-dark

**Badges status :**
- `pending` → `background:#FEF9E7; color:var(--gold-dk)`
- `accepted/completed` → `background:#dcfce7; color:#15803d`
- `rejected/cancelled` → `background:#fee2e2; color:#b91c1c`
- `maintenance/unavailable` → `background:#FEF9E7; color:var(--gold-dk)`

**3D Palettes (Three.js) :** `0xD4AF37`, `0x9A7D20` — JAMAIS orange

---

## 🗄️ BASE DE DONNÉES

```
users        : id, name, email, password, role(admin|owner|client),
               phone, city, bio, is_suspended, profile_photo_path,
               remember_token, timestamps
machines     : id, owner_id, name, type, description, price_per_day,
               price_per_hour, status(available|unavailable|maintenance),
               city, location, latitude, longitude
reservations : id, machine_id, client_id, start_date, end_date,
               status(pending|accepted|rejected|completed|cancelled),
               total_price, motif
machine_images: id, machine_id, path
ratings      : id, machine_id, user_id, rating, comment
```

⚠️ `type` (PAS category) | `price_per_day` (PAS daily_price) | `total_price` OBLIGATOIRE
⚠️ Photo profil = `profile_photo_path` dans DB et dans les réponses API

---

## 👤 COMPTES TEST (password: password)

| Rôle   | Email               |
|--------|---------------------|
| Admin  | admin@rentify.ma    |
| Owner  | karim@rentify.ma    |
| Client | fadwa@rentify.ma    |

---

## 🖼️ IMAGES MACHINES (`public/images/`)

```js
const TYPE_PHOTO = {
  excavatrice:'/images/img3.png', grue:'/images/img4.png',
  bulldozer:'/images/img1.png',   chargeuse:'/images/img2.png',
  compacteur:'/images/img8.png',  nacelle:'/images/img5.png',
  tractopelle:'/images/img7.png', camion:'/images/img9.png',
};
```

**Logo :** `public/images/logo.jpeg`
- Blade : `{{ asset('images/logo.jpeg') }}`
- JS/HTML : `/images/logo.jpeg`

---

## 🗺️ ROUTES WEB

```php
Route::get('/',                 fn()      => view('welcome'));
Route::get('/machines',         fn()      => view('machines.index'));
Route::get('/machines/create',  fn()      => view('machines.create'));
Route::get('/machines/{id}',    fn($id)   => view('machines.show', ['id'=>$id]))->where('id','[0-9]+');
Route::get('/machines/{id}/edit',fn($id)  => view('machines.create', ['id'=>$id,'editMode'=>true]))->where('id','[0-9]+');
Route::get('/login',            fn()      => view('auth.auth_sliding'));
Route::get('/register',         fn()      => view('auth.auth_sliding'));
Route::get('/dashboard/client', fn()      => view('dashboard.client'));
Route::get('/dashboard/owner',  fn()      => view('dashboard.owner'));
Route::get('/dashboard/admin',  fn()      => view('admin.dashboard'));
Route::get('/profile',          fn()      => view('profile.index'));
Route::get('/contact',          fn()      => view('contact'));
Route::get('/favoris',          fn()      => view('client.favorites'));
```

## 🔌 ROUTES API (auth:sanctum)

```
GET/POST    /api/machines
PUT/DELETE  /api/machines/{id}
GET         /api/my-machines
GET/POST    /api/reservations
PATCH       /api/reservations/{id}/accept|reject|complete|cancel
GET         /api/reservations/{id}/contrat
GET|PUT     /api/profile
PUT         /api/profile/password
POST        /api/profile/avatar          ← upload photo profil
POST        /api/machines/{id}/ratings
GET         /api/admin/stats|users|etc.
POST        /api/login
POST        /api/register
POST        /api/logout
GET         /api/machines/map-data
POST        /api/chatbot
```

---

## 🔧 API HELPER GLOBAL (`layouts/app.blade.php`)

```js
window.API.get(url)           // retourne data directement
window.API.post(url, body)    // retourne {ok, status, data}
window.API.put/patch/delete   // idem
window.getUser()              // localStorage auth_user
window.getToken()             // localStorage auth_token
window.getFavorites()         // array d'ids
window.toggleFavorite(id)
window.refreshFavsBadge()
window.showFlash(msg, type)   // success | error | warning
window.isFavorite(id)
window.refreshNavAvatar()     // ← V13 : sync avatar navbar après upload photo
```

⚠️ `API.get()` retourne la data directement — toujours : `d?.data || (Array.isArray(d) ? d : [])`
⚠️ `API.post/put` retourne `{ ok, status, data }`

---

## 🧭 NAVBAR PAR RÔLE

```
GUEST:  Accueil | Machines | À propos | Contact | [Connexion] [S'inscrire]
CLIENT: Accueil | Machines | À propos | Contact | Mon espace | [avatar dropdown]
OWNER:  Accueil | Machines | Dashboard | À propos | Contact | [+ Ajouter machine] | [avatar]
ADMIN:  Dashboard | Contact | [avatar]
```

**Avatar navbar V13 :** affiche `profile_photo_path` si disponible, sinon initiale.
`window.refreshNavAvatar()` disponible globalement pour sync sans reload.

---

## 🔔 NOTES THREE.JS r128

- OrbitControls **NON disponible** en CDN r128 → orbit manuel mouse/touch
- `destroyMiniViewer()` à chaque changement de tab
- `machine.type` lowercase pour palette 3D
- Palettes : `0xD4AF37` (gold), `0x9A7D20` (gold-dk) — JAMAIS orange

---

## 🔐 AUTH SLIDING PANEL

Page auth unique avec slide asymétrique :
- **Login** : gauche formulaire / droite overlay crème+gold
- **Register** : overlay glisse droite→gauche, formulaire apparaît droite
  - Step 1 : choix rôle (Client / Propriétaire)
  - Step 2 : formulaire (Nom, Email, Téléphone, MDP)
- **Transition :** `0.65s cubic-bezier(.77,0,.175,1)`
- **JS :** fetch → token localStorage → redirect par rôle
- **URL :** `?mode=register` → ouvre register directement

---

## ✅ ÉTAT DES PAGES — V13

| Fichier | État | Notes |
|---------|------|-------|
| `layouts/app.blade.php` | ✅ V13 | Avatar photo navbar, refreshNavAvatar() |
| `welcome.blade.php` | ✅ V10 | 100% gold/crème, zéro navy bg |
| `machines/index.blade.php` | ✅ V9 | Orange→gold, Leaflet map, filtres |
| `machines/show.blade.php` | ✅ V9 | 3D viewer, booking, ratings |
| `admin/dashboard.blade.php` | ✅ V8 | Stats + charts + tables |
| `dashboard/client.blade.php` | ✅ V10 | Navy→cream, toast blanc, btn gold |
| `client/favorites.blade.php` | ✅ V10 | Navy→cream, btn-reserve gold |
| `dashboard/owner.blade.php` | ✅ V11 | Sidebar cream, btn-navy→gold, 3D palette |
| `auth/auth_sliding.blade.php` | ✅ | Déjà conforme |
| `chatbot/widget.blade.php` | ✅ V11 | Header cream, gold system |
| `profile/index.blade.php` | ⚠️ V12 | Fix photo persist — à tester upload→refresh |

---

## 🗄️ USER MODEL — V13 COMPLET

```php
// app/Models/User.php
protected $fillable = [
    'name', 'email', 'password', 'role', 'phone',
    'city', 'bio', 'is_suspended', 'profile_photo_path',
];
protected $hidden = ['password', 'remember_token'];
```

---

## 🔧 PROFILECONTROLLER — V13 COMPLET

```php
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $u = $request->user();
        return response()->json(['data' => [
            'id'                 => $u->id,
            'name'               => $u->name,
            'email'              => $u->email,
            'phone'              => $u->phone,
            'city'               => $u->city,
            'bio'                => $u->bio,
            'role'               => $u->role,
            'profile_photo_path' => $u->profile_photo_path,
        ]]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'city'  => 'nullable|string|max:100',
            'bio'   => 'nullable|string|max:500',
        ]);
        $u = $request->user();
        $u->update($request->only('name','phone','city','bio'));
        return response()->json(['data' => [
            'id'                 => $u->id,
            'name'               => $u->name,
            'email'              => $u->email,
            'phone'              => $u->phone,
            'city'               => $u->city,
            'bio'                => $u->bio,
            'role'               => $u->role,
            'profile_photo_path' => $u->profile_photo_path,
        ]]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);
        $u = $request->user();
        if (!Hash::check($request->current_password, $u->password)) {
            return response()->json(['message' => 'Mot de passe actuel incorrect'], 422);
        }
        $u->update(['password' => Hash::make($request->password)]);
        return response()->json(['message' => 'Mot de passe modifié avec succès']);
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|image|max:2048']);
        $u    = $request->user();
        $path = $request->file('avatar')->store('avatars', 'public');
        $u->profile_photo_path = $path;
        $u->save();
        return response()->json(['data' => [
            'profile_photo_path' => $path,
        ]]);
    }
}
```

---

## 🐛 BUGS CORRIGÉS (historique complet)

- `API.get()` 401 → clear localStorage avant redirect
- KPIs → `Array.isArray()` guard
- `doLogout()` → clear token AVANT API call + `window.location.replace`
- `filterMapByCity()` → `normaliserTexte()` robuste
- DB seed : machines avec `owner_id`
- Route fallback : `abort(404)`
- Dashboard owner : `bulldozer 3D palette 0xF97316 → 0xD4AF37`
- Profile V11 : `u.avatar → u.profile_photo_path` (mismatch DB)
- **V13 : User `$fillable` manquait `city`, `bio`, `is_suspended`, `profile_photo_path`**
- **V13 : `GET /api/profile` retournait 500 → colonnes manquantes en DB**
- **V13 : Navbar avatar affichait toujours l'initiale — ignorait `profile_photo_path`**
- **V13 : `loadProfile()` écrasait `profile_photo_path` avec null au refresh**

---

## 🚀 PROCHAINES ÉTAPES

### 1. ⚠️ Tester upload photo profil end-to-end
- Upload → preview ✓
- Après refresh → photo toujours là ✓
- Navbar → montre la photo (pas initiale) ✓

### 2. `admin/dashboard.blade.php` — Audit V13
→ Vérifier badges, charts, boutons (encore V8)

### 3. Test e2e complet
- Login → reserve → accept → PDF contrat → download ✓
- Upload photo → refresh → photo toujours là ✓
- Owner : add machine → client reserve → owner accept ✓

### 4. Soutenance — Préparer démo
- Données réalistes dans DB (machines avec photos, réservations variées)
- Screenshot/vidéo de chaque feature

---

## 🏗️ FEATURES BACK-END COMPLÉTÉES

- Auth Sanctum — tokens localStorage, redirect par rôle
- Machines CRUD — images multiples, map-data GPS
- Réservations — accept/reject/complete/cancel + emails auto
- Contrat PDF — barryvdh/laravel-dompdf
- Emails — ReservationAccepted/Rejected/New (MAIL_MAILER=log)
- Carte GPS Leaflet — markers, filterMapByCity()
- Ratings — par machine, moyenne front
- Chatbot IA — keyword-based, 13 catégories
- Page Contact — validation JS
- Erreurs — errors/404.blade.php
- Profile — CRUD complet + upload avatar
