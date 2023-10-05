<?php

namespace App\Http\Controllers;

use App\Models\Api;
use Illuminate\Support\Facades\Http;

class FetchApiController extends Controller
{
    public function index()
    {
        $apiData = Http::get('https://jsonplaceholder.typicode.com/posts');
        $apiData = json_decode($apiData->body());

        foreach ($apiData as $apiRecord) {
            // Check if a record with the same ID exists in the database
            $existingRecord = Api::where('id', $apiRecord->id)->first();

            if (!$existingRecord) {
                // If no record exists, create a new one
                $newRecord = new Api();
                $newRecord->id = $apiRecord->id; // Assuming 'id' is your unique identifier
                $newRecord->userId = $apiRecord->userId;
                $newRecord->title = $apiRecord->title;
                $newRecord->body = $apiRecord->body;
                $newRecord->save();
            } else {
                // If a record exists, update it only if the data has changed
                if ($existingRecord->userId != $apiRecord->userId ||
                    $existingRecord->title != $apiRecord->title ||
                    $existingRecord->body != $apiRecord->body) {
                    $existingRecord->userId = $apiRecord->userId;
                    $existingRecord->title = $apiRecord->title;
                    $existingRecord->body = $apiRecord->body;
                    $existingRecord->save();
                }
            }
        }

        // Fetch all data from the database to display in the view
        $data = Api::all();

        return view('fetchData', compact('data'));
    }

}
