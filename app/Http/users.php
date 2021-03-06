<?php

require_once __DIR__.'/../Models/Users.php';

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as Image;

$app->get('/users', function() use ($app)
{
    return response()->json(User::all());
});

$app->get('/users/{id}', function($id = NULL) use ($app)
{
    return response()->json(getUserById($id));
});

$app->post('/users', function(Request $req) use ($app)
{
    function public_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }

    $user = new User();
    if(Input::file())
    {
        $image = Input::file('image');
        $filename  = time() . '.' . $image->getClientOriginalExtension();

        $path = public_path('assets/' . $filename);

        Image::make($image->getRealPath())->save($path);
        $user->image = $path;
    }
    return response()->json(save($user, $req, "User cannot been created."));
});

$app->put('/users/{id}', function(Request $req, $id) use ($app)
{
    $user = getUserById($id);

    if (!is_null($user)) {
        $response = save($user, $req, "User cannot been updated.");
    } else {
        $response['message'] = "User not found.";
    }

    return response()->json($response);
});

$app->delete('/users/{id}', function(Request $req, $id) use ($app)
{
    $user = getUserById($id);

    if (!is_null($user)) {
        $response['success'] = $user->delete();
        if ($response['success']) {
            $response['message'] = "User deleted successfully!";
        } else {
            $response['message'] = "User cannot been deleted";
        }
    } else {
        $response['message'] = "User not found.";
    }
    return response()->json($response);
});

function getUserById ($id)
{
    return User::where('id','=',$id)->first();
}

function save ($user, $req, $error_message)
{

    $isValid = $req->has('name') && $req->has('email');

    if ($isValid) {
        $user->name = $req->input('name');
        $user->email = $req->input('email');
        $response['success'] = $user->save();

        if ($response['success']) {
            $response['data'] = $user;
        } else {
            $response['message'] = $error_message;
        }

    } else {
        $response['message'] = "Name and Email are required fields";
    }

    return $response;
};
