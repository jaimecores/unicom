<?php

namespace App\Http\Api\V1;

use App\Http\Api\Controller as Controller;
use Illuminate\Http\Request;
use App\Models\University;
use App\Models\Favourite;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request){

        // Get the inputs from the request
        $keyword = $request->input('keyword');
        $sort = $request->input('sort');
        $order = $request->input('order');
        
        // Find the universities for the authenticated user
        if (auth('sanctum')->user()) {

            // Get the user id
            $user_id = auth('sanctum')->user()->id;

            // Set the query for the authenticated user
            $query = DB::table('universities AS u')
                ->select(DB::raw('u.id, u.name, u.reviews_count, u.rating, (SELECT (COUNT(*) > 0) FROM favourites f WHERE u.id=f.university_id and user_id='.$user_id.') AS saved_as_favourite'))
                ->where('u.enabled', true);

        }else{ 
            // Find the universities for the unauthenticated user
            $query = DB::table('universities AS u')
                ->select(DB::raw('u.id, u.name, u.reviews_count, u.rating'))
                ->where('u.enabled', true);
        }

        // Search in the title 
        if (!empty($keyword) && isset($keyword)){
            $query->where('u.name', 'like', "%{$keyword}%");
        }    
        
        // Order the results by the parameters from the request    
        if (!empty($sort) && in_array($sort, ['rating','name'])) {
            if (!empty($order) && in_array($order, ['asc','desc'])) {
                $query->orderBy($sort, $order);
            }else{
                $query->orderBy($sort);
            }
        }

        // Return the results
        $result = [
            'total' => $query->count(), 
            'results' => $query->get()
        ];

        // Return the response
        return $this->returnResponse($result, 'Search results');
    }
}
