<?php

namespace App\Http\Api\V1;

use App\Http\Api\Controller as Controller;
use Illuminate\Http\Request;
use App\Models\Favourite;
use Validator;

class FavouriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all favourites
        $favourites = Favourite::where('user_id', auth('sanctum')->user()->id)
            ->get();

        // Set the result
        $result = [
            'total' => $favourites->count(),
            'favourites' => $favourites
        ];

        // Return the response
        return $this->returnResponse($result, 'All favourites');
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
        ]);

        // Return errors if validation fails
        if($validator->fails()){
            return $this->returnError('Validation Error', $validator->errors(), 400);       
        }

        // Store the favourite if the model can not be found in the database
        $favourite = Favourite::firstOrCreate([
            'user_id' => auth('sanctum')->user()->id,
            'university_id' => $request->input('university_id')
        ]);

        // Return the response
        if(isset($favourite->id)){
            return $this->returnResponse($favourite, 'The university has been added as favourite.');
        }else{
            return $this->returnError('Bad request', ['error'=>'The university has already saved as favourite.'], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($university_id)
    {
        // Find the favourite
        $favourite = Favourite::where('user_id', auth('sanctum')->user()->id)
            ->where('university_id', $university_id)
            ->first();

        // Return the response
        if($favourite){
            return $this->returnResponse($favourite, 'The favourite has been found.');
        }else{
            return $this->returnError('Bad request', ['error'=>'The favourite has not been found.'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $university_id)
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
        // Delete the favourite
        $deleted = Favourite::where('user_id', auth('sanctum')->user()->id)
            ->where('university_id', $university_id)
            ->delete();

        // Return the response
        if($deleted){
            return $this->returnResponse('', 'The university has been removed from favourites.');
        }else{
            return $this->returnError('Bad request', ['error'=>'The university has not been found.'], 400);
        }
    }
}
