<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Mission;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MissionController extends Controller
{
    // GET /api/missions
    public function index(Request $request)
    {
        $query = Mission::with(['driver.user', 'reservation.machine', 'reservation.client'])
            ->when($request->statut, fn($q) => $q->where('statut', $request->statut))
            ->when($request->driver_id, fn($q) => $q->where('driver_id', $request->driver_id))
            ->when($request->today, fn($q) => $q->aujourdhui())
            ->latest();

        return response()->json($query->get()->map(fn($m) => $this->formatMission($m)));
    }

    // POST /api/missions — Assigner une mission à un chauffeur
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver_id'            => 'required|exists:drivers,id',
            'reservation_id'       => 'required|exists:reservations,id',
            'heure_depart_prevue'  => 'required|date',
            'heure_arrivee_prevue' => 'nullable|date|after:heure_depart_prevue',
            'lat_depart'           => 'nullable|numeric',
            'lng_depart'           => 'nullable|numeric',
            'lat_destination'      => 'nullable|numeric',
            'lng_destination'      => 'nullable|numeric',
            'adresse_depart'       => 'nullable|string',
            'adresse_destination'  => 'nullable|string',
            'instructions'         => 'nullable|string',
            'distance_km'          => 'nullable|numeric',
            'duree_estimee_min'    => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $driver = Driver::findOrFail($request->driver_id);
        if ($driver->statut === 'en_mission') {
            return response()->json(['message' => 'Ce chauffeur est déjà en mission.'], 409);
        }

        $mission = Mission::create($request->all());
        $driver->update(['statut' => 'en_mission']);

        return response()->json($this->formatMission($mission->load(['driver.user', 'reservation.machine'])), 201);
    }

    // GET /api/missions/{id}
    public function show(Mission $mission)
    {
        return response()->json($this->formatMission(
            $mission->load(['driver.user', 'reservation.machine', 'reservation.client'])
        ));
    }

    // PATCH /api/missions/{id}/statut — Changer le statut (chauffeur)
    public function updateStatut(Request $request, Mission $mission)
    {
        $request->validate([
            'statut'         => 'required|in:en_route,sur_place,en_cours,terminee,annulee',
            'notes_chauffeur'=> 'nullable|string',
            'lat'            => 'nullable|numeric',
            'lng'            => 'nullable|numeric',
        ]);

        $updates = ['statut' => $request->statut];

        if ($request->notes_chauffeur) $updates['notes_chauffeur'] = $request->notes_chauffeur;

        // Timestamps automatiques
        match($request->statut) {
            'en_route'  => $updates['heure_depart_reelle'] = now(),
            'sur_place' => $updates['heure_arrivee_reelle'] = now(),
            'terminee'  => $updates['heure_fin_reelle'] = now(),
            default     => null,
        };

        // Mise à jour position du chauffeur si fournie
        if ($request->lat && $request->lng) {
            $updates['lat_actuelle'] = $request->lat;
            $updates['lng_actuelle'] = $request->lng;
            $mission->driver->update([
                'latitude_actuelle'    => $request->lat,
                'longitude_actuelle'   => $request->lng,
                'derniere_position_at' => now(),
            ]);
        }

        $mission->update($updates);

        // Si terminée, libérer le chauffeur
        if ($request->statut === 'terminee') {
            $mission->driver->update([
                'statut'              => 'disponible',
                'missions_completees' => $mission->driver->missions_completees + 1,
            ]);
        }

        if ($request->statut === 'annulee') {
            $mission->driver->update(['statut' => 'disponible']);
        }

        return response()->json($this->formatMission($mission->fresh(['driver.user', 'reservation.machine'])));
    }

    // PATCH /api/missions/{id}/track — Mise à jour GPS de la mission
    public function track(Request $request, Mission $mission)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $mission->update([
            'lat_actuelle' => $request->lat,
            'lng_actuelle' => $request->lng,
        ]);

        $mission->driver->update([
            'latitude_actuelle'    => $request->lat,
            'longitude_actuelle'   => $request->lng,
            'derniere_position_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }

    // POST /api/missions/{id}/noter — Client note la mission
    public function noter(Request $request, Mission $mission)
    {
        $request->validate([
            'note'       => 'required|integer|between:1,5',
            'commentaire'=> 'nullable|string|max:500',
        ]);

        $mission->update([
            'note_client'       => $request->note,
            'commentaire_client'=> $request->commentaire,
        ]);

        // Recalculer note moyenne du chauffeur
        $driver = $mission->driver;
        $avg = $driver->missions()->whereNotNull('note_client')->avg('note_client');
        $driver->update(['note_moyenne' => round($avg, 2)]);

        return response()->json(['ok' => true, 'note_moyenne' => $driver->fresh()->note_moyenne]);
    }

    // GET /api/missions/planning — Planning hebdomadaire
    public function planning(Request $request)
    {
        $debut = $request->debut ? now()->parse($request->debut) : now()->startOfWeek();
        $fin   = $request->fin   ? now()->parse($request->fin)   : now()->endOfWeek();

        $missions = Mission::with(['driver.user', 'reservation.machine'])
            ->whereBetween('heure_depart_prevue', [$debut, $fin])
            ->orderBy('heure_depart_prevue')
            ->get()
            ->map(fn($m) => $this->formatMission($m));

        return response()->json($missions);
    }

    // ─── Helper format ─────────────────────────────────────
    private function formatMission(Mission $m): array
    {
        return [
            'id'                   => $m->id,
            'statut'               => $m->statut,
            'statut_icon'          => $m->statut_icon,
            'driver'               => $m->driver ? [
                'id'     => $m->driver->id,
                'nom'    => $m->driver->user->name,
                'tel'    => $m->driver->telephone,
                'statut' => $m->driver->statut,
            ] : null,
            'reservation'          => $m->reservation ? [
                'id'      => $m->reservation->id,
                'machine' => $m->reservation->machine->name ?? '',
                'client'  => $m->reservation->client->name ?? '',
            ] : null,
            'depart' => [
                'lat'     => $m->lat_depart,
                'lng'     => $m->lng_depart,
                'adresse' => $m->adresse_depart,
            ],
            'destination' => [
                'lat'     => $m->lat_destination,
                'lng'     => $m->lng_destination,
                'adresse' => $m->adresse_destination,
            ],
            'position_actuelle' => [
                'lat' => $m->lat_actuelle,
                'lng' => $m->lng_actuelle,
            ],
            'planning' => [
                'depart_prevu'    => $m->heure_depart_prevue?->format('d/m/Y H:i'),
                'depart_reel'     => $m->heure_depart_reelle?->format('d/m/Y H:i'),
                'arrivee_prevue'  => $m->heure_arrivee_prevue?->format('d/m/Y H:i'),
                'arrivee_reelle'  => $m->heure_arrivee_reelle?->format('d/m/Y H:i'),
                'fin_reelle'      => $m->heure_fin_reelle?->format('d/m/Y H:i'),
                'en_retard'       => $m->estEnRetard(),
            ],
            'distance_km'       => $m->distance_km,
            'duree_estimee_min' => $m->duree_estimee_min,
            'duree_travail_min' => $m->duree_travail,
            'instructions'      => $m->instructions,
            'notes_chauffeur'   => $m->notes_chauffeur,
            'note_client'       => $m->note_client,
            'created_at'        => $m->created_at?->format('d/m/Y H:i'),
        ];
    }
}