<?php

namespace App\Http\Controllers\API;

use App\Services\Braintree\BrainTreeSKD;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Repositories\Invoice\InvoiceInterface;
use Illuminate\Http\Request;

class CartsController extends Controller
{
    /**
     * InvoiceInterface
     * @var $invoice
     */
    private $invoice;


    public function __construct( InvoiceInterface $invoice)
    {
    	$this->middleware('jwt.auth');
        
        $this->invoice = $invoice;
    }

    public function createPayment(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $data = [
            'amount' => $request->get('amount'),
            'template_mk_id' => $request->get('template_mk_id')
        ];
        
        $result = $this->invoice->checkout($data);
        
        return $result
            ? response()->json([
                    'status_code' => 200,
                    'client_token' => BrainTreeSKD::getClientToken($user), 
                    'invoice_id' => $result
                ], 200, [], JSON_NUMERIC_CHECK)
            : response()->json(['status_code' => 400, 'message' => 'Error when create invoice']);
    }

    public function checkout($invoice_id, Request $request)
    {
        try {
            \Log::info('testAPI', $request->all());
            $data = [
                'amount' => $this->invoice->getById($invoice_id)->total,
                'paymentMethodNonce' => $request->get('paymentMethodNonce'),
                'customerId' => \Auth::user()->id,
            ];

            $result = BrainTreeSKD::transaction($data);
            
            if ( !$result) {
                return response()->json(['status_code' => 400, 'message' => 'Transaction failed']);
            }    

           return $this->invoice->paid($invoice_id)
                    ? response()->json(['status_code' => 200, 'message' => 'Paid invoice successfully'])
                   : response()->json(['status_code' => 400, 'message' => 'Paid invoice faild']); 
            
        } catch(PaymentMethodException $e) {
            return response()->json(['status_code' => 400, 'message' => $e->getMessage()]);
        }        
    }
}
