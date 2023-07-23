<?php

namespace App\Http\Controllers;

use App\Models\offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    // Get all offers
    public function index()
    {
        $offers = Offer::all();
        return response()->json($offers);
    }

    // Get a specific offer by ID
    public function show($id)
    {
        $offer = Offer::find($id);

        if (!$offer) {
            return response()->json(['message' => 'Offer not found'], 404);
        }

        return response()->json($offer);
    }

    // Create a new offer
    public function store(Request $request)
    {
        $offer = new Offer();
        $offer->company = $request->input('company');
        $offer->title = $request->input('title');
        $offer->type = $request->input('type');
        $offer->attendance = $request->input('attendance');
        $offer->period = $request->input('period');
        $offer->gratifications = $request->input('gratifications');
        $offer->tags = $request->input('tags');
        $offer->description = $request->input('description');
        $offer->created_by = $request->input('created_by'); // Assuming you pass the user ID in the request

        $offer->save();

        return response()->json($offer, 201);
    }

    // Update an existing offer
    public function update(Request $request, $id)
    {
        $offer = Offer::find($id);

        if (!$offer) {
            return response()->json(['message' => 'Offer not found'], 404);
        }

        // Update offer data
        $offer->company = $request->input('company');
        $offer->title = $request->input('title');
        $offer->type = $request->input('type');
        $offer->attendance = $request->input('attendance');
        $offer->period = $request->input('period');
        $offer->gratifications = $request->input('gratifications');
        $offer->tags = $request->input('tags');
        $offer->description = $request->input('description');
        
        $offer->save();

        return response()->json($offer);
    }

    // Delete an offer
    public function destroy($id)
    {
        $offer = Offer::find($id);

        if (!$offer) {
            return response()->json(['message' => 'Offer not found'], 404);
        }

        $offer->delete();

        return response()->json(['message' => 'Offer deleted'], 200);
    }
}
