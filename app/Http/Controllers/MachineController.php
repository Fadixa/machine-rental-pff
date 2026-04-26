<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\MachineImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MachineController extends Controller
{
    // GET /api/machines — liste publique avec filtres
    public function index(Request $request)
    {
        $machines = Machine::available()
            ->when($request->type,     fn($q) => $q->byType($request->type))
            ->when($request->location, fn($q) => $q->byLocation($request->location))
            ->when(
                $request->start_date && $request->end_date,
                fn($q) => $q->freeOn($request->start_date, $request->end_date)
            )
            ->with(['primaryImage', 'owner:id,name,phone'])
            ->withAvg('ratings', 'rating')
            ->paginate(12);

        return response()->json($machines);
    }

    // GET /api/machines/{machine} — fiche détaillée
    public function show(Machine $machine)
    {
        $machine->load([
            'images',
            'owner:id,name,phone,ville',
            'ratings.client:id,name',
        ]);
        $machine->ratings_avg = $machine->averageRating();

        return response()->json($machine);
    }

    // POST /api/machines — créer une annonce (owner seulement)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'nullable|string|max:100',
            'description'    => 'nullable|string',
            'price_per_day'  => 'required|numeric|min:0',
            'price_per_hour' => 'required|numeric|min:0',
            'location'       => 'nullable|string|max:255',
            'images'         => 'nullable|array|max:8',
            'images.*'       => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $machine = $request->user()->machines()->create($data);

        // Upload des images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('machines', 'public');
                MachineImage::create([
                    'machine_id' => $machine->id,
                    'image_url'  => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return response()->json(
            $machine->load('images'), 201
        );
    }

    // PUT /api/machines/{machine} — modifier (owner seulement)
    public function update(Request $request, Machine $machine)
    {
        if ($request->user()->id !== $machine->owner_id) {
            return response()->json(['message' => 'Interdit'], 403);
        }

        $data = $request->validate([
            'name'           => 'sometimes|string|max:255',
            'type'           => 'sometimes|nullable|string',
            'description'    => 'sometimes|nullable|string',
            'price_per_day'  => 'sometimes|numeric|min:0',
            'price_per_hour' => 'sometimes|numeric|min:0',
            'location'       => 'sometimes|nullable|string',
            'status'         => 'sometimes|in:available,unavailable',
        ]);

        $machine->update($data);
        return response()->json($machine);
    }

    // DELETE /api/machines/{machine}
    public function destroy(Request $request, Machine $machine)
    {
        if ($request->user()->id !== $machine->owner_id) {
            return response()->json(['message' => 'Interdit'], 403);
        }
        // Supprimer les fichiers images du storage
        foreach ($machine->images as $img) {
            Storage::disk('public')->delete($img->image_url);
        }
        $machine->delete();
        return response()->json(null, 204);
    }

    // GET /api/my-machines — mes machines (owner)
    public function myMachines(Request $request)
    {
        $machines = $request->user()
            ->machines()
            ->with('primaryImage')
            ->withCount('reservations')
            ->latest()
            ->get();
        return response()->json($machines);
    }
}