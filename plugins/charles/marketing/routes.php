<?php 

use Charles\Marketing\Models\Client;
use Charles\Marketing\Models\Project;
use Charles\Marketing\Models\Settings;
use Charles\Marketing\Models\Competence;


Route::options('api/clients', function() {
    return Response::make('You are connected to the API');
});

Route::get('api/clients', function() {
	$clients = Client::get();
	return $clients;
});

Route::options('api/projects', function() {
    return Response::make('You are connected to the API');
});

Route::get('api/projects', function() {
	$data = Project::with('main_picture', 'pictures', 'client')->get();
	return $data;
});

Route::options('api/settings', function() {
    return Response::make('You are connected to the API');
});

Route::get('api/settings', function() {
	$data['settings'] = Settings::instance()->value;
	$data['projects'] = Project::with('main_picture', 'client')->get();
	$data['clients'] = Client::get();
	$data['competences'] = Competence::get();
	return $data;
});
Route::options('api/project/{any}', function() {
    return Response::make('You are connected to the API');
});

Route::get('api/project/{slug}', function($slug) {
	$data = Project::where('slug', $slug)->with('main_picture', 'video_picture', 'pictures', 'client')->first();
	return $data;
});