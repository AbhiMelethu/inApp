<?php

namespace App\Http\Controllers;

use Exception;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\ImportRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ImportController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function view(Request $request): View
    {
        return view('import.index');
    }

    /**
     * Import the user's information.
     */
    public function import(ImportRequest $request): RedirectResponse
    {
        $file = $request->file('csv_file');
        //Using PHP's fgetcsv
        if ($file->isValid()) {
            $isFirstRow = true;
            //Validate unique email validation
            $isEmailError = $this->validateUniqueEmail($file,$isFirstRow);
           if($isEmailError == false){
                //Insert
                $file = fopen($file, 'r');
                while (($row = fgetcsv($file)) !== false) {
                    // Skip the first row
                    if ($isFirstRow) {
                        $isFirstRow = false;
                        continue;
                    }
                    //Save
                    $this->saveData($row);
                }
                fclose($file);
            }else{
                //error
                return redirect()->back()->with('error', 'The email should be unique');
            }
        }
        return redirect()->back()->with('success', 'CSV imported successfully');
    }

    /**
     * Validate email
     * @param mixed $file
     * @param mixed $isFirstRow
     * @return bool
     */
    private function validateUniqueEmail($file,$isFirstRow)
    {
        //Check
        $file = fopen($file, 'r');
        while (($row = fgetcsv($file)) !== false) {
            // Skip the first row
            if ($isFirstRow) {
                $isFirstRow = false;
                continue;
            }
            //Save
            $email[] = $row[2];
        }
        fclose($file);
        $uniqueEmails = array_unique($email);
        // Check if the number of unique elements is less than the original array size
        if (count($uniqueEmails) < count($email)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Save user data
     * @param array $data
     * @return string
     */
    private function saveData($data)
    {
        //Psw
        $randomString = Str::random(10);
        //Prepare data
        $array = [
            'name' => $data[0],
            'last_name' => $data[1],
            'email' => $data[2],
            'phone' => $data[3],
            'doj' => $this->convertDateFormat($data[4]),
            'designation' => $data[5],
            'password' => Hash::make($randomString),
        ];
        //Check already exists
        $userModel = User::select('id')->where('email', $data[2])->first();
        //Save
        if(empty($userModel)){
            //Insert
            User::create($array);
        }else{
            //Update
            User::where('id', $userModel->id)->update($array);
        }
        //Send mail
        $this->sendMail($array,$randomString);
    }

    /**
     * sendMail
     * @param array $array
     * @param string $randomString
     * @return void
     */
    private function sendMail($array,$randomString)
    {
        $link = route("login");
        $forgotLink = route("password.request");
        $html = 'Hi '.$array['name'].''.'<br>';
        $html.='To log in, please click on the <a href='.$link.'>link</a> and enter your credentials.'.'<br>';
        $html.='User Name: '.$array['email'].''.'<br>';
        $html.='Password: '.$randomString.''.'<br>';
        $html.='Or please click this <a href='.$forgotLink.'>link</a> to rest the password.'.'<br>';
        //Prepare data
        $data = [
            'subject' => 'InApp: User created successfully',
            'view' => $html,
        ];
        try{
            Mail::to($array['email'])->send(new SendMail($data));
         }catch(Exception $e){
            echo 'Caught exception: ',  $e->getMessage(), "\n";
         }
    }

    /**
     * Convert date
     * @param string $date
     * @return string
     */
    private function convertDateFormat($date)
    {
       return date("Y-m-d", strtotime($date));
    }

}
