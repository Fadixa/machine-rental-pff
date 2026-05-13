<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * GET /api/machines/{machine}/ratings
     * Récupérer tous les avis d'une machine (public)
     */
    public function index(Machine $machine)
    {
        $ratings = $machine->ratings()
            ->with('client:id,name')
            ->latest()
            ->get();

        return response()->json($ratings);
    }

    /**
     * POST /api/machines/{machine}/ratings
     * Créer un avis (client connecté)
     */
    public function store(Request $request, Machine $machine)
    {
        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Vérifier si le client a déjà noté cette machine
        $existing = Rating::where('client_id', $request->user()->id)
                          ->where('machine_id', $machine->id)
                          ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Vous avez déjà laissé un avis pour cette machine.'
            ], 422);
        }

        $rating = Rating::create([
            'client_id'  => $request->user()->id,
            'machine_id' => $machine->id,
            'rating'     => $data['rating'],
            'comment'    => $data['comment'] ?? null,
        ]);

        return response()->json($rating->load('client:id,name'), 201);
    }
}