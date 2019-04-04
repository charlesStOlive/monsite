<?php namespace Charles\Mailgun\Controllers;
 
use Illuminate\Http\Request;
use Backend\Classes\Controller;
use Mail;
 
class PostController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function formSubmit(Request $request)
    {
        // $obj;
        // $obj['questions'] =  $request->get('questions');
        // trace_log($obj);

        // trace_log("--------------------------------------");
        // trace_log($request->all());
        // trace_log($request->get('name'));
        $name = $request->get('name');

        Mail::send('vue::survey1', $request->all(), function($message) use ($name) {

            $message->to('embauche@charles-saint-olive.com', 'Charles Saint Olive');
            $message->subject('Reponse sondage de '.$name);
        });

    	return response()->json([$request->all()]);;
    }
}