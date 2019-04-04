<?php


Route::post('mail', 'Charles\Mailgun\Controllers\MailController@mails')
    ->name('mails');

Route::options('api/mg/formSubmit', function() {
    return Response::make('You are connected to the API');
});

Route::post('api/mg/formSubmit','Charles\Mailgun\Controllers\PostController@formSubmit');
