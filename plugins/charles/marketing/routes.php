<?php 

use Charles\Marketing\Models\Client;
use Charles\Marketing\Models\Project;
use Charles\Marketing\Models\Settings;
use Charles\Marketing\Models\Competence;
use Charles\Marketing\Models\Expertise;
use Charles\Marketing\Models\Target;
use Charles\Marketing\Models\Moa;


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
	$data['targets'] = Target::with('missions', 'missions.competences' )->get();
	$data['moas'] = MOA::get();
	return $data;
});
Route::options('api/competences', function() {
    return Response::make('You are connected to the API');
});

Route::get('api/competences', function() {
	 //return Competence::get();
	 $data['technical'] = Competence::CompetencetypeFilter([1,2,3,4])->get();
	 $data['marketing'] = Competence::CompetencetypeFilter([5])->get();
	 $data['soft_skills'] = Competence::CompetencetypeFilter([6])->get();
	 $data['fonctionelle'] = Competence::CompetencetypeFilter([7])->get();
	 return $data;
	 
});
Route::options('api/project/{any}', function() {
    return Response::make('You are connected to the API');
});

Route::get('api/project/{slug}', function($slug) {
	$data = Project::where('slug', $slug)->with('main_picture', 'video_picture', 'pictures', 'client')->first();
	return $data;
});

Route::options('api/expertises/{any}', function() {
    return Response::make('You are connected to the API');
});

Route::get('api/expertises/{slug}', function($slug) {
	$data = Expertise::where('slug', $slug)->with('competences', 'projects', 'projects.main_picture' )->first();
	return $data;
});
//ABANDON DES TARGETS
Route::options('api/targets', function() {
    return Response::make('You are connected to the API');
});

Route::get('api/targets', function($slug) {
	$data = Target::with('competences', 'targets')->get();
	return $data;
});

Route::options('api/targets/{any}', function() {
    return Response::make('You are connected to the API');
});

Route::get('api/targets/{slug}', function($slug) {
	$data = Target::where('slug', $slug)->with('missions', 'missions.competences')->first();
	return $data;
});
// MOA
Route::options('api/moas/{any}', function() {
    return Response::make('You are connected to the API');
});

Route::get('api/moas', function() {
	$data = MOA::with('competences')->get();
	return $data;
});
