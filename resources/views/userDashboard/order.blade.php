
@extends('userDashboard.maindashboard')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title total-users">
      All Orders
    </h3>
  </div>
  <div class="card-body" class="position-relative">

    @if(count($main) > 0)

<table style="text-align: center" class="table">
  @foreach ($main as $data)

    <tr>
      <td colspan="4" style="background-color:#effdff;"><span style="font-weight: 600">ORDER NO</span> : {{$data->order_no}}</td>
      @foreach ($data->orderItems as $single)
        <tr>
          <td>{{$single->category_name}}</td>
          <td>{{$single->description}}</td>
          <td>{{$single->quantity}}</td>
          <td>{{$single->measurement}}</td>

        </tr>
      @endforeach
    </tr>

  @endforeach
  
</table>
    @else
    <img src="{{asset('images/home-page/nodata.jpg')}}"style="width:40%;margin:auto;display:block"> 
    @endif
     
     
  </div>

</div>


@endsection