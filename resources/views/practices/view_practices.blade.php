
@extends('layouts.app_layout')

@section('content')

<?php
use App\Models\Company;
use App\Models\User;
use App\Models\UserCompanyMap;
use Illuminate\Foundation\Auth\RegistersUsers;
?>
<script src="{{asset('/js/views/practices.js')}}"></script>

<div class="row ">
  <div class="col-sm-12 col-lg-12">
    <div class="card bg-white border-0 shadow-sm rounded">
      <div class="card-body">
        @if(Auth::user()->role_id != 1)
          <?php 
          if(empty($practicesCount[0]->practices_count) || empty($practicesList)) { ?>
            <span class='float-right'>
              <button class="btn btn-sm btn-primary" data-toggle="modal"  id="mNewPractice_model" data-target="#mNewPractice">
                <i class="fas fa-plus mr-1"></i> New Practice</button>
            </span>
            <?php 
          } 
          else if(count($practicesList) < $practicesCount[0]->practices_count || $practicesCount[0]->practices_count == '200+') { ?>
            <span class='float-right'>
              <button class="btn btn-sm btn-primary" data-toggle="modal"  id="mNewPractice_model" data-target="#mNewPractice">
              <i class="fas fa-plus mr-1"></i> New Practice</button>
            </span>
            <?php 
          } else { ?>
            <span class='float-right' title="You have reached the maximum number of practices. Please contact administrator to add new practices" data-toggle="tooltip" tabindex="0">
              <button class="btn btn-sm btn-primary" data-toggle="modal"  id="mNewPractice_model" data-target="#mNewPractice" style="pointer-events:none" disabled>
              <i class="fas fa-plus mr-1"></i> New Practice</button>
            </span>
            <?php 
          }?>
        @endif
        
        <i class="fas fa-info-circle mr-1 text-info"></i>
        Below is a list of available practices
      </div>

      <div class="card-body border-top">
        <!-- <div class="form-group mb-2">
          <input class="form-control form-control-sm search" type="text" placeholder="Type here to search">
        </div> -->

        <div class="table-y-scroll fetch-practices-data">
          <table class="table table-sm table-no-border bg-white" >
            <thead>
              <tr>
                <th class="border-top-0 bg-light">Practice Name</th>
                <!-- @if($role_id == 1) 
                  <th class="border-top-0 bg-light">Subscription</th>
                @endif -->
                <th class="border-top-0 bg-light">Contact</th>
                <th class="border-top-0 bg-light">Email</th>
                <th class="border-top-0 bg-light">Status</th>
                <th class="border-top-0 bg-light">Email Notification</th>
                @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                  <th class="border-top-0 bg-light">Action</th>
                @endif
              </tr>
            </thead>
            
            <tbody class="align-middle">
              @if(count($practicesList) > 0)
                @foreach($practicesList as $key => $row)
                  <tr>
                    <td>
                      <span data-toggle="modal"  id="mEditPractice_model" data-target="#mEditPractice" onclick='editPracticeDetails("{{$row->id}}", "{{$row->company_id}}")' style="cursor: pointer;color: blue">
                        <h6>
                          {{ $row->name }}
                        </h6>
                      </span>
                    </td>
                    <td>{{ $row->phone }}</td>
                    <td>{{ $row->email }}</td>
                      <!-- @if($role_id == 1) 
                        <td>
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input subscription" name="chkUserActive_{{ $key }}" data-userid="{{ $row->id }}" id="chkUserActive_{{ $key }}" tabindex="6" <?php if($row->is_subscription == 1) {?> checked <?php }?>>
                            <label class="custom-control-label" for="chkUserActive_{{ $key }}" style="margin-bottom: 20px;"></label>
                          </div>
                        </td>
                      @endif -->
                    @if($row->is_report_verified == 1)            
                      <td style="color: lightgreen">Verified</td>
                    @else
                      <td>Yet to verify</td>
                    @endif

                    @if($row->active == 1)
                      <td>
                        <u id="sendLogin" data-id="{{$row->user_id}}" data-toggle="tooltip" title="Send client login" onclick='sendLogin("{{$row->user_id}}")' style="cursor: pointer"><a href="#">Send Email</a></u>
                        <!-- <i class="fas fa-paper-plane"></i> -->
                      </td>
                    @else
                      <td class="d-block text-warning" data-toggle="tooltip" title="Waiting for client to accept invite and configure.">waiting for client</td>
                    @endif

                    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3)<!-- Only Master Firm Admin -->
                      <td onclick='deleteUser()'>
                        <a class="btn btn-sm btn-light rounded-pill text-primary userValId" data-toggle="modal" data-target="#mAddUser" data-id="16"><i class="fa fa-trash" style="cursor: pointer"></i></a>
                      </td>
                    @endif
              
                  </tr>
                @endforeach
              @else 
                <td colspan="6" class="text-center">No Practices Found</td>
              @endif
            </tbody>
          </table>
        </div>
        
       <!--  <div class="list-group fetch-practices-data">
          @foreach($practicesList as $row)

          <a class="list-group-item list-group-item-action">
            <span data-toggle="modal"  id="mEditPractice_model" data-target="#mEditPractice" onclick='editPracticeDetails("{{$row->id}}", "{{$row->company_id}}")' style="cursor: pointer;color: blue">
              <h6>
                {{ $row->name }}
              </h6>
            </span>

            @if($row->is_report_verified == 1)            
              <span style="color: lightgreen">Verified</span>
            @else
              <span>Yet to verify</span>
            @endif

            @if($row->active == 1)
              <button class="btn btn-sm btn-secondary float-right mr-1" id="sendLogin" data-id="{{$row->id}}" data-toggle="tooltip" title="Send client login" onclick='sendLogin("{{$row->id}}")' style="margin-top: -30px;">
                <i class="fas fa-paper-plane"></i>
              </button>
            @else
              <small class="d-block text-warning float-right mr-1" data-toggle="tooltip" title="Waiting for client to accept invite and configure." style="margin-top: -20px;">waiting for client</small>
            @endif 
          </a>
          @endforeach
        </div> -->
        
      </div>
    </div>
  </div>
  <div class="col-md-4 col-lg-4">
  </div>
</div>
   
<div class="basic_details modal fade" id="mEditPractice">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
          <div class="col-4 col-fhd-3">
            <img class="img-fluid" src="{{ asset('/img/logo.svg') }}">
          </div>
          <div class="col-8 col-fhd-9 text-right d-none">
            <button class="btn btn-light shadow-sm" href="login.html">
              <i class="fas fa-sign-out-alt mr-1"></i>
              Logout
            </button>
          </div>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
          <span aria-hidden="true" id="close_model">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
          <div class="card border-0 mt-0 mb-4 mb-lg-0 mt-lg-0">
            <div class="card-body border-bottom border-info border-2">
              <!-- <span class="float-right text-center">
                Step 1 of 2
                <div class="progress" style="height: 5px; width:100px;">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 50%"></div>
                </div>
              </span> -->
              <h5 class="my-0 text-muted">Complete the following steps to define your
                practice.</h5>
            </div>

            <!--  <div class="card-body bg-light py-2">
              <h4 class="my-0">Basic Details</h4>
              <h4 class="my-0">Practice Specifics</h4>
            </div> -->

            <div class="tab">
              <button class="tablinks active" id="tab_1" onclick="changeTab('1');">Basic Details</button>
              <button class="tablinks" id="tab_2" onclick="changeTab('2');">Practice Specifics</button>
            </div>

            <form class="model_basic_details" id="basic_information" name="basic_information" method="post">
              @csrf
              <input type="hidden" name="user_id" id="user_id" value="">
              <input type="hidden" name="company_id" id="company_id" value="">
              <div class="card-body pb-0">
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label>Practice name <i class="fas fa-info-circle text-warning ml-1" data-toggle="tooltip" title="" data-original-title="Legal name of your practice"></i></label>
                      <input type="text" class="form-control form-control-sm" placeholder="" id="pName" name="pName" value="" tabindex="1">
                    </div>
                    <div class="form-group">
                      <label>City</label>
                      <input type="text" class="form-control form-control-sm" placeholder="" name="city" id="city" value="" tabindex="3">
                    </div>
                  </div>
                  <div class="col-sm-8">
                    <div class="form-group">
                      <label>Practice Address</label>
                      <input type="text" class="form-control form-control-sm" placeholder="" id="address" name="address" value="" tabindex="2">
                    </div>

                    <div class="row">
                      <div class="col">

                        <div class="form-group">
                          <label>State</label>
                          <input type="text" class="form-control form-control-sm" placeholder="" id="state" name="state" value="" tabindex="4">
                        </div>
                      </div>
                      <div class="col">

                        <div class="form-group">
                          <label>Zip</label>
                          <input type="text" class="form-control form-control-sm" placeholder="" id="zipcode" name="zipcode" value="" tabindex="5" maxlength="6">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row border-top mt-3">
                  <div class="col-4 pb-3">
                    <div class="form-group mt-3">
                      <label>Contact person name</label>
                      <input class="form-control form-control-sm" type="text" id="cName" name="cName" placeholder="" value="" tabindex="6">
                    </div>

                    <div class="form-group">
                      <label>Contact person email</label>
                      <input class="form-control form-control-sm" type="text" id="cEmail" name="cEmail" placeholder="" value="" tabindex="" disabled="">
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group mt-3">
                      <label>Contact person phone</label>
                      <input class="form-control form-control-sm" type="text" id="cPhone" name="cPhone" placeholder="" value="" maxlength="10" tabindex="7">
                    </div>
                  </div>
                  <div class="col-4 bg-light border-left pb-3">
                    <div class="form-group mt-3">
                      <label>Preferred Communication Method <i class="fas fa-info-circle text-warning ml-1" data-toggle="tooltip" title="" data-original-title="Select the best way to communicate with the contact person"></i></label>
                      <div class="custom-control custom-checkbox">
                        <input type="radio" id="rdbPreferred_email" name="rdgPreferred" class="custom-control-input" value="1" tabindex="8" checked="">
                        <label class="custom-control-label" for="rdbPreferred_email">Email</label>
                      </div>
                      <div class="custom-control custom-checkbox">
                        <input type="radio" id="rdbPreferred_text" name="rdgPreferred" class="custom-control-input" value="2" tabindex="9">
                        <label class="custom-control-label" for="rdbPreferred_text">Text (Phone)</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>

            <form class="practice_specific" id="practice_specify" name="practice_specify" method="post" style="display: none">
              <input type="hidden" name="user_id" id="user_id" value="{{isset($_GET['confirmation_code'])?base64_decode($_GET['confirmation_code']):''}}">
              <input type="hidden" name="company_id" id="company_id" value="{{isset($_GET['practices'])?base64_decode($_GET['practices']):''}}">
                <input type="hidden" name="start_year" id="start_year" value="">
                
              <div class="card-body border-top">
                <div class="row">
                 <div class="col-3" role="group">
                  <div class="dropdown flex-fill block-picker year-dropdown">  
                     <label>Year started <i class="fas fa-info-circle text-warning ml-1" data-toggle="tooltip"
                            title="Year you started or purchased this practice"></i></label>
                    <a class="form-control form-control-sm " data-toggle="dropdown" data-select>
                      <span class="text" id="year">{{ isset($as_year) ? $as_year : date('Y') }}</span>
                      <i class="fas fa-caret-down float-right py-1"></i>
                    </a>
                    @php
                      $previousYr = date( 'Y' ,strtotime ( '-39 year' , strtotime(date('Y')) ) );
                      $yearArr    = range(date("Y"), $previousYr);
                      asort($yearArr);
                    @endphp                 
                    <div class="year_started dropdown-menu shadow" style="">
                      @foreach($yearArr as $key => $year)
                        <a data-year="{{ $year }}" class="dropdown-item {{  isset($as_year) && $year == $as_year ? 'active' : '' }}">{{ $year }}</a>
                      @endforeach
                    </div>
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label># Operatories<i class="fas fa-info-circle text-warning ml-1" data-toggle="tooltip"
                        title="How many operatories does the practice have? "></i></label>
                    <select class="form-control form-control-sm" data-toggle="range" data-min="1" data-max="50" id="operatories_count" name="operatories_count">
                      @for($i=1;$i<=50;$i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                      @endfor
                    </select>

                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label>Entity Type</label>
                    <select class="form-control form-control-sm" id="entity_count" name="entity_count">
                      @foreach($entity_type as $value)
                        <option value="{{$value['id']}}">{{$value['name']}}</option>
                      @endforeach
                    </select>

                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label>Type of Practice</label>
                    <select class="form-control form-control-sm" id="practices_count" name="practices_count">
                     @foreach($practices_type as $value)
                        <option value="{{$value['id']}}">{{$value['name']}}</option>
                     @endforeach
                    </select>

                  </div>
                </div>
                </div>
              </div>
              <div class="card-body bg-light">
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label># Owners <i class="fas fa-info-circle text-warning ml-1" data-toggle="tooltip"
                          title="The number of owners for this practice"></i></label>
                      <select class="form-control form-control-sm" data-toggle="range" data-min="1" data-max="50" id="owners_count" name="owners_count">
                        @for($i=1;$i<=50;$i++)
                          <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                      </select>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label>Total Number of FTE Employees (including owners and associates)<i class="fas fa-info-circle text-warning ml-1" data-toggle="tooltip"
                          title="Number of employees in this practice (including the owner) "></i></label>
                      <select class="form-control form-control-sm" data-toggle="range" data-min="1" data-max="50" id="employee_count" name="employee_count">
                        @for($i=1;$i<=50;$i++)
                          <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                      </select>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label># of FTE Associates<i class="fas fa-info-circle text-warning ml-1" data-toggle="tooltip"
                          title="Number of full time equivalent doctors in practice"></i></label>
                      <select class="form-control form-control-sm" data-toggle="range" data-min="1" data-max="50" id="fte_count" name="fte_count">
                        @for($i=1;$i<=50;$i++)
                          <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                      </select>

                    </div>
                  </div>
                </div>
              </div>

              <div class="card-body">
                <div class="form-group">
                  <label>
                    Does the Practice use a milling unit to mill crowns?
                  </label>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="rdbMill_yes" name="rdgMill" value="1" class="custom-control-input" checked>
                    <label class="custom-control-label" for="rdbMill_yes">Yes</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="rdbMill_no" name="rdgMill"  value="0" class="custom-control-input">
                    <label class="custom-control-label" for="rdbMill_no">No</label>
                  </div>
                </div>

                <div class="form-group">
                  <label>
                    Does the Practice offer Implants?
                  </label>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="rdbImplants_yes"  value="1" name="rdgImplants" class="custom-control-input" checked>
                    <label class="custom-control-label" for="rdbImplants_yes">Yes</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="rdbImplants_no"  value="0" name="rdgImplants" class="custom-control-input">
                    <label class="custom-control-label" for="rdbImplants_no">No</label>
                  </div>
                </div>

                <div class="form-group">
                  <label>
                    Does the practice offer clear aligners?
                  </label>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="rdbAligner_yes" name="rdgAligner"  value="1" class="custom-control-input" checked>
                    <label class="custom-control-label" for="rdbAligner_yes">Yes</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="rdbAligner_no" name="rdgAligner"  value="0" class="custom-control-input">
                    <label class="custom-control-label" for="rdbAligner_no">No</label>
                  </div>
                </div>
              </div>
            
            </form>

          </div>
        </div>
      </div>
     
      <div class="card-footer">
        <div class="clearfix">
          <div id="errorMsg"></div>
          <button type="button" class="float-right btn btn-sm btn-primary" id="btnSaveDetails" tabindex="10"><i class="fas fa-check mr-2"></i>Save
            and
            Continue</button>
        </div>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="mNewPractice">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
          New Practice

        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" id="close_model">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-muted d-block mb-3 pb-2 border-bottom">
          Send an invitation to client for configuring this new practice. When the client is done with the
          configuration, you will be notified.
        </div>
        <form id="requestEmail" name="requestEmail" method="POST">
           @csrf 
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label>
                Client Email
                <i class="fas fa-info-circle text-warning ml-1" data-toggle="tooltip"
                  title="The invitated to configure this new practice will be sent to this mail id."></i>
              </label>
              <input class="form-control form-control-sm" id="email"  name="email" onkeyup="checkEmailExist()"  tabindex="1" />
              <span id="validEmail"></span>
              <span id="checkEmailVal"></span>
            </div>
            <div class="form-group">
              <label>
                Client Name
                <i class="fas fa-info-circle text-warning ml-1" data-toggle="tooltip"
                  title="This will be used in the invitation Email."></i>
              </label>
              <div class="input-group input-group-sm">
                <input class="form-control form-control-sm validate_blur" id="txtCName" name="first_name" placeholder="First Name" tabindex="2" />
              
                <input class="form-control form-control-sm validate_blur" id="txtCLName"  name="last_name" placeholder="Last Name" tabindex="3" />
                 <!--   <span class=" form-control-sm" id="validName"></span>
                 <span class=" form-control-sm" id="validLastName"></span> -->
              </div>
            </div>
            <div class="form-group">
              <label>
                Practice Name
                <i class="fas fa-info-circle text-warning ml-1" data-toggle="tooltip"
                  title="This name will be saved, untill the client accepts and configures this practice."></i>
              </label>
              <input class="form-control form-control-sm validate_blur" id="txtPName" name="practice_name" tabindex="4" onkeyup="checkPractNameExist()"/>
              <span id="validPracticesName"></span>
            </div>
          </div>

          <div class="col-6">
            <div class="form-group" >
              <label>Welcome message</label>
              <div class="form-control form-control-sm h-auto" id="clientMsg">
              <p>Dear <span id="spnCName">Client</span>,</p>
                <p contenteditable="true" id="welcome_msg" name="welcome_msg">Good day!</p>
                <p contenteditable="true" id="prag">
                We are pleased to inform you that, you have been invited to collaborate with Practice Gauge for trend analysis and peer comparison of your business.</p>

                <p contenteditable="true" id="req_practice">Please us the following link to configure your practice (<strong
                    contenteditable="false" id="spnPName"></strong>) on Practice Gauge!
                  <div class="text-primary" contenteditable="false">Link Goes Here</div>
                </p>

                <p contenteditable="true" id="req_send">Thank you,<br />
                  <span id="spnCName1"></span><br/>
                  <span id="cName">CPA</span><br/>
                 <span id="company"> Practice Gauge</span>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="clearfix w-100">
          <div class="text-primary float-left"  style="cursor:pointer;" contenteditable="false"   href="#" onclick="newSelf()"  tabindex="6">I will
            cofigure this practice myself</div><!-- id="lnkSelfInvite" data-toggle="modal" data-target="#mSelfInvite"
            data-dismiss="modal" -->
          <button class="btn btn-sm btn-primary float-right" id="btnSend" type="button" tabindex="5">Send Invite</button>
        </div>
      </div>
      <!-- </form> -->
    </div>
  </div>
</div>

<div class="modal fade" id="mSelfInvite" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
          New Practice Configuration
        </h4>
      </div>
      <div class="modal-body text-center">
        <i class="fas fa-spinner fa-spin fa-2x my-3 text-primary" id="iconSpin"></i>
        <h5 class="" id="lblProgressMsg">
          Waiting for the configuration to complete...
        </h5>

        <div class="text-muted" id="lnkWindowAgain_wrapper">A new window has been opened to configure a new practice. <a
            class="" href="#" id="lnkWindowAgain">Click
            here</a>, if you do
          not see any new window.
        </div>
      </div>
      <div class="modal-footer text-center">
        <button class="btn btn-sm btn-primary float-right" id="btnCancel" data-dismiss="modal">Cancel process</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    window.workspaceScript.onLoad();
  })

  /*$(window).on("click", function(){
    if ($('#mEditPractice').is(':visible')) {
      window.location.reload();
    }
  });*/

  $('.validate_blur').blur(function (){
    var email      = $.trim($('#email').val());
    var first_name = $.trim($('#txtCName').val());
    var last_name  = $.trim($("#txtCLName").val());
    var txtPName   = $.trim($("#txtPName").val());

    if(first_name != ""){      
      $("#txtCName").css("border-color", '');
      $("#validName").text('');
      $("#validName").css('color', '');
    }
    if(last_name != ""){      
      $("#txtCLName").css("border-color", '');
      $("#validLastName").text('');
      $("#validLastName").css('color', '');
    }

    if(txtPName != ""){      
      $("#txtPName").css("border-color", '');
      $("#validPracticesName").text('');
      $("#validPracticesName").css('color', '');
    }
   
  });

  $('.search').keyup(function() {
    
    $.ajax({
      url: "{{ route('search_practices') }}",
      type: 'POST',
      data: {
        search: $('.search').val(),
        "_token": "{{ csrf_token() }}",
      },

      success: function(response) {
          $('.fetch-practices-data').html(response);
      }
    });
  })
  
  function sendLogin(id){
    strawberry.loader.showFull();
    $.ajax({
      url:'{{route("sendLoginPwd")}}',
      type:'POST',
      data:{'id':id},
      success: function(response) {
        strawberry.loader.hideFull();
        if(response > 0){
          $(".fade").removeClass('modal-backdrop');
          strawberry.toast.show("Invitation sent successfully", "success");
        }else{
          $(".fade").removeClass('modal-backdrop');
          strawberry.toast.show("Invitation sent Failed", "danger");
        }
        
      }
    });
  }

  function editPracticeDetails(id, company_id){
    $('.model_basic_details').show();
    $('.practice_specific').hide();
    $('#tab_2').removeClass('active');
    $('#tab_1').addClass('active');

    $.ajax({
      url:'{{route("edit_practice_details")}}',
      type:'POST',
      data:{
        id:id,
        company_id:company_id,
      },
      success: function(response) {
        
        $('#company_id').val(company_id);
        $('#user_id').val(id);
        $('#pName').val(response.name);
        $('#city').val(response.city);
        $('#address').val(response.address);
        $('#state').val(response.state);
        $('#zipcode').val(response.pincode);
        $('#cName').val(response.contact_person_name);
        $('#cEmail').val(response.email);
        $('#cPhone').val(response.phone);
        $('#practices_count option[value="'+response.types_of_practice+'"]').prop('selected', true);
        $('#entity_count option[value="'+response.entity_type+'"]').prop('selected', true);
        $('#operatories_count option[value="'+response.operatories+'"]').prop('selected', true);
        $('#owners_count option[value="'+response.total_owner+'"]').prop('selected', true);
        $('#employee_count option[value="'+response.total_employee+'"]').prop('selected', true);
        $('#fte_count option[value="'+response.total_fte+'"]').prop('selected', true);

        $('#start_year').val(response.start_year);
        $(":checkbox[value=4]").prop("checked","true");

        $('input[name="rdgPreferred"][value="' + response.prefer_communication + '"]').prop("checked", true);
        $("input[name=rdgMill][value=" + response.is_milling_unit + "]").prop('checked', true);
        $("input[name=rdgImplants][value=" + response.implants + "]").prop('checked', true);
        $("input[name=rdgAligner][value=" + response.has_aligner_services + "]").prop('checked', true);

        var _saveYr = $.trim($('#start_year').val());
        if(_saveYr == "") {
          _saveYr = 2019;
        }
        $('.year_started').find("[data-year='"+_saveYr+"']").addClass('active');
        $('.year-dropdown a').find('#year').text(_saveYr);
        //$("#myselect option[value=3]").attr('selected', 'selected');
      
        /*if(response > 0){
          $(".fade").removeClass('modal-backdrop');
          strawberry.toast.show("Invitation sent successfully", "success");
        }else{
          $(".fade").removeClass('modal-backdrop');
          strawberry.toast.show("Invitation sent Failed", "danger");
        }*/
      }
    });
   
  }
  
  /*$(".close").click(function(){
    window.location.reload();
  });*/

  /*$("#close_model").click(function(){
    $("#email").val("");
    $("#txtCName").val("");
    $("#txtCLName").val("");
    $("#txtPName").val("");
    $("#txtCName").css("border-color", '');
    $("#txtCLName").css("border-color", '');
    $("#txtPName").css("border-color", '');
    $("#email").css("border-color", '');
    $("#validEmail").val("");
  });*/

  $('#btnSaveDetails').click(function () {
    /*strawberry.dropdownFormBubbler();
    strawberry.dropdownFormBubbler();
    strawberry.dropdownSelectToggler();*/
    var pName   = $.trim($("#pName").val());
    var address = $.trim($("#address").val());
    var state   = $.trim($("#state").val());
    var city    = $.trim($("#city").val());
    var zipcode = $.trim($("#zipcode").val());
    var cName   = $.trim($("#cName").val());
    var cPhone  = $.trim($("#cPhone").val()); 
    var cEmail  = $("#cEmail").val();
    var rdbPreferred_email = document.getElementById('rdbPreferred_email').checked;
    var rdbPreferred_text   = document.getElementById('rdbPreferred_email').checked;
    if(pName == "" || address == "" || state == "" || city == "" || zipcode == "" || cName == "" || cPhone =="" || cEmail =="" ){    

      if(pName == ""){ 
        $("#errorMsg").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please enter the practice name.</label>');  
         return;
      }else{
        $("#errorMsg").html('');
      }
      if(address == ""){  
        $("#errorMsg").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please enter the address.</label>');
            return;
      }else{
          $("#errorMsg").html('');
      }
      if(city == ""){
        $("#errorMsg").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please enter the city.</label>');
           return;
      }else{
            $("#errorMsg").html('');
      }
      if(state == ""){
        $("#errorMsg").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please enter the state.</label>');
            return;
      }else{
        $("#errorMsg").html('');
      }
     
      if(zipcode == ""){
        $("#errorMsg").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please enter the zipcode.</label>');
            return;
      }else{
        $("#errorMsg").html('');
      }

      if(cName == ""){
        $("#errorMsg").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please enter the contact person name.</label>');
          return;
      }else{
        $("#errorMsg").html('');
      }

      if(cPhone == ""){
        $("#errorMsg").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please enter the contact phone number.</label>');
           return;
      }else{
        $("#errorMsg").html('');
      }
      if(cEmail == ""){
        $("#errorMsg").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please enter the contact email.</label>');
        return; 
      }else{
        $("#errorMsg").html('');
      }

      return false;
    }else{
     $("#errorMsg").html('');
          
      var _btn = $(this);
      _btn.text('Saving... Please wait');
      _btn.addClass('btn-processing');
      _btn.prop('diasbled', true);

      setTimeout(function () {
        _btn.popover({
          html: true,
          content: "<i class='fas fa-check-circle mr-2'></i> Basic details saved successfully",
          trigger: 'focus',
          placement: 'top',
          template: '<div class="popover border-success" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
        }).popover('show');
      }, 2000);
    }

    var formData = $('#basic_information').serialize();
    $.ajax({
      url:WEBURL+'/basicInformationSvd',
      type:'POST',
      data:formData,
      success: function(response) {
        btnSaveIndustry();

        
        setTimeout(function () {
          //_btn.popover('dispose');
          //slider.showIndustry();
          /*$('.model_basic_details').hide();
          $('.btnSaveBasic').hide();
          $('.practice_specific').show();
          $('.btnSaveIndustry').show();
          */
        }, 2000);
      }
    });
  })

  function btnSaveIndustry() {
    var year                 = $("#year").text();
    var operatories_count    = $("#operatories_count").val();
    var entity_count         = $("#entity_count").val();
    var practices_count      = $("#practices_count").val();
    var owners_count         = $("#owners_count").val();
    var employee_count       = $("#employee_count").val();
    var fte_count            = $("#fte_count").val(); 
    var rdgMill              = $("input[name='rdgMill']:checked").val();
    var rdgImplants          = $("input[name='rdgImplants']:checked").val();
    var rdgAligner           = $("input[name='rdgAligner']:checked").val();
  
    if(year == "" || operatories_count == "" || entity_count =="" || practices_count==""  | owners_count == "" || employee_count =="" || fte_count =="" || rdgMill=="" || rdgImplants =="" || rdgAligner == ""){
      if(year == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please select the year.</label>');  
        return;
      }else{
        $("#errorMsg").html('');
      }
      if(operatories_count == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please select operatories count.</label>');  
        return;
      }else{
        $("#errorMsg").html('');  
      }
      if(entity_count == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please select entity count.</label>');  
       return;
      }else{
        $("#errorMsg").html('');  
      }
      if(practices_count == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please select practices count.</label>');  
       return;
      }else{
        $("#errorMsg").html('');  
      }
      if(owners_count == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please select owners count.</label>');  
       return;
      }else{
          $("#errorMsg").html('');  
      }
      if(employee_count == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please select employee count.</label>');  
       return;
      }else{
         return false;
      }
      if(fte_count == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please select fte count.</label>');  
       return;
      }else{
        $("#errorMsg").html('');  
      }
      if(rdgMill == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please choose practices mill units.</label>');  
       return;
      }else{
        $("#errorMsg").html('');  
      }
      if(rdgImplants == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please choose offer implants.</label>');  
       return;
      }else{
          $("#errorMsg").html('');  
      }
      if(rdgAligner == ""){
        $("#errorMsg1").html('<label class="alert alert-danger py-1 my-0" id="lblErrorBasic">Please choose clean aligner services.</label>');  
       return;
      }else{
          $("#errorMsg").html('');  
      }
    }else{
      $("#errorMsg").html('');  

      var _btn = $(this);

      _btn.text('Saving... Please wait');
      _btn.addClass('btn-processing');
      _btn.prop('diasbled', true);

      setTimeout(function () {
      _btn.popover({
        html: true,
        content: "<i class='fas fa-check-circle mr-2'></i> Practice Specifics saved successfully",
        trigger: 'focus',
        placement: 'top',
        template: '<div class="popover border-success" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
        }).popover('show');
      }, 2000);
  
      var formData = {
        'user_id'           : $("#user_id").val(),
        'year'              : $("#year").text(),
        'operatories_count' : $("#operatories_count").val(),
        'entity_count'      : $("#entity_count").val(),
        'practices_count'   : $("#practices_count").val(),
        'owners_count'      : $("#owners_count").val(),
        'employee_count'    : $("#employee_count").val(),
        'fte_count'         : $("#fte_count").val(),
        'rdgMill'           : $("input[name='rdgMill']:checked").val(),
        'rdgImplants'       : $("input[name='rdgImplants']:checked").val(),
        'rdgAligner'        : $("input[name='rdgAligner']:checked").val(),
      }
      $.ajax({
        url:WEBURL+'/practiceSpecificsSvd',
        type:'POST',
        data:formData,
        success: function(response) {
          if(response > 0){
             setTimeout(function () {
              _btn.popover('dispose');
              //slider.showQB();
              window.location.reload();
            }, 2000);

         }
         
        }
      });
    }
  }

  /*$('.btn-back').click(function() {
      $('.model_basic_details').show();
      $('.btnSaveBasic').show();
      $('.practice_specific').hide();
      $('.btnSaveIndustry').hide();
      _btn = $('#btnSaveBasic');
      _btn.html('<i class="fas fa-check mr-1"></i> Save and coninue');
      _btn.removeClass('btn-processing');
      _btn.prop('diasbled', false);
  })*/

    function changeTab(changeId) {
      
      if(changeId == 1) {

        $('.model_basic_details').show();
        $('.practice_specific').hide();
        $('#tab_2').removeClass('active');
        $('#tab_1').addClass('active');
      } else {
       
        $('.model_basic_details').hide();
        $('.practice_specific').show();
        strawberry.dropdownFormBubbler();
        strawberry.dropdownSelectToggler();
        $('#tab_1').removeClass('active');
        $('#tab_2').addClass('active');
      }
    }

    $('.subscription').click(function() {
      var user_id = $(this).data('userid');
      if($(this).prop("checked") == true){
        checked = 1;
      } else {
        checked = 0;
      }
      strawberry.dialog.confirm({
        title: 'Subscription Status',
        body : 'Are you sure you want to change subscription status?',
        yes  : confirmReport(user_id, checked),
      })
    });

    function confirmReport(user_id, checked) {   
      $.ajax({
        url: "{{ route('update_subscription') }}",
        type: 'POST',
        data: {
          user_id: user_id,
          checked : checked,
          "_token": "{{ csrf_token() }}",
        },

        success: function(response) { 
        }
      });
    }

    function deleteUser() {
      strawberry.dialog.confirm({
        title: 'Delete Practices',
        body : 'Are you sure, you want to delete the practice? If you are 100% certain you want to delete it, click Yes... but you will not be able to get it back.Type the word DELETE below to confirm deletion.<br><br></label><input type="text" name="comment" id="comment" style="width: 250px;">',
        yes  : confirmDelete,
      })

      var strcomment = $('#comment').val();
       if($('#comment').val() == ''){
          $('.btn-yes').hide();
        } else {
          if(strcomment.toUpperCase() == "DELETE")
            $('.btn-yes').show();
          else
            $('.btn-yes').hide();
        }

      function confirmDelete() 
      {
       var company_id = "{{ Session::get('company')['company_id'] }}";
         $.ajax({
            url: '{{route("confirm_delete")}}',
            type: 'POST',
            data: {
              'company_id' : company_id,
            },
            success: function(response) {
              window.location.href = '{{ route('select_company') }}';
            }
        });
      }
    }

    $(window).on("keyup", function(){
      var strcomment = $('#comment').val();
      if($('#comment').val() == ''){
        $('.btn-yes').hide();
      } else {
        if(strcomment.toUpperCase() == "DELETE")
          $('.btn-yes').show();
        else
          $('.btn-yes').hide();
      }
    });

</script>

<style type="text/css">
  .year_started {
    transform: translate3d(0px, 60px, 0px); 
    max-height: 250px;
    overflow-y: auto;
  }

    /* Style the tab */
  .tab {
    overflow: hidden;
    background-color: #f8f9fa;
  }

  /* Style the buttons inside the tab */
  .tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
  }

  /* Change background color of buttons on hover */
  .tab button:hover {
    background-color: #ddd;
  }

  /* Create an active/current tablink class */
  .tab button.active {
    background-color: #ccc;
  }

  /* Style the tab content */
  .tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
  }
</style>

@endsection