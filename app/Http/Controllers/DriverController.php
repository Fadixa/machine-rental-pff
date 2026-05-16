<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    // GET /api/drivers — Liste tous les chauffeurs (admin/owner)
    public function index(Request $request)
    {
        $query = Driver::with('user')
            ->when($request->statut, fn($q) => $q->where('statut', $request->statut))
            ->when($request->actif !== null, fn($q) => $q->where('actif', $request->boolean('actif')));

        $drivers = $query->get()->map(fn($d) => [
            'id'                  => $d->id,
            'user_id'             => $d->user_id,
            'nom'                 => $d->user->name,
            'email'               => $d->user->email,
            'telephone'           => $d->telephone,
            'numero_permis'       => $d->numero_permis,
            'categorie_permis'    => $d->categorie_permis,
            'statut'              => $d->statut,
            'competences'         => $d->competences,
            'latitude_actuelle'   => $d->latitude_actuelle,
            'longitude_actuelle'  => $d->longitude_actuelle,
            'derniere_position_at'=> $d->derniere_position_at?->diffForHumans(),
            'missions_completees' => $d->missions_completees,
            'note_moyenne'        => $d->note_moyenne,
            'actif'               => $d->actif,
        ]);

        return response()->json($drivers);
    }

    // GET /api/drivers/map — Positions temps réel pour carte Leaflet
    public function mapData()
    {
        $drivers = Driver::with('user')
            ->avecPosition()
            ->where('actif', true)
            ->get()
            ->map(fn($d) => [
                'id'       => $d->id,
                'nom'      => $d->user->name,
                'statut'   => $d->statut,
                'lat'      => (float) $d->latitude_actuelle,
                'lng'      => (float) $d->longitude_actuelle,
                'derniere_position' => $d->derniere_position_at?->diffForHumans(),
                'mission'  => $d->missionEnCours()?->id,
            ]);

        return response()->json($drivers);
    }

    // POST /api/drivers — Créer un chauffeur (admin)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'          => 'required|exists:users,id|unique:drivers,user_id',
            'numero_permis'    => 'required|string|unique:drivers,numero_permis',
            'categorie_permis' => 'required|in:B,C,D,E,F',
            'telephone'        => 'nullable|string|max:20',
            'competences'      => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $driver = Driver::create($request->only([
            'user_id', 'numero_permis', 'categorie_permis', 'telephone', 'competences'
        ]));

        // Mettre à jour le rôle de l'utilisateur
        User::find($request->user_id)->update(['role' => 'driver']);

        return response()->json($driver->load('user'), 201);
    }

    // GET /api/drivers/{id}
    public function show(Driver $driver)
    {
        return response()->json([
            'driver'   => $driver->load('user'),
            'missions' => $driver->missions()->with('reservation.machine')->latest()->take(10)->get(),
        ]);
    }

    // PUT /api/drivers/{id}
    public function update(Request $request, Driver $driver)
    {
        $driver->update($request->only([
            'statut', 'telephone', 'competences', 'actif', 'categorie_permis'
        ]));

        return response()->json($driver->fresh('user'));
    }

    // PATCH /api/drivers/{id}/position — Mise à jour GPS du chauffeur
    public function updatePosition(Request $request, Driver $driver)
    {
        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $driver->update([
            'latitude_actuelle'    => $request->latitude,
            'longitude_actuelle'   => $request->longitude,
            'derniere_position_at' => now(),
        ]);

        return response()->json(['ok' => true, 'updated_at' => now()->toIso8601String()]);
    }

    // DELETE /api/drivers/{id}
    public function destroy(Driver $driver)
    {
        $driver->update(['actif' => false]);
        return response()->json(['message' => 'Chauffeur désactivé.']);
    }
}