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
        $offer->company = $request->company;
        $offer->title = $request->title;
        $offer->type = $request->type;
        $offer->attendance = $request->get('attendance');
        $offer->period = $request->get('period');
        $offer->gratifications = $request->get('gratifications');
        $offer->tags = $request->get('tags');
        $offer->description = $request->get('description');
        $offer->created_by = $request->get('created_by'); // Assuming you pass the user ID in the request

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
        $offer->company = $request->get('company');
        $offer->title = $request->get('title');
        $offer->type = $request->get('type');
        $offer->attendance = $request->get('attendance');
        $offer->period = $request->get('period');
        $offer->gratifications = $request->get('gratifications');
        $offer->tags = $request->get('tags');
        $offer->description = $request->get('description');

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
