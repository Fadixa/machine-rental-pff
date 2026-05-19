# 🏗 CONTEXT PROMPT — Rentify PFE Laravel 11
> **Version V3 — Mai 2026**
> Coller ce fichier au début de chaque nouvelle conversation Claude

---

## 1. LE PROJET

| Champ | Valeur |
|---|---|
| **Nom** | Rentify |
| **Sous-titre** | Plateforme de location de machines de travaux publics au Maroc |
| **Type** | PFE (Projet de Fin d'Études) |
| **URL local** | http://127.0.0.1:8000 |
| **Repo** | `C:\Users\om\Desktop\projet salma fadwa\project_machines_pfe` |

### Stack technique

| Couche | Technologie |
|---|---|
| Back-end | Laravel 11, PHP 8.2 |
| Auth | Laravel Sanctum — tokens stockés dans localStorage |
| Base de données | MySQL — database: `rentify` |
| Front-end | Blade + Vanilla JS + Bootstrap 5 |
| Icônes | Font Awesome 6 |
| Carte GPS | Leaflet.js |
| PDF | barryvdh/laravel-dompdf |
| Emails | Laravel Mail (MAIL_MAILER=log pour tests) |
| Charts | Chart.js (dashboard admin) |

### Couleurs
```css
--navy:   #0F1B2D   /* fond sombre principal */
--navy2:  #1a2d45   /* fond sombre secondaire */
--orange: #F59E0B   /* couleur principale / CTA */
--green:  #10b981
--red:    #ef4444
--gray:   #6b7280
--border: #e2e8f0
```

---

## 2. COMPTES DE TEST

| Email | Password | Role | Dashboard |
|---|---|---|---|
| admin@rentify.ma | password | admin | /dashboard/admin |
| karim@rentify.ma | password | owner | /dashboard/owner |
| fadwa@rentify.ma | password | client | /dashboard/client |

---

## 3. STRUCTURE BASE DE DONNÉES

### Table `users`
```
id, name, email, password,
phone (nullable), city (nullable), bio (nullable), avatar (nullable),
role ENUM('owner','client','admin') DEFAULT 'client',
is_suspended BOOLEAN DEFAULT false,
remember_token, timestamps
```

### Table `machines` — ⚠️ COLONNES IMPORTANTES
```
id, owner_id (FK users), name,
type,              ← PAS category
price_per_day,     ← PAS daily_price
price_per_hour,
description, status ENUM('available','rented','maintenance'),
city,              ← ville (PAS location)
location,          ← adresse détaillée (optionnel)
latitude, longitude, timestamps
```

### Table `reservations` — ⚠️ COLONNES IMPORTANTES
```
id, machine_id, client_id (FK users),
start_date, end_date,
total_price,       ← EXISTE — calculer AVANT create()
status ENUM('pending','accepted','completed','rejected'),
motif (nullable), timestamps

⚠️ PAS de owner_id     → passer par machine.owner_id
```

### Table `ratings`
```
id, client_id (FK users), machine_id (FK machines),
rating (int 1-5), comment (nullable), timestamps
```

### Table `drivers`
```
id, name, phone, city, status, latitude, longitude, timestamps
```

### Table `missions`
```
id, driver_id, machine_id, statut, timestamps
```

---

## 4. MODÈLES — POINTS CRITIQUES

### `Machine.php`
```php
// owner_id PAS user_id
Machine::where('owner_id', $user->id)->get();

// Relations
owner()        → belongsTo(User, 'owner_id')
reservations() → hasMany(Reservation)
ratings()      → hasMany(Rating)
images()       → hasMany(MachineImage)
```

### `Reservation.php` — ⚠️ PAS de owner_id dans la table
```php
// Pour avoir le owner → passer par la machine
$owner = $reservation->machine->owner;

// Pour le contrat PDF
$reservation->load('machine.owner', 'client');
$owner = $reservation->machine->owner;

// Pour l'accès sécurisé
$machineOwnerId = $reservation->machine->owner_id;
if (Auth::id() !== $reservation->client_id && Auth::id() !== $machineOwnerId) {
    return response()->json(['message' => 'Accès refusé.'], 403);
}
```

### `Rating.php`
```php
protected $fillable = ['client_id', 'machine_id', 'rating', 'comment'];
```

### `User.php`
```php
protected $fillable = ['name','email','password','role','phone'];
// city, bio, avatar aussi fillable si mis à jour
```

---

## 5. ROUTES API COMPLÈTES

### Publiques (sans token)
```
GET  /api/machines               → liste avec filtres
GET  /api/machines/{id}          → fiche détaillée
GET  /api/machines/{id}/ratings  → avis d'une machine
GET  /api/machines/map-data      → données carte Leaflet
POST /api/register
POST /api/login
POST /api/chatbot
```

### Protégées auth:sanctum
```
POST /api/logout
GET  /api/me

// Machines (owner)
GET    /api/my-machines
POST   /api/machines
PUT    /api/machines/{id}
DELETE /api/machines/{id}

// Réservations
GET   /api/reservations
POST  /api/reservations
GET   /api/reservations/{id}
PATCH /api/reservations/{id}/accept
PATCH /api/reservations/{id}/reject
PATCH /api/reservations/{id}/complete
PATCH /api/reservations/{id}/cancel
GET   /api/reservations/{id}/contrat ← PDF via machine.owner

// Ratings
POST /api/machines/{machine}/ratings

// Profile
GET  /api/profile
PUT  /api/profile
PUT  /api/profile/password
POST /api/profile/avatar

// Drivers & Missions
GET/POST /api/drivers
GET/POST /api/missions
```

### Admin (auth:sanctum + AdminMiddleware)
```
GET    /api/admin/stats
GET    /api/admin/activity
GET    /api/admin/users
PUT    /api/admin/users/{id}/suspend
PUT    /api/admin/users/{id}/activate
DELETE /api/admin/users/{id}
GET    /api/admin/machines
DELETE /api/admin/machines/{id}
GET    /api/admin/reservations
GET    /api/admin/payments
```

---

## 6. ROUTES WEB

```php
GET /                    → welcome
GET /machines            → machines.index
GET /machines/{id}       → machines.show
GET /machines/create     → machines.create
GET /login               → auth.login
GET /register            → auth.register
GET /contact             → contact
GET /profile             → profile.index
GET /dashboard/client    → dashboard.client
GET /dashboard/owner     → dashboard.owner
GET /dashboard/driver    → dashboard.driver
GET /dashboard/admin     → admin.dashboard
```

---

## 7. LOGIQUE MÉTIER IMPORTANTE

### Auth Sanctum — localStorage
```js
localStorage.setItem('auth_token', token);
localStorage.setItem('token', token);
localStorage.setItem('auth_user', JSON.stringify(user));
localStorage.setItem('user', JSON.stringify(user));

// Helpers globaux (layouts/app.blade.php)
getToken()  → lit 'auth_token' ou 'token'
getUser()   → lit 'auth_user' ou 'user' (JSON.parse)

// Redirect après login
admin  → /dashboard/admin
owner  → /dashboard/owner
driver → /dashboard/driver
client → /dashboard/client
```

### API Helper global
```js
// API.get() retourne la DATA directement
const data = await API.get('/api/machines');

// API.post() retourne {ok, status, data}
const res = await API.post('/api/reservations', body);
if (res.ok) { ... }

const list = d?.data || (Array.isArray(d) ? d : []);
```

### Catalogue machines — Filtres API
```js
// ⚠️ Utiliser 'city' PAS 'location' pour le filtre ville
params.set('city', filters.ville);    // ✅
params.set('location', filters.ville); // ❌ BUG

// ⚠️ Afficher m.city PAS m.location dans les cards
${m.city || '—'}    // ✅
${m.location || '—'} // ❌ vide souvent
```

### MachineController::index() — Filtres supportés
```php
// q=        → search dans name, type, city, description
// type=     → filtre par type
// city=     → filtre par ville ← PAS location
// status=   → filtre par statut
// max_price= → prix max/jour
// sort=price_per_day + order=asc/desc
```

### ReservationController::store() — ⚠️ VERSION CORRECTE
```php
public function store(Request $request)
{
    $request->validate([
        'machine_id' => 'required|exists:machines,id',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date'   => 'required|date|after:start_date',
    ]);

    $machine = Machine::findOrFail($request->machine_id);

    // ⚠️ $jours AVANT create() — sinon total_price manque → 500 error
    $jours = \Carbon\Carbon::parse($request->start_date)
                ->diffInDays($request->end_date);

    $reservation = Reservation::create([
        'machine_id'  => $machine->id,
        'client_id'   => Auth::id(),
        'start_date'  => $request->start_date,
        'end_date'    => $request->end_date,
        'total_price' => $machine->price_per_day * $jours, // ⚠️ obligatoire
        'status'      => 'pending',
        // ⚠️ PAS owner_id — n'existe pas dans la table
    ]);

    try {
        $owner = $machine->owner; // ⚠️ via machine PAS $reservation->owner
        Mail::to($owner->email)->send(new NewReservationMail($reservation));
        Log::info("📧 Email envoyé à {$owner->email} — #{$reservation->id}");
    } catch (\Exception $e) {
        Log::error("❌ Échec email: " . $e->getMessage());
    }

    return response()->json([
        'message'     => 'Réservation créée avec succès.',
        'reservation' => $reservation->load('machine', 'client'),
        // ⚠️ PAS ->load('owner') — relation inexistante directe
    ], 201);
}
```

### accept() et reject() — ⚠️ owner via machine
```php
// ❌ FAUX — owner_id n'existe pas dans reservations
if ($reservation->owner_id !== Auth::id()) { ... }

// ✅ CORRECT
if ($reservation->machine->owner_id !== Auth::id()) { ... }

// ✅ Et pour fresh() — PAS 'owner' direct
$reservation->fresh(['machine', 'client'])
// ❌ PAS
$reservation->fresh(['machine', 'client', 'owner'])
```

### Contrat PDF — ⚠️ Via machine.owner
```php
$reservation->load('machine.owner', 'client');
$owner = $reservation->machine->owner; // ← PAS $reservation->owner

$pdf = Pdf::loadView('pdf.contrat', [
    'reservation' => $reservation,
    'machine'     => $reservation->machine,
    'client'      => $reservation->client,
    'owner'       => $reservation->machine->owner,
]);
```

### Feature D — WhatsApp
```js
function whatsappResBtn(r) {
    if (r.status !== 'accepted') return '';
    const phone = r.machine?.owner?.phone ?? null;
    if (!phone) return '';
    let num = phone.replace(/\s+/g,'').replace(/^0/,'212');
}

// API doit retourner owner.phone :
->with(['machine:id,name,type,city,price_per_day,owner_id', 'machine.owner:id,name,phone'])
```

---

## 8. BOUTON RÉSERVER — STYLE OPTION 4 ✅

Style navy/orange — choisi et validé :

```css
/* Dans machines/index.blade.php ET machines/show.blade.php */
.btn-reserver {
    background: #0F1B2D !important;
    color: #F59E0B !important;
    border: 1.5px solid #F59E0B !important;
    border-radius: 12px;
    font-weight: 800;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
    padding: 10px 24px;
}
.btn-reserver:hover {
    background: #F59E0B !important;
    color: #0F1B2D !important;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(245,158,11,.4);
}
```

Si Bootstrap override — utiliser inline style :
```html
<button onclick="reserver()"
  style="background:#0F1B2D;color:#F59E0B;border:1.5px solid #F59E0B;..."
  onmouseover="this.style.background='#F59E0B';this.style.color='#0F1B2D'"
  onmouseout="this.style.background='#0F1B2D';this.style.color='#F59E0B'">
  <i class="fas fa-paper-plane"></i> Envoyer une demande
</button>
```

---

## 9. VUES BLADE — ÉTAT ACTUEL

| Vue | Statut | Notes |
|---|---|---|
| `layouts/app.blade.php` | ✅ | navbar + footer + API helper global |
| `welcome.blade.php` | ✅ | landing page + loading screen SVG |
| `machines/index.blade.php` | ✅ | catalogue + filtres city + carte Leaflet |
| `machines/show.blade.php` | ✅ | redesign + 3D viewer + avis + WhatsApp |
| `machines/create.blade.php` | ✅ | |
| `dashboard/client.blade.php` | ✅ | tabs: stats/réservations/contrat PDF + WhatsApp |
| `dashboard/owner.blade.php` | ✅ | |
| `dashboard/driver.blade.php` | ✅ | |
| `admin/dashboard.blade.php` | ✅ | stats + users + machines + réservations |
| `profile/index.blade.php` | ✅ | tabs: infos/sécurité/historique |
| `pdf/contrat.blade.php` | ✅ | |
| `chatbot/widget.blade.php` | ✅ | bouton flottant |
| `contact.blade.php` | ✅ | |
| `errors/404.blade.php` | ✅ | |

---

## 10. BUGS CORRIGÉS ✅

| Bug | Solution |
|---|---|
| 401 loop infini au logout | `doLogout()` → clear token AVANT appel API |
| `/profile` page blanche | fichier était vide → rempli complet |
| Header profile vide | `readUser()` lit les deux clés localStorage |
| Avatar se reset au Save | `saveProfile()` ne touche plus l'avatar |
| `getUser is not defined` | `@extends('layouts.app')` manquait |
| `ville` column not found | `owner:id,name,phone,city` |
| NaN dans les prix | `daily_price` → `price_per_day` |
| `category` column not found | `category` → `type` partout |
| Rating 500 error | `Rating.php` model était vide → ajout `$fillable` |
| Rating 405 Method Not Allowed | JS appelait `/api/ratings` → `/api/machines/{id}/ratings` |
| Machines count = 0 | `Machine::create()` bloqué → `DB::table()->insert()` |
| `Mon profil` lien cassé | href `/dashboard/client` → `/profile` |
| Middleware undefined | `withMiddleware` au lieu de `withExceptions` |
| Contrat PDF → Erreur 500 | `$reservation->owner` → `$reservation->machine->owner` |
| Filtre ville sans résultat | `params.set('location',...)` → `params.set('city',...)` |
| Cards affichent ville vide | `m.location` → `m.city` dans renderMachines() |
| **POST /api/reservations 500** | **`$jours` AVANT `create()` + `total_price` obligatoire** |
| **accept/reject 403 faux** | **`$reservation->machine->owner_id` au lieu de `$reservation->owner_id`** |
| **Btn Réserver style pas appliqué** | **`!important` + inline onmouseover/onmouseout** |

---

## 11. FEATURES — ÉTAT COMPLET

| Feature | Description | Statut |
|---|---|---|
| **Feature 1** | Auth + Roles (owner/client/admin) | ✅ |
| **Feature 2** | Contrat PDF | ✅ |
| **Feature 3** | Notifications Email | ✅ |
| **Feature 4** | Carte GPS Leaflet | ✅ |
| **Feature 5** | Loading Screen SVG | ✅ |
| **Feature 6** | Chatbot IA | ✅ |
| **Feature 7** | Dashboard Chauffeur | ✅ |
| **Feature 8** | Dashboard Admin | ✅ |
| **Feature B** | Page Profil `/profile` | ✅ |
| **Feature C** | WhatsApp (fiche machine + dashboard client) | ✅ |
| **Feature D** | Reservation store() corrigé | ✅ |
| **Feature E** | PWA / Mobile optimization | 🔲 |

---

## 12. DONNÉES DE DÉMONSTRATION

### ⚠️ NE JAMAIS SUPPRIMER
```
JCB 3CX Backhoe Loader   — Excavatrice — Casablanca — 2500 DH/j
Manitou MT 1840          — Manitou     — Rabat       — 1800 DH/j
Caterpillar 320 GX       — Excavatrice — Marrakech   — 3200 DH/j
Camion Benne Volvo FH16  — Camion      — Fès         — 1500 DH/j
Compacteur Bomag BW 213  — Compacteur  — Tanger      — 1200 DH/j
Grue Liebherr LTM 1100   — Grue        — Casablanca  — 5000 DH/j
Bulldozer Komatsu D65    — Bulldozer   — Agadir      — 2800 DH/j
```

### Données test
```sql
-- Réservation test (Fadwa sur JCB) — status accepted
Reservation machine_id=1, client_id=7,
start=2026-05-20, end=2026-05-25, total=12500, status=accepted

-- Owner Karim phone
UPDATE users SET phone='0612345678' WHERE email='karim@rentify.ma'
```

### ⚠️ Commandes INTERDITES
```bash
php artisan migrate:fresh        # SUPPRIME TOUT
php artisan migrate:fresh --seed # SUPPRIME TOUT
```

### Commandes sûres
```bash
php artisan migrate
php artisan optimize:clear
php artisan serve
php artisan tinker
php artisan storage:link
php artisan db:seed --class=DemoDataSeeder
```

---

## 13. DASHBOARD BLADE — TEMPLATE

```blade
@extends('layouts.app')
@push('styles') ... @endpush
@section('content')
  1. Header avec gradient navy
  2. Tabs navigation
  3. .dashboard-body — max-width 1400px
  4. KPI Grid (4 colonnes)
  5. Charts / Tables
  6. Toast + Modal confirm
@endsection
@push('scripts')
<script>
  const user = getUser();
  if (!user) window.location.replace('/login');

  async function loadData() { ... }
  loadData();
</script>
@endpush
```

---

## 14. COMMANDES UTILES

```bash
php artisan migrate
php artisan optimize:clear
php artisan serve
php artisan tinker
php artisan storage:link

# Vérifier colonnes
Schema::getColumnListing('machines');
Schema::getColumnListing('reservations'); # ← pas owner_id, a total_price
Schema::getColumnListing('users');
```

---

## 15. PREMIÈRE ACTION EN NOUVELLE SESSION

**Coller ce fichier puis dire :**

```
"Reprends le projet Rentify.
État actuel : [décrire ce qui marche / bug actuel / feature à faire]"
```

### Exemples
```
"Reprends Rentify — on attaque Feature E PWA"
"Reprends Rentify — bug sur dashboard owner"
"Reprends Rentify — on améliore le catalogue machines"
```
