<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MyprofileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = auth()->user();
        return view('index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($_FILES['image']) AND $_FILES['image']['error'] == 0)
{
        // Check if the file is not too big
        if ($_FILES['image']['size'] <= 3145728 && $_FILES['image']['size'] >= 500)
        {
            // Testons si l'extension est autorisée
            $infosfichier = pathinfo($_FILES['image']['name']);
            $extension_upload = $infosfichier['extension'];
            $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
            if (in_array($extension_upload, $extensions_autorisees))
            {
                $upload_dir_name = "/public/Pictures/";
                move_uploaded_file($_FILES['image']['tmp_name'], base_path() . $upload_dir_name . basename($_FILES['image']['name']));
                //la picture est enregistre sur le serveur je dois faire la query d update de la bdd
                $users = auth()->user();
                $users->update(['path_picture' => "/Pictures/" . basename($_FILES['image']['name'])]);
                return redirect()->route('myprofile.index')->with('info', 'Your picture has been updated!');
            }
            else
            {
                return back()->withErrors([
                    'image' => 'extension non-autorisee',
                ]);
            }
        }
        else
        {
          return back()->withErrors([
              'image' => 'image trop grosse',
          ]);

        }
}
elseif (isset($_FILES['image']) AND $_FILES['image']['error'] == UPLOAD_ERR_NO_FILE)
{
  return back()->withErrors([
      'image' => 'Fichier inexistant',
  ]);
}
elseif (isset($_FILES['image']) AND $_FILES['image']['error'] == UPLOAD_ERR_PARTIAL)
{
  return back()->withErrors([
      'image' => 'fichier uploadé que partiellement',
  ]);
}
elseif (isset($_FILES['image']) AND $_FILES['image']['error'] == UPLOAD_ERR_INI_SIZE)
{
  return back()->withErrors([
      'image' => 'image trop grosse',
  ]);
}
elseif (isset($_FILES['image']) AND $_FILES['image']['error'] == UPLOAD_ERR_FORM_SIZE)
{
  return back()->withErrors([
      'image' => 'image trop grosse',
  ]);
}
elseif (!isset($_FILES['image']))
{
  return back()->withErrors([
      'image' => 'Pas de variable',
  ]);
}
else
{
  return back()->withErrors([
      'image' => 'Probleme indeterminée veuileez réssayer',
  ]);
}
        //return redirect()->route('myprofile.index')->with('info', 'Votre image de profil à bien été mis à jour !');
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $users = auth()->user();
        return view('edit_picture', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $users = auth()->user();
        return view('edit', compact('users'));
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
      $users = auth()->user();
      $id = auth()->id();

      $mail = User::where('email', $request->Email) -> first();

      if (isset($mail) && $mail->id != $id)
         return redirect()->back();
      $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|min:3,max:25',
        'last_name' => 'required|string|min:3,max:25',
        'Email' => 'required|string|email',
        'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $psw = Hash::make($request->password);

        $now = date("Y-n-j G:i:s");

        $users->where('id', $id)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->Email,
            'password' => $psw,
            'updated_at' => $now
        ]);

        return redirect()->route('myprofile.index')->with('info', 'Your account has been updated!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
