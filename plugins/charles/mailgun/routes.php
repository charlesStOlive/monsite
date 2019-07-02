<?php

use Charles\Mailgun\Models\Contact;
use Charles\Mailgun\Models\Visit;


Route::post('mail', 'Charles\Mailgun\Controllers\MailController@mails')
    ->name('mails');

Route::options('api/mg/formSubmit', function() {
    return Response::make('You are connected to the API');
});

Route::post('api/mg/formSubmit','Charles\Mailgun\Controllers\PostController@formSubmit');

Route::get('maker/pdfreport/{user_id}/{sendDate?}/', 'Charles\Mailgun\Controllers\PdfReportController@index');
//
Route::get('maker/pdfcv/{user_id}', 'Charles\Mailgun\Controllers\PdfCvController@index');
//
Route::options('api/user/{any}', function() {
    return Response::make('You are connected to the API');
});

Route::get('api/user/{userkey?}', function($userkey='3lrU70dUgW8R') {
    $contact = Contact::where('key', $userkey)->with('cloudis', 'client')->first();
    $contact->visits()->add(new Visit(['type' => 'site']));
    $contact['colors'] = $contact->client->colors;
	return $contact;
});
Route::options('api/{any}', function() {
    return Response::make('You are connected to the API');
});
Route::get('api/pdfreportglobal/{user_id}/{region_id}/{is_pdf}/{sendDate?}/', 'Charles\Mailgun\Controllers\PdfReportGlobalController@index');



