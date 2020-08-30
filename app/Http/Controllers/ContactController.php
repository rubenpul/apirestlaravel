<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Contact;



use Unirest\Request as RequestClient;

class ContactController extends Controller
{
    
    public function register(Request $request){ 
        
        //get data contact
        $data = $request->input("json",null);

        //get image Profile
        $image = $request->file('image0');

        $params = json_decode($data,true);

        

        if (!empty($params) && $image != null){

            $params = array_map('trim',$params);

            //data validate

            $validate = Validator::make($params, [
                'idDocument' => 'required|numeric|unique:tbl_contact',
                'firstName' => 'required|alpha|max:45',
                'middleName' => 'required|alpha|max:45',
                'lastName' => 'required|alpha|max:45',
                'birthdate' => 'required|date',
                'phonePersonal' => 'required|numeric',
                'phoneWork' => 'numeric',
                'email' => 'required|email',
                'idCountry' => 'required|alpha',
                'idState' => 'required|alpha',
                'idCity' => 'required|numeric',
                'street' => 'required|regex:/^[\pL\s\-]+$/u',
                'number' => 'required|numeric',
                'zipCode' => 'required|max:10',
                'companyName' => 'required',
            ]);

            //verify if contact exists in database 

            if ($validate->fails()){
            
                $data = $this->arrayJson('error','400','Contact not created',$validate->errors());
                
            }
            else{

                //validate format image

                $validate = Validator::make($request->all(), [
                    'image0' => 'required|image|mimes:jpg,jpeg,png,gif,jfif'
                ]);

                if ($validate->fails()){
                    $data = $this->arrayJson('error','400','Error in Validating Image format',$validate->errors());
                }
                else{    

                     //save image in Storage folder 

                     $image_name = time().$image->getClientOriginalName();
                     Storage::disk('imageProfile')->put($image_name,File::get($image));

                    //create object contact

                    $contact = new Contact();
                    $contact->idDocument = $params['idDocument'];
                    $contact->firstName = $params['firstName'];
                    $contact->middleName = $params['middleName'];
                    $contact->lastName = $params['lastName'];
                    $contact->birthdate = $params['birthdate'];
                    $contact->phonePersonal = $params['phonePersonal'];
                    $contact->phoneWork = $params['phoneWork'];
                    $contact->email = $params['email'];
                    $contact->idCountry = $params['idCountry'];
                    $contact->idState = $params['idState'];
                    $contact->idCity = $params['idCity'];
                    $contact->street = $params['street'];
                    $contact->number = $params['number'];
                    $contact->zipCode = $params['zipCode'];
                    $contact->companyName = $params['companyName'];
                    $contact->imageProfile = $image_name;

                    //save to database
                    try {
                        $contact->save();
                        $data = $this->arrayJson('success','200','Data saved in database');
                    } catch (QueryException $e) {
                        $data = $this->arrayJson('error','500','QueryException',$e);          
                    }
                }    
            }
        }
        else{
            $data = $this->arrayJson('error','400','Data send are not correct','Json or Image Empty');
        }

        return response()->json($data,$data['code']);

    }

    public function update(Request $request){
        
        //get data contact

        $data = $request->input("json",null);

        $params = json_decode($data,true);

        if (!empty($params)){

            $params = array_map('trim',$params);

            //data validate

            $validate = Validator::make($params, [
                'firstName' => 'alpha|max:45',
                'middleName' => 'alpha|max:45',
                'lastName' => 'alpha|max:45',
                'birthdate' => 'date',
                'phonePersonal' => 'numeric',
                'phoneWork' => 'numeric',
                'email' => 'email',
                'idCountry' => 'alpha',
                'idState' => 'alpha',
                'idCity' => 'numeric',
                'street' => 'regex:/^[\pL\s\-]+$/u',
                'number' => 'numeric',
                'zipCode' => 'max:10',
                'companyName' => 'max:45'
            ]);

            //verify if contact exists in database 

            if ($validate->fails()){
            
                $data = $this->arrayJson('error','400','Contact not updated',$validate->errors());
                
            }
            else{
            
                //unset fields

                unset($params['created_at']);

                //update contact to database
                try {
                    $contact_update = Contact::where('idDocument',$params['idDocument'])->update($params);

                    if ($contact_update){
                        $data = $this->arrayJson('success','200','Data updated in database');    
                    }
                    else{
                        $data = $this->arrayJson('error','400','There is no Contact to update','No Data');
                    }
                    
                } catch (QueryException $e) {
                    $data = $this->arrayJson('error','500','QueryException',$e);          
                }
            }
        }
        else{
            $data = $this->arrayJson('error','400','Data send are not correct','Json Empty1');
        }

        return response()->json($data,$data['code']);

    }

    public function updateImageProfile(Request $request){

        //retrieve data

        $imageNew = $request->file('imageNew');
        $imageOld = $request->input('imageOld');

        //validate if it's a image to upload

        $validate = Validator::make($request->all(), [
            'imageNew' => 'required|image|mimes:jpg,jpeg,png,gif,jfif'
        ]);

        if ($validate->fails()){
            $data = $this->arrayJson('error','400','Error in Validating Image format',$validate->errors());
        }
        else{
            //save image
            if ($imageNew){
                $image_name = time().$imageNew->getClientOriginalName();
                Storage::disk('imageProfile')->put($image_name,File::get($imageNew));

                Storage::disk('imageProfile')->delete($imageOld);

                $data = $this->arrayJson('success','200','image uploaded succesfully');
            }
            else{
                $data = $this->arrayJson('error','400','error in upload image','no upload');
            }
        }    
        //result

        return response()->json($data,$data['code'])->header('Content-Type','text/plain');

    }

    public function delete($idDocument){
      
        //delete contact to database
        try {

            $contact = Contact::where('idDocument',$idDocument)->get();

            if (is_object($contact)){
                
                $contact_delete = Contact::where('idDocument',$idDocument)->delete();

                if($contact_delete){
                    if ($contact[0]["imageProfile"] != null){
                        Storage::disk('imageProfile')->delete($contact[0]["imageProfile"]);
                    }    
                    $data = $this->arrayJson('success','200','Data deleted in database');
                }
                else{
                    $data = $this->arrayJson('error','400','There is no Contact Register to delete','No Data');
                }
            }    
            else{
                $data = $this->arrayJson('error','400','There is no Contact Register to delete','No Data');
            }    
            
        } catch (QueryException $e) {
            $data = $this->arrayJson('error','500','QueryException',$e);          
        }
    
        return response()->json($data,$data['code']);
    }

    public function deleteImage($imageProfile){

        
            //delete image
            if (!empty($imageProfile)){
               
                Storage::disk('imageProfile')->delete($imageProfile);

                $data = $this->arrayJson('success','200','image deleted succesfully');
            }
            else{
                $data = $this->arrayJson('error','400','error in delete image','no delete');
            }
        
            //result

             return response()->json($data,$data['code']);

    }

    public function upload(Request $request){

        //retrieve data

        $image = $request->file('image0');

        

        //validate if it's a image to upload

        $validate = Validator::make($request->all(), [
            'image0' => 'required|image|mimes:jpg,jpeg,png,gif,jfif'
        ]);

        if ($validate->fails()){
            $data = $this->arrayJson('error','400','Error in Validating Image format',$validate->errors());
        }
        else{
            //save image
            if ($image){
                $image_name = time().$image->getClientOriginalName();
                Storage::disk('imageProfile')->put($image_name,File::get($image));

                $data = $this->arrayJson('success','200','image uploaded succesfully');
            }
            else{
                $data = $this->arrayJson('error','400','error in upload image','no upload');
            }
        }    
        //result

        return response()->json($data,$data['code'])->header('Content-Type','text/plain');

    }

    public function getImageProfile($fileName){

        $exists = Storage::disk('imageProfile')->exists($fileName);

        if ($exists){
            $file = Storage::disk('imageProfile')->get($fileName);

            return new Response($file,200);
        }
        else{
            $data = $this->arrayJson('error','400','error in getting the image','image don´t exists in database');  
            return response()->json($data,$data['code']);
        }
    }

    public function getContact($idDocument){

        $contact = Contact::where('idDocument',$idDocument)->get();

        if (is_object($contact)){
            $data = $this->arrayJson('success','200',$contact);  
        }
        else{
            $data = $this->arrayJson('error','404','Contact doesn´t exist in database','No data'); 
        }

        return response()->json($data,$data['code']);

    }

    public function getAllContacts(){

        $contact = Contact::orderBy('firstName')->get();

        if (is_object($contact)){
            $data = $this->arrayJson('success','200',$contact);  
        }
        else{
            $data = $this->arrayJson('error','404','There is no contacts in database','No data'); 
        }

        return response()->json($data,$data['code']);

    }

    public function getContactByEmailPhone(Request $request){

        $data = $request->input("json",null);

        $params = json_decode($data,true);

        if (!empty($params)){

            $params = array_map('trim',$params);

            //data validate

            $validate = Validator::make($params, [
                'phonePersonal' => 'numeric',
                'phoneWork' => 'numeric',
                'email' => 'email',
            ]);

            //verify if contact exists in database 

            if ($validate->fails()){
            
                $data = $this->arrayJson('error','400','Entry Information can´t been validated',$validate->errors());
                
            }
            else{

                $contact = Contact::where('phonePersonal', isset($params['phonePersonal'])?$params['phonePersonal']:null)
                                    ->orWhere('phoneWork',isset($params['phoneWork'])?$params['phoneWork']:null)
                                    ->orWhere('email',isset($params['email'])?$params['email']:null)
                                    ->get();
               
                $data = $this->arrayJson('sucess','200',$contact);
            }
        }
        else{
            $data = $this->arrayJson('error','400','Data sent are not correct','Json Empty');
        }

        return response()->json($data,$data['code']);

    

    }

    public function getContactByStateCity(Request $request){

        $data = $request->input("json",null);

        $params = json_decode($data,true);

        if (!empty($params)){

            $params = array_map('trim',$params);

            //data validate

            $validate = Validator::make($params, [
                'idState' => 'alpha',
                'idCity' => 'numeric'
            ]);

            //retrieve data contact  in database 

            if ($validate->fails()){
            
                $data = $this->arrayJson('error','400','Entry Information can´t been validated',$validate->errors());
                
            }
            else{

                $contact = Contact::where('idState', isset($params['idState'])?$params['idState']:null)
                                    ->orWhere('idCity',isset($params['idCity'])?$params['idCity']:null)
                                    ->get();
               
                $data = $this->arrayJson('sucess','200',$contact);
            }
        }
        else{
            $data = $this->arrayJson('error','400','Data sent are not correct','Json Empty');
        }

        return response()->json($data,$data['code']);

    }

    public function getCountries(){


        $response = RequestClient::get("https://wft-geo-db.p.rapidapi.com/v1/geo/countries/",
        array(
            "X-RapidAPI-Host" => "wft-geo-db.p.rapidapi.com",
            "X-RapidAPI-Key" => "3e10ecace2mshaa7138a255ef641p171edfjsn8b8b674995be"
        ));
        
        return response()->json( $response->body,200);

    }  

    public function getStates($idCountry){


        $response = RequestClient::get("https://wft-geo-db.p.rapidapi.com/v1/geo/countries/". $idCountry . "/regions",
        array(
            "X-RapidAPI-Host" => "wft-geo-db.p.rapidapi.com",
            "X-RapidAPI-Key" => "3e10ecace2mshaa7138a255ef641p171edfjsn8b8b674995be"
        ));
        
        return response()->json( $response->body,200);

    }    

    public function getCities($idCountry,$idState){


        $response = RequestClient::get("https://wft-geo-db.p.rapidapi.com/v1/geo/countries/" . $idCountry . "/regions/" . $idState . "/cities",
        array(
            "X-RapidAPI-Host" => "wft-geo-db.p.rapidapi.com",
            "X-RapidAPI-Key" => "3e10ecace2mshaa7138a255ef641p171edfjsn8b8b674995be"
        ));
        
        return response()->json($response->body,200);

    }  

    public function arrayJson($status,$code,$message,$errors="no errors"){

        $data = array(
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'errors' => $errors
        );

        return $data;
    }

}
