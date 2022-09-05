<?php

namespace App\Http\Api\V1;

use App\Http\Api\Controller as Controller;
use Illuminate\Http\Request;
use App\Models\University;
use App\Models\Review; 
use App\Models\Favourite;
use Illuminate\Support\Arr;
use Validator;
use Illuminate\Support\Facades\Cache;

class ProfileController extends Controller
{
    public function show($university_id){

        // Check if it is a numeric value
        if (!is_numeric($university_id)) {
            return $this->returnError('Bad request', ['error'=>'Incorrect university id.'], 400);
        }

        // Find the university
        $university = University::where('enabled', true)
            ->where('id', $university_id)
            ->first();

        // Return an error if the uni profile is not enabled
        if(!isset($university) || !$university){
            return $this->returnError('Bad request', ['error'=>'The university profile is not enabled.'], 400);
        }

        // Get cached uni profile
        $profile = Cache::rememberForever('uni_profile_'.$university_id, function () use ($university) {

            // University with basic profile
            if(!$university->premium){

                // Set the basic profile
                $profile = [
                    'name'              => $university->name,
                    'description'       => $university->description,
                    'logo_image_path'   => $university->logo_image_path,
                ];

            }else{    

                // University with premium profile
                $university_reviews = Review::select('id','user_name','review_comment','rating','updated_at')
                ->where('university_id', $university->id)
                ->get();

                // Set the premium profile
                $profile = [
                    'name'              => $university->name,
                    'description'       => $university->description,
                    'phone_number'      => $university->phone_number,
                    'address'           => $university->address,
                    'logo_image_path'   => $university->logo_image_path,
                    'website'           => $university->website,
                    'rating'            => $university->rating,
                    'university_reviews'=> $university_reviews,
                ];            
            }
            
            return $profile;
        });

        // Return "university saved as favourite" if it is an authenticated user
        if (auth('sanctum')->user()) {
            $saved_as_favourite = Favourite::where('user_id', auth('sanctum')->user()->id)
                ->where('university_id', $university_id)
                ->exists();
            $profile = Arr::add($profile, 'saved_as_favourite', $saved_as_favourite ? 1 : 0);
        }
        
        //Set the message
        $message = $university->premium ? "University premium profile found" : "University basic profile found";

        // Return the response
        return $this->returnResponse($profile, $message);
    }
}
