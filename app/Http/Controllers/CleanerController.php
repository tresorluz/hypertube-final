<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\WatchedMovie;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth;
use Illuminate\Database\Eloquent\Model;

class CleanerController extends Controller
{
    public function index()
    {
        $this->Clean_tmp();

        $date = \Carbon\Carbon::today()->subDays(30);

        $oldMovies = Film::where('updated_at', '<=', $date)->get();

        foreach ($oldMovies as $oldMovie)
        {
            $moviePath = public_path() . "/film/" . $oldMovie->hash . '.mp4';
            
            if (file_exists($moviePath)) {
                unlink($moviePath);
            }

            $oldMovie->delete();
        }
    }

    public  function RepEfface($dir)
    {
        $handle = opendir($dir);

        // Loop through to remove all the repositories & sub-repositories
        while($elem = readdir($handle))
        {
            if (is_dir($dir.'/'.$elem) &&substr($elem, -2, 2) !== '..' && substr($elem, -1, 1) !== '.') {
                $this->RepEfface($dir.'/'.$elem);
            } else {
                if(substr($elem, -2, 2) !== '..' && substr($elem, -1, 1) !== '.') {
                    unlink($dir.'/'.$elem);
                }
            }
        }

        $handle = opendir($dir);

        // Loop through to remove all the repositories
        while($elem = readdir($handle))
        {
            // Check if it is a repository
            if (is_dir($dir.'/'.$elem) && substr($elem, -2, 2) !== '..' && substr($elem, -1, 1) !== '.') {
                $this->RepEfface($dir.'/'.$elem);
                rmdir($dir.'/'.$elem);
            }

        } 
      
        rmdir($dir);      //Delete the principal repository
    }

    public function Clean_tmp()
    {
        $i = 0;
        $array;
        $b = public_path() . "/film/";

        if ($handle = opendir($b))
        {
            while (false !== ($entry = readdir($handle)))
            {
                if ($entry != '.' && $entry != '..' && substr($entry, 40) != '_tmp') {
                    $array[$i++] = $entry;
                }
            }

            closedir($handle);

            if (isset($array)) {
                foreach ($array as $key => $value)
                {

                    $filename = public_path() . "/film/" . $array[$key];
                    $date = \Carbon\Carbon::today()->subDays(30);
                    $date_modify = date("Y-m-d H:i:s", filemtime($filename));

                    if ($date >= $date_modify) {
                        // Delete folders and their files
                        unlink(public_path() . "/film/" . $array[$key]);

                        $path = substr($array[$key], 0, 40);
                        $path .= '_tmp';
                        $dir = public_path() . "/film/" . $path;

                        if (\file_exists($dir)) {
                            $this->RepEfface($dir);
                        }
                    }
                }
            }
        }
    }
}
