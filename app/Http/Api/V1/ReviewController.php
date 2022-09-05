<?php

namespace App\Http\Api\V1;

use App\Http\Api\Controller as Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\University;
use Validator;
use Illuminate\Support\Facades\Cache;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all cached reviews
        $reviews = Cache::rememberForever('all_reviews', function () {
            return Review::all();
        });

        // Set the result
        $result = [
            'total' => $reviews->count(),
            'reviews' => $reviews
        ];

        // Return the response
        return $this->returnResponse($result, 'All reviews');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'university_id' => ['required', 'exists:universities,id'],
            'user_name' => ['required'],
            'review_comment' => ['required'],
            'rating' => ['required', 'integer', 'between:1,5'],
        ]);

        // Return errors if validation fails
        if($validator->fails()){
            return $this->returnError('Validation Error', $validator->errors(), 400);       
        }
        
        // Set university_id
        $university_id = $request->input('university_id');

        // Store the favourite if it the model can not be found in the database
        $review = Review::create([
            'university_id' => $university_id,
            'user_name' => $request->input('user_name'),
            'review_comment' => $request->input('review_comment'),
            'rating' => $request->input('rating'),
        ]);

        // Update the reviews count and rating for the university 
        $university = University::find($university_id);
        $university->increment('reviews_count');
        $university->rating = $university->reviews->pluck('rating')->avg();
        $university->save();

        // Remove some items from cache
        Cache::forget('all_reviews');
        Cache::forget('uni_profile_'.$university_id);

        // Return the response
        return $this->returnResponse($review, 'The review has been added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Find the review
        $review = Review::find($id);

        // Return the response
        if($review){
            return $this->returnResponse($review, 'The review has been found.');
        }else{
            return $this->returnError('Bad request', ['error'=>'The review has not been found.'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Return the response
        return $this->returnError('Unauthorized', ['error'=>'User unauthorised.'], 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($university_id)
    {
        // Return the response
        return $this->returnError('Unauthorized', ['error'=>'User unauthorised.'], 401);
    }
}
