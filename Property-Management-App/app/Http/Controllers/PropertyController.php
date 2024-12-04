<?php

namespace App\Http\Controllers;
use App\Models\Property;

use App\Models\Amenity;
use App\Models\PropertyAmenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
class PropertyController extends Controller
{
    
    public function listamenities(){
$amenities= Amenity::all();
return view('propertylisting',compact('amenities'));
    }
    
    public function insertproperty(Request $request){
        $property= new Property();
        $amenities= $request->amenities;
        $user = Auth::user();
$property->user_id=$user->id;
$property->title= $request->title;
$property->description=$request->description;
$property->images_path= $request->imagearray;
$property->location= $request->location;
$property->latitude= $request->latitude;
$property->longitude= $request->longitude;
$pricepernight= $request->price+ $request->cleaning_fee + $request->security_deposit;
$property->price_per_night= $pricepernight;
$property->cleaning_fee= $request->cleaning_fee;
$property->security_deposit= $request->security_deposit;
$property->cancellation_policy= $request->cancellation_policy;
$property->start_date= $request->start_date;
$property->end_date= $request->end_date;
if ($request->has('is_active')){
$property->is_available= 1;
}
else{
    $property->is_available= 0;
}
$property->save();

foreach($amenities as $amenity){
    $amenity_object = Amenity::select('id')->where('amenity','=',$amenity)->first();
$amenity_id= $amenity_object->id;
$amintiyinsert= new PropertyAmenity();
$propid= Property::max('id');
$amintiyinsert->property_id=$propid;
$amintiyinsert->amenity_id=$amenity_id;
$amintiyinsert->save();
}
return "Property is inserted successfully!";
    }

    public function uploadImage(Request $request)
{
   
    $request->validate([
        'image' => 'required|image|max:2048',
    ]);

   
    $file = $request->file('image');

    $fileName = $file->getClientOriginalName();

    $destinationPath = public_path('assets');

    $file->move($destinationPath, $fileName);

    Log::info('File saved: ' . $fileName . ' at path: ' . $destinationPath);

    return response()->json([
        'success' => true,
        'fileName' => $fileName,
    ]);
}

    public function deleteImage(Request $request)
{
    $request->validate([
        'fileName' => 'required|string',
    ]);

    $filePath = public_path('assets' . DIRECTORY_SEPARATOR . $request->fileName);  

    \Log::info('Attempting to delete file at path: ' . $filePath);

    if (File::exists($filePath)) {
        File::delete($filePath); 
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'File not found'], 404);
}



}
