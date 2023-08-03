<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\AttachmentController;
use App\Models\Order;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Layout\Content;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\HtmlString;
use Encore\Admin\Widgets\TableEditable;
use Encore\Admin\Grid\NestedGrid;
use Encore\Admin\Widgets\Table;
use App\Admin\Actions\Rejected;
use App\Admin\Actions\Data;
use App\Admin\Actions\ShowDocuments;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Auth\Permission;
use Illuminate\Validation\Rule;
use App\EncryptedFilter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Encore\Admin\Grid\Actions\BatchDelete;
use Encore\Admin\Grid\Filter\Like;
use Encore\Admin\Grid\Filter\Equal;
use App\Models\PaymentDataHandling;
use App\Admin\Actions\RegisterWithPsiec;

class UserController extends AdminController
{
  /**
   * Title for current resource.
   *
   * @var string
   */
  protected $title = 'User';
  /**
   * Make a grid builder.
   *
   * @return Grid
   */
  protected function grid()
  {
    

    try {
      $grid = new Grid(new User());
      // $grid->column('id', __('Id'));
      $grid->column('name', __('First Name'));
      //   $grid->column('name', __('First Name'))->display(function ($value) {
      //     return $this->getNameAttribute($value);
      // });
      $grid->column('last_name', __('Last Name'));
      $grid->column('email', __('Email'));
      $grid->column('contact_number', __('Contact Number'));
      $grid->column('attachment', 'Info')->display(function ($comments) {
        //$count = count($comments);
        //return "<span class='label label-warning'>{$count}</span>";
        return "documents";
      })->expand(function ($model) {
        $comments = $model->attachment()->take(10)->where('fileno', 'IS NOT', null)->get()->map(function ($comment) {
          return $comment->only(['file_type', 'fileno', 'created_at']);
        });
        return new Table(['Document Type', 'Document No', 'Release Time'], $comments->toArray());
      });
      $grid->column("approved", __('Status'))->display(function ($value) {
        if (isset($value) && $value === 1) {
          return "Approved";
        } else if (isset($value) && $value === 0) {
          return "New";
        } else if (isset($value) && $value === 2) {
          return "Rejected";
        }
      });

    

    //   $grid->column('Wallet')->display(function()
    // {
      
    //   return "Balance";
    // })->expand(function($model){
    //   $alluserdata=["Registration Amount"=>"<span style='color:red;font-weight:600'>Unpaid</span>","Booking Initial Amount"=>"<span style='color:red;font-weight:600'>Unpaid</span>","Pending Amount"=>"<span style='color:red;font-weight:600'>N/A</span>","Final Amount"=>"<span style='color:red;font-weight:600'>Unpaid</span>"];
    //   $alluserdata=["Order No","Registration Amount","Booking Initial Amount","Pending Mode","Final Amount"];
 
    //     $user_id=$this->getkey();
    //     $date=Carbon::now();
    //     $allorders=Order::where('user_id',$user_id)->where('payment_mode','online')->where('status','approved')->get()->last();

  
    //     if(isset($allorders) && !empty($allorders))
    //     {

          
    //         $order_id[]=$allorders->id;
        
    //     }
    //     if(isset($order_id[0]) && !empty($order_id[0]))
    //     {
    //       $registration_amount=PaymentDataHandling::where('user_id',$user_id)->where('data','Registration_Amount')->get()->last();
    //       if($registration_amount != null)
    //       {
    //         if(strtolower($registration_amount->payment_status)===strtolower("success"))
    //         {

    //           $alluserdata["Registration Amount"]=$registration_amount->transaction_amount."  <sapn style='color:green;font-weight:600'>(Paid)</sapn>";
    //         }
    //         else
    //         {
    //           $alluserdata["Registration Amount"]="<sapn style='color:red;font-weight:600'>(Payment fail)</sapn>";
    //         }

    //       }
         
    //         $initial_amount=PaymentDataHandling::where('order_id',$order_id)->where('data','Booking_Amount')->get()->last();
           
    //         if(isset($initial_amount)&& !empty($initial_amount))
    //         {
    //           if(strtolower($initial_amount->payment_status) == strtolower('SUCCESS'))
    //           {
           

           
              
    //           $alluserdata['Booking Initial Amount']=$initial_amount->transaction_amount."   <sapn style='color:green;font-weight:600'>(Outstanding Amount)</sapn>";
              
    //           }
    //           else
    //           {
    //             $alluserdata['Booking Initial Amount']="<sapn style='color:red;font-weight:600'>(Payment fail)</sapn>";
    //           }
    //         }
    //        $final_amount= PaymentDataHandling::where('order_id',$order_id)->where('data','Booking_Final_Amount')->get()->last();
    //        if(isset($final_amount) && !empty($final_amount))
    //        {
    //         if(strtolower($final_amount->payment_status) === strtolower('success'))
    //         {
    //           $totalAmount=$final_amount->transaction_amount;
             
              
    //           $cgstPercent = env('CGST', 9); // Set your CGST percentage here (e.g., 9%)
    //           $sgstPercent = env('SGST', 9);
    //           ; // Set your SGST percentage here (e.g., 9%)
    //           $totalTaxAmount = ($totalAmount * ($cgstPercent + $sgstPercent) / 100) ?? 0;
    //           $centralTaxAmount = ($totalAmount * $cgstPercent / 100) ?? 0;
    //           $stateTaxAmount = ($totalAmount * $sgstPercent / 100) ?? 0;
    //           // iii- Find the complete amount
    //           $completeAmount = ($totalAmount + $totalTaxAmount) ?? 0;

    //          $alluserdata['Final Amount']=$completeAmount;
    //          $alluserdata['Pending Amount']=$completeAmount-$initial_amount->transaction_amount."   <sapn style='color:green;font-weight:600'>(Pending)</sapn>";
    //         }
    //         else
    //         {
    //           $alluserdata['Final Amount']="<sapn style='color:green;font-weight:600'>(Paid)</sapn>";
    //         }


    //        }


            
  

    //     }

  
    //     return new Table($alluserdata);
    // });









    


    $grid->column('Wallet')->display(function()
    {
      
      return "Balance";
    })->expand(function($model){
   

      $alluserdata=[["<span style='color:red;font-weight:600'>NO Order</span>","<span style='color:red;font-weight:600'>Unpaid</span>","<span style='color:red;font-weight:600'>Unpaid</span>","<span style='color:red;font-weight:600'>Unpaid</span>","<span style='color:red;font-weight:600'>Unpaid</span>"]];

$order_num=0;
$order_num=3;

            $userId=$this->id;
          
            $payment1=PaymentDataHandling::where('user_id',$userId)->where('data',"booking_amount")->first();

            $allorder=order::where('user_id',$userId);

            foreach($allorder as $singleorder)
            {
               $singleorder   
            }
            

           
         






        // $user_id=$this->getkey();

        // $order_no=0;
        // $order_nos=0;

        // $inital=0;
        // $initials=2;

        // $num=0;
        //     $num1=1;

        //     $payment_mode=0;
        //     $payment_modes=3;

        //     $final=0;
        //     $finals=4;




        // $registration_amount=PaymentDataHandling::where('user_id',$user_id)->where('data','Registration_Amount')->first();

        // if($registration_amount === null)
        // {

        // }
           

        //      if(count($registration_amount) > 0)
        
        //      {

        //   foreach($registration_amount as $singleregistrationamount)
        //   {
          
            
            

                
        //     if(strtolower($singleregistrationamount->payment_status)===strtolower("success"))
        //     {
                    
                
        //       $alluserdata[$num][$num1]= $singleregistrationamount->transaction_amount."  <sapn style='color:green;font-weight:600'>(Paid)</sapn>";
        //     }
          
        //     elseif(strtolower($singleregistrationamount->payment_status)===strtolower("fail"))
        //     {
        //       $alluserdata[$num][$num1]=$singleregistrationamount->transaction_amount." <sapn style='color:red;font-weight:600'>(Payment fail)</sapn>";
        //     }
        //     $num++;
        //   }

        // }
        // $allorders=Order::where('user_id',$user_id)->get();
      

        //   foreach($allorders as $singleordeer)
        //   {
        
             

             
            
        //     $alluserdata[$order_no][$order_nos]=$singleordeer->order_no;
        //     $alluserdata[$payment_mode][$payment_modes]=$singleordeer->payment_mode;

        //     $initial_amount=PaymentDataHandling::where('user_id',$user_id)->where('order_id',$singleordeer->id)->where('data','Booking_Amount')->get();
            
        //     $final_again_amount= PaymentDataHandling::where('user_id',$user_id)->where('order_id',$singleordeer->id)->where('data','Booking_Final_Amount')->get();
           
        //     foreach($initial_amount as $singleinitialamount)
        //     {
         
        //       foreach($final_again_amount as $singlefinalagain)
        //       {
        //         if(strtolower($singleinitialamount->payment_status)===strtolower("success"))
        //         {
        //          if(strtolower($singlefinalagain->payment_status)===strtolower('success'))
        //          {
                  
        //           $alluserdata[$inital][$initials]=$singleinitialamount->transaction_amount." <sapn style='color:green;font-weight:600'></sapn>";
        //          }
        //          else
        //          {

        //            $alluserdata[$inital][$initials]=$singleinitialamount->transaction_amount." <sapn style='color:green;font-weight:600'>(Outstanding Payment)</sapn>";
        //          }
             
        //       }
        //       elseif($singleinitialamount->payment_status === null)
        //       {
        //         $alluserdata[$inital][$initials]=" <sapn style='color:red;font-weight:600'>Unpaid</sapn>";
        //       }     
               
        //       else
        //       {
        //         $alluserdata[$inital][$initials]=$singleinitialamount->transaction_amount." <sapn style='color:red;font-weight:600'>(fail)</sapn>";
        //       }
        //       }
           
        //     $inital++;
        //     }

        //     $final_amount= PaymentDataHandling::where('user_id',$user_id)->where('order_id',$singleordeer->id)->where('data','Booking_Final_Amount')->get();
        //     foreach($final_amount as $finalsingle)
        //     {
              
        //       $cgstPercent = env('CGST', 9); // Set your CGST percentage here (e.g., 9%)
        //       $sgstPercent = env('SGST', 9);
        //       ; // Set your SGST percentage here (e.g., 9%)
        //       $totalTaxAmount = ($finalsingle->transaction_amount * ($cgstPercent + $sgstPercent) / 100) ?? 0;
        //       $centralTaxAmount = ($finalsingle->transaction_amount * $cgstPercent / 100) ?? 0;
        //       $stateTaxAmount = ($finalsingle->transaction_amount * $sgstPercent / 100) ?? 0;
        //       // iii- Find the complete amount
        //       $completeAmount = ($finalsingle->transaction_amount + $totalTaxAmount) ?? 0;

        //       $alluserdata[$final][$finals]=$completeAmount." <span style='color:green;font-weight:600'>(Including Tax)</span><span style='color:red;font-weight:600'>(Unpaid)</span>";
        //       if(strtolower($finalsingle->payment_status)===strtolower("success"))
        //       {
        //         $alluserdata[$final][$finals]=$completeAmount." <sapn style='color:green;font-weight:600'>(Paid)</sapn>";

              
        //       }
   
        //     }

        //     $order_no++;
        //     $payment_mode++;
            
        //   }


      











       
     

  
        return new Table(["Order No","Registration Amount","Booking Initial Amount","Payment Mode","Final Amount"],$alluserdata);
    });

   
   
      // ->expand(function ($model) {
      //         $query = DB::table('comments')->where('approved', $model->approved)->where('admin_id',Admin::user()->id)->get();
      //         if ($model->approved == 0) {
      //             return "                                                                           "."No Status Found!!!!!!!!!";
      //         } else if (isset($query) && count($query) > 0) {
      //             $table = '<table class="table ms-4">
      //             <thead>
      //                 <tr>
      //                     <th scope="col">Status</th>
      //                     <th scope="col">Updated At</th>
      //                     <!-- Add more table headers as needed -->
      //                 </tr>
      //             </thead>
      //             <tbody>';
      //             foreach ($query as $query) {
      //                 $table .= '<tr>
      //                     <td>' . $query->comment . '</td>
      //                     <td>' . $query->approved_at . '</td>
      //                     <!-- Add more table cells as needed -->
      //                 </tr>';
      //             }
      //             $table .= '</tbody></table>';
      //             return $table;
      //         }
      //  });
      $grid->export(function ($export) {
        //$export->filename('Filename.csv');
        $export->except(['approved', 'comments', 'attachment', 'otp']);
      });
     
      $grid->actions(function ($actions) {
        
        if (Admin::user()->inRoles(['admin', 'administrator', 'Administartor'])) {
          //$actions->disableEdit();
        } else if (Admin::user()->inRoles(['superadmin', 'SuperAdmin'])) {

        }
        $actions->disableView();
        $actions->disableDelete();
        $actions->disableEdit();
        $actions->add(new ShowDocuments);
        if ($actions->row->approved == 0) {
          $actions->add(new Data);
          $actions->add(new Rejected);
        } else if ($actions->row->approved == 1) {
          //$actions->add(new Data);
          $actions->add(new Rejected);
        } else if ($actions->row->approved == 2) {
          $actions->add(new Data);
          //$actions->add(new Rejected);
        }
        $actions->add(new RegisterWithPsiec);



      });

      $grid->batchActions(function ($batchActions) {
        $batchActions->disableDelete(); // Disable batch delete for all cases
      });

      $grid->disableCreateButton();
      // $grid->column('id')->hidden();
      //$grid->model()->orderBy('created_at', 'desc');
      $grid->column('created_at', __('Created At'))->display(function ($value) {
        //return Carbon::parse($value)->format('d-m-Y H:i:s');
        return Carbon::parse($value)->format('Y-m-d H:i');
        //return Carbon::parse($value)->format('d-m-Y');
      });
      $grid->column('comment', __('Payment'))->display(function ($value) {
        if (isset($value) && !empty($value) && $value == "Done") {
          return "Done";
        } else {
          return "Pending";
        }
      });
      $grid->column("member_at", __("Member At"))->display(function ($value) {
        if (isset($value) && !empty($value)) {
          return Carbon::parse($value)->format('Y-m-d');
        } else {
          $userID = $this->id;
          if (isset($userID) && !empty($userID)) {
            $user = User::with([
              'paymentDataHandling' => function ($query) {
                $query->where('payment_status', 'SUCCESS')
                  ->where("data", "Registration_Amount")
                  ->orderBy('updated_at', 'desc')
                  ->limit(1);
              }
            ])->find($userID);
            //return $user;
            if ($user) {
              if ($user->paymentDataHandling->isNotEmpty()) {
                $updatedAt = $user->paymentDataHandling->first()->updated_at;
                //return $updatedAt;
                $customerStartDate = Carbon::parse($updatedAt);
                return Carbon::parse($customerStartDate)->format('Y-m-d');
              } else {
                return "N/A";
              }
            } else {
              return "N/A";
            }
          } else {
            return "N/A";
          }
        }
      });
      $grid->filter(function ($filter) {
        $filter->disableIdFilter();
        $filter->column(1 / 2, function ($filter) {
          //$filter->equal('name', __('Select Name'))->select(User::pluck('name', 'name')->toArray());
          $filter->like('name', __('First Name'));
          $filter->like('email', __('Email'));
        });
        $filter->column(1 / 2, function ($filter) {
          $filter->equal('approved', __('Status'))->select([
            0 => 'New',
            1 => 'Approved',
            2 => 'Rejected',
          ]);
          $filter->like('contact_number', __('Contact'));
        });
      });
      //$grid->disableRowSelector();
      $grid->model()->whereHas('attachment', function ($query) {
        $query->whereNotNull('filename');
      })->orderByDesc('created_at');
      return $grid;
    } catch (\Throwable $ex) {
      Log::info($ex->getMessage());
      return $grid;
    }
  }

  /**
   * Make a show builder.
   *
   * @param mixed $id
   * @return Show
   */
  protected function detail($id)
  {
    $show = new Show(User::findOrFail($id));
    // $show->field('id', __('Id'));
    $show->field('name', __('Name'));
    $show->field('last_name', __('Last name'));
    $show->field('email', __('Email'));
    $show->field('contact_number', __('Contact number'));
    return $show;
  }

  /**
   * Make a form builder.
   *
   * @return Form
   */
  protected function form()
  {
    // return $form;
    $form = new Form(new User());
    // $form->text('name', __('First Name'))->rules('required|max:255|regex:/^[a-zA-Z]+$/');
    // $form->text('last_name', __('Last name'))->rules('required|max:255|regex:/^[a-zA-Z]+$/');
    // $form->email('email', __('Email'))->rules('required|max:255|email');
    // $form->text('contact_number', __('Contact number'))->rules('required|max:10|unique:users|min:10');
    $form->date("member_at", __("Register with PSIEC"));
    $form->footer(function ($footer) {
      $footer->disableViewCheck();
      // disable `Continue editing` checkbox
      $footer->disableEditingCheck();
      // disable `Continue Creating` checkbox
      $footer->disableCreatingCheck();
    });
    // $form->footer->class('form-footer'); 
    return $form;
  }
}