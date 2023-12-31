<?php

namespace App\Admin\Actions;

use Encore\Admin\Actions\RowAction;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class OrderApproved extends RowAction
{
    public $name = 'Approve';
    //protected $selector = '.order-approved';
    public function handle(Model $model)
    {
        try {
            $id = $model->id;
            $encryptedID = Crypt::encryptString($model->id);
            if (isset($id) && !empty($id)) {
                $data = Order::find($id);
                $data->status = "Approved";
                $data->save();
                if ($data->save() == true) {
                    $user_id = Order::find($model->id)->user_id;
                    $emailData = User::join('orders', 'users.id', '=', 'orders.user_id')
                        ->where('orders.user_id', $user_id)
                        ->select('users.email')
                        ->first();
                    // $user_id=Order::find($model->id)->user_id;
                    if (isset($emailData)) {
                        $emailDataName = $emailData->email;
                        //het current user emailid end
                        $details = [
                            'email' => 'PSIEC ADMIN PANEL (Payment Link)',
                            'body' => 'Congratulations!!! Your order no ' . $model->order_no . ' has successfully approved. Kindly wait till dispatching of order.',
                            'encryptedID' => $encryptedID,
                            'status' => 'OrderApprove'
                        ];
                        \Mail::to($emailDataName)->send(new \App\Mail\PSIECMail($details));
                        //\Mail::to('csanwalit@gmail.com')->send(new \App\Mail\PSIECMail($details));
                        //dd("Email is Sent.");
                    } else {
                        return $this->response()->error('Oops! Kindly submit documents as required');
                    }
                }
                return $this->response()->success('Congratulations!!! Your order no ' . $model->order_no . ' has successfully approved. Kindly wait till dispatching of order.')->refresh();
            }
        } catch (\Throwable $ex) {
            Log::info($ex->getMessage());
        }
    }
    public function dialog()
    {
        $this->confirm('Are you sure for order approval?');
    }
}