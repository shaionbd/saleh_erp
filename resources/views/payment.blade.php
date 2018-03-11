@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">

    <h1>Payment<small></small></h1>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i></a></li>
        <li class="active">payment</li>
    </ol>

</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
        <!-- show net income -->
            <div class="box box-info">
                <div class="box-body">
                        
                    <table class="table table-bordered">
                        <thead>
                            <th class="text-center">Total Earning(&#2547)</th>
                            <th class="text-center">Penalty amount(&#2547;)</th>
                            <th class="text-center">Earning(&#2547)</th>
                            <th class="text-center">Total Withdrawing(&#2547;)</th>
                            <th class="text-center">Current Amount(&#2547;)</th>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>{{ number_format($net,2) }}</td>
                                <td>{{ number_format($penalty,2) }}</td>
                                <?php $total = $net-$penalty; ?>
                                <td>{{ $total }}</td>
                                <td>{{ number_format($withdraw,2) }}</td>
                                <td>{{ number_format($net-$withdraw,2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                        
                    {{-- {{ $payments }}{{ $withdrawals }} --}}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
        <!-- show net income -->
            <div class="box box-success">
                <div class="box-header with-border text-center">
                    <h3 class="box-title">Withdrawal History</h3>
                </div>
                
                <div class="box-body">
                        
                    <table class="table table-bordered">
                        <thead>
                            <th class="text-center">Withdrawal Type</th>
                            <th class="text-center">Account No</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Request Status</th>
                            <th class="text-center">Amount(&#2547;)</th>
                        </thead>
                        <tbody>
                            @foreach($withdrawals as $withdrawal)
                            <tr class="text-center">
                                <td>{{ $withdrawal->request_type }}</td>
                                <td>{{ $withdrawal->account_no }}</td>
                                <td>{{ date("jS M Y, h:i A", strtotime($withdrawal->created_at)) }}</td>
                                <td>@if($withdrawal->request_status == 1) received @elseif($withdrawal->request_status == 0) pending request @endif</td>
                                <td>{{ number_format($withdrawal->amount,2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                        
                    {{-- {{ $payments }}{{ $withdrawals }} --}}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
        <!-- show net income -->
            <div class="box box-danger">
                <div class="box-header with-border text-center">
                    <h3 class="box-title">Request</h3>
                </div>
                
                <div class="box-body">
                    <form role="form" method="post" action="{{ route('user.request_payemt') }}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="request_type">Request Type</label>
                                <select id="request_type" name="request_type" class="form-control">
                                    <option value="bkash">Bkash</option>
                                    <option value="ukash">Ukash</option>
                                    <option value="rocket">Rocket</option>
                                    <option value="dbbl">DBBL</option>
                                    <option value="brack">Brack</option>
                                    <option value="payoneer">Payoneer</option>
                                    <option value="paypal">Paypal</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="account_no">Account Number</label>
                                <input type="text" class="form-control" id="account_no" name="account_no" placeholder="Account Number" required>
                            </div>
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" min="50" max="{{ $total-$withdraw }}" required>
                            </div>
                            @if($net-$withdraw < 50)
                                <h4 class="text-danger">*you have not enough money for request</h4>
                            @endif
                        </div>

                        <div class="box-footer">
                            @if($net-$withdraw < 50)
                               <button type="submit" class="btn btn-primary" disabled title="you have not enough money for request">Submit</button> 
                            @else
                                <button type="submit" class="btn btn-primary">Submit</button> 
                            @endif
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section><!-- /.content -->

@endsection

@push('js')

@endpush