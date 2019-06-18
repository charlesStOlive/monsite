<?php



Route::post('mail', 'Charles\Mailgun\Controllers\MailController@mails')
    ->name('mails');

Route::options('api/mg/formSubmit', function() {
    return Response::make('You are connected to the API');
});

Route::post('api/mg/formSubmit','Charles\Mailgun\Controllers\PostController@formSubmit');

Route::get('maker/pdfreport/{user_id}/{sendDate?}/', 'Charles\Mailgun\Controllers\PdfReportController@index');
//
Route::get('maker/pdfcv/{user_id}', 'Charles\Mailgun\Controllers\PdfCvController@index');
