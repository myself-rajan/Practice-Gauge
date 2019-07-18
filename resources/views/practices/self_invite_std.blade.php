<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pratice Gauge - Registration</title>

    <!-- Animate.CSS CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
      integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <!-- jQuery-UI Framework -->
    <script src="{{asset('/js/jQueryUI/jquery-ui.min.js')}}"></script>
    <link href="{{asset('/js/jQueryUI/jquery-ui.structure.min.css')}}" rel="stylesheet" />
    <link href="{{asset('/js/jQueryUI/jquery-ui.min.css')}}" rel="stylesheet" />

    <!-- Popper CSN (Bootstrap 4 requires this to work)-->
    <script src="https://unpkg.com/popper.js/dist/umd/popper.min.js"></script>

    <!-- Bootstrap CDN -->
    <link href="https://bootswatch.com/4/cosmo/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Cosmo from bootswatch -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
      integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
      crossorigin="anonymous"></script>


    <!-- Bootstrap Overrides - JONES -->
    <link href="{{asset('/css/bootstrap.overrides.css')}}" rel="stylesheet" />
    <link href="{{asset('/css/bootstrap.xxl.css')}}" rel="stylesheet" />

    <!-- Strawberry CSS - JONES -->
    <link href="{{asset('css/strawberry.css')}}" rel="stylesheet" />
      <link href="{{asset('css/strawberry.min.768.css')}}" rel="stylesheet" />
    <!-- Responsive CSS -->
    <link href="{{asset('css/strawberry.utilities.css')}}" rel="stylesheet" />
    <!-- Utility CSS -->


    <link href="{{asset('css/app_modules/integration.css')}}" rel="stylesheet" />
    <link href="{{asset('css/app_modules/connected.list.css')}}" rel="stylesheet" />
    <link href="{{asset('css/app_modules/registration.css')}}" rel="stylesheet" />

    <script src="{{ asset('/js/strawberry.core.js') }}"></script>
    <script src="{{ asset('/js/strawberry.js') }}"></script>

    <script src="{{asset('/js/views/registration_std.js')}}"></script>
    <script type="text/javascript">
      //Globally Set Web URL
      var l      = window.location;
      var WEBURL = l.protocol + "//" + l.host ;

      //Ajax Setup Token
       //Ajax Setup Token
      $(function () { 
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
      });
    </script>
  </head>

  <body>
    <div class="registration-wrapper">
      <div class="registration-panel">
        <div id="registrationSlider" class="carousel slide">
          <div class="carousel-inner">
            <div class="carousel-item {{($steps == 2)?'':'active'}}">
              <!-- Basic Details -->
              <section id="secBasic">
                <div class="card border-0 mt-0 mb-4 mb-lg-0 mt-lg-0">
                  <div class="card-body border-bottom">
                    <div class="row">
                      <div class="col-4 col-fhd-3">
                        <img class="img-fluid" src="{{asset('img/logo.svg')}}" />
                      </div>

                      <div class="col-8 col-fhd-9 text-right d-none">
                        <button class="btn btn-light shadow-sm" href="login.html">
                          <i class="fas fa-sign-out-alt mr-1"></i>
                          Logout</button>
                      </div>
                    </div>

                  </div>

                  <div class="card-body border-bottom border-info border-2">
                    <span class="float-right text-center">
                      Step 1 of 3
                      <div class="progress" style="height: 5px; width:100px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar"
                          aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 33%"></div>
                      </div>
                    </span>
                    <h5 class="my-0 text-muted">Complete the following steps to define your
                      practice.</h5>

                  </div>
                  <div class="card-body bg-light py-2">
                    <h4 class="my-0">Basic Details</h4>
                  </div>

                  <form id="basic_information_std" name="basic_information_std" method="post">
                    <input type="hidden" name="user_id" id="user_id" value="{{isset($_GET['confirmation_code'])?base64_decode($_GET['confirmation_code']):''}}">
                    @csrf
                   <!--  <input type="hidden" name="company_id" id="company_id" value="{{isset($_GET['practices'])?base64_decode($_GET['practices']):''}}"> -->
                    <div class="card-body pb-0">
                      <div class="row">
                        <div class="col-sm-4">
                          <div class="form-group">
                            <label>Practice name <i class="fas fa-info-circle text-warning ml-1" data-toggle="tooltip"
                                title="Legal name of your practice"></i></label>
                            <input type="text" class="form-control form-control-sm" placeholder="" id="pName" name="pName" value="{{isset($practices_name)?$practices_name:''}}"  tabindex="1"/>
                          </div>
                          <div class="form-group">
                            <label>City</label>
                            <input type="text" class="form-control form-control-sm" placeholder="" name="city" id="city" value="" tabindex="3"/>
                          </div>
                        </div>
                        <div class="col-sm-8">
                          <div class="form-group">
                            <label>Practice Address</label>
                            <input type="text" class="form-control form-control-sm" placeholder="" id="address" name="address" value="" tabindex="2"/>
                          </div>

                          <div class="row">
                            <div class="col">

                              <div class="form-group">
                                <label>State</label>
                                <select class="form-control form-control-sm mb-3 validate_blur" id="state" name="state">
                                  <option value="">Select State</option>
                                  @foreach($states as $row)
                                    <option value="{{ $row['name'] }}">{{ $row['name'] }}</option>
                                  @endforeach
                                </select>
                                <!-- <input type="text" class="form-control form-control-sm" placeholder="" id="state" name="state"value="" tabindex="4"/> -->
                              </div>
                            </div>
                            <div class="col">

                              <div class="form-group">
                                <label>Zip</label>
                                <input type="text" class="form-control form-control-sm" placeholder="" id="zipcode" name="zipcode" value="" maxlength="6" tabindex="5"/>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row border-top mt-3">
                        <div class="col-4 pb-3">
                          <div class="form-group mt-3">
                            <label>Contact person name</label>
                            <input class="form-control form-control-sm" type="text" id="cName" name="cName" placeholder="" value="{{isset($name)?$name:''}}" tabindex="6"/>
                          </div>

                          <div class="form-group">
                            <label>Contact person email</label>
                            <input class="form-control form-control-sm" type="text" id="cEmail" name="cEmail" placeholder="" value="{{isset($email)?$email:''}}" tabindex="8" disabled />
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group mt-3">
                            <label>Contact person phone</label>
                            <input class="form-control form-control-sm" type="text" id="cPhone" name="cPhone" placeholder="" value="" maxlength="10" tabindex="7"/>
                          </div>
                        </div>
                        <div class="col-4 bg-light border-left pb-3">

                          <div class="form-group mt-3">
                            <label>Preferred communication Method<i class="fas fa-info-circle text-warning ml-1"
                                data-toggle="tooltip"
                                title="Select the best way to communicate with the contact person"></i></label>
                            <div class="custom-control custom-checkbox">
                              <input type="radio" id="rdbPreferred_email" name="rdgPreferred" class="custom-control-input" value="1" tabindex="9"
                                checked>
                              <label class="custom-control-label" for="rdbPreferred_email">Email</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                              <input type="radio" id="rdbPreferred_text" name="rdgPreferred" class="custom-control-input" value="2" tabindex="10">
                              <label class="custom-control-label" for="rdbPreferred_text">Text (Phone)</label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="card-footer">
                      <div class="clearfix">
                        <div id="errorMsg"></div>
                        <button type="button" class="float-right btn btn-sm btn-primary" id="btnSaveBasic" tabindex="11"><i
                            class="fas fa-check mr-2"></i>Save
                          and
                          Continue</button>
                      </div>
                    </div>
                  </form>
                </div>
              </section>
            </div>

            <div class="carousel-item {{($steps == 2)?'active':''}}">
              <!-- Select Industry Panel-->
              <section id="secIndustry">
                <div class="card border-0 mt-0 mb-4 mb-lg-0 mt-lg-0">
                  <div class="card-body border-bottom">
                    <div class="row">
                      <div class="col-4 col-fhd-3">
                        <img class="img-fluid" src="{{asset('img/logo.svg')}}" />
                      </div>

                      <div class="col-8 col-fhd-9 text-right d-none">
                        <button class="btn btn-light shadow-sm" href="login.html">
                          <i class="fas fa-sign-out-alt mr-1"></i>
                          Logout</button>
                      </div>
                    </div>
                  </div>

                  <div class="card-body border-bottom border-info border-2">
                    <span class="float-right text-center">
                      Step 2 of 3
                      <div class="progress" style="height: 5px; width:100px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 33%;" aria-valuenow="33"
                          aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar"
                          aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%"></div>
                      </div>
                    </span>
                    <h5 class="my-0 text-muted">Complete the following steps to define your
                      practice.</h5>
                  </div>
                  <div class="card-body bg-light py-2">
                    <h4 class="my-0">
                      <button class="btn btn-sm btn-light btn-back" data-toggle="tooltip"
                        title="Go back to Basic Details"><i class="fas fa-chevron-left mx-1"></i></button>
                      <span class="align-middle">Practice Specifics</span>
                    </h4>
                  </div>

                  <form id="practice_specify" name="practice_specify" method="post">
                      <input type="hidden" name="user_id" id="user_id" value="{{isset($_GET['confirmation_code'])?base64_decode($_GET['confirmation_code']):''}}">
                      <input type="hidden" name="company_id" id="company_id" value="{{isset($_GET['practices'])?base64_decode($_GET['practices']):''}}">

                  <div class="card-body border-top">
                    <div class="row">
                      <div class="btn-group flex-fill" role="group">
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
                          <div class="year_started dropdown-menu shadow">
                            @foreach($yearArr as $key => $year)
                              <a class="dropdown-item {{  isset($as_year) && $year == $as_year ? 'active' : '' }}">{{ $year }}</a>                       
                            @endforeach
                          </div>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label># Operatories<i class="fas fa-info-circle text-warning ml-1" data-toggle="tooltip"
                              title="How many operatories does the practice have? "></i></label>
                          <select class="form-control form-control-sm" data-toggle="range" data-min="1" data-max="50" id="operatories_count" name="operatories_count">

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
                          </select>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label>Total Number of FTE Employees (including owners and associates)<i class="fas fa-info-circle text-warning ml-1" data-toggle="tooltip"
                              title="Number of employees in this practice (including the owner) "></i></label>
                          <select class="form-control form-control-sm" data-toggle="range" data-min="1" data-max="50" id="employee_count" name="employee_count">

                          </select>

                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label># of FTE Associates<i class="fas fa-info-circle text-warning ml-1" data-toggle="tooltip"
                              title="Number of full time equivalent doctors in practice"></i></label>
                          <select class="form-control form-control-sm" data-toggle="range" data-min="1" data-max="50" id="fte_count" name="fte_count">

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
                        Does the practice offer clean aligner services?
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
                  <div class="card-footer">
                    <div class="clearfix">
                          <div id="errorMsg"></div>
                      <button class="float-right btn btn-sm btn-primary" type="button" id="btnSaveIndustry">
                        <i class="fas fa-check mr-2"></i>Save
                        and
                        Continue</button>
                    </div>
                  </div>
                </div>
                </form>
              </section>
            </div>

            <div class="carousel-item {{($steps == 3)?'active':''}}">
              <!-- QuickBooks Online -->
              <section id="secQB">
                <div class="card border-0 mt-0 mb-4 mb-lg-0 mt-lg-0">
                  <div class="card-body border-bottom">
                    <div class="row">
                      <div class="col-4 col-fhd-3">
                        <img class="img-fluid" src="{{ asset('img/logo.svg') }}" />
                      </div>

                      <div class="col-8 col-fhd-9 text-right d-none">
                        <button class="btn btn-light shadow-sm" href="login.html">
                          <i class="fas fa-sign-out-alt mr-1"></i>
                          Logout</button>
                      </div>
                    </div>
                  </div>

                  <div class="card-body border-bottom border-info border-2">
                    <span class="float-right text-center">
                      Step 3 of 3
                      <input type="hidden" name="user_id_qbo" id="user_id_qbo"  value="{{isset($_GET['confirmation_code'])?base64_decode($_GET['confirmation_code']):''}}">
                      <div class="progress" style="height: 5px; width:100px;" id="prgEnd">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 66%;" aria-valuenow="66"
                          aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar"
                          aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" style="width: 34%"></div>
                      </div>
                    </span>
                    <h5 class="my-0 text-muted">Complete the following steps to define your
                      practice.</h5>
                  </div>

                  <div class="card-body bg-light py-2">
                    <h4 class="my-0">
                      <button class="btn btn-sm btn-light btn-back" data-toggle="tooltip"
                        title="Go back to Business Models"><i class="fas fa-chevron-left mx-1"></i></button>
                      <span class="align-middle">
                        QuickBooks Online Integration
                      </span>
                    </h4>
                  </div>

                  <div class="card-body border-bottom">
                    <div class="form-group" id="chkQB_wrapper">
                      <!-- <a href="#" class="mb-2 d-block">Read our terms and policy regarding QuickBooks
                        integration.</a> -->
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" id="chkQB_yes" class="custom-control-input">
                        <label class="custom-control-label" for="chkQB_yes">I have read the above terms and policy, and
                          allow Practice Gauge to connect to my QuickBooks account</label>
                      </div>
                    </div>
                  </div>

                  <div class="card-body text-center disabled" id="divQB">
                    <div id="divQB_0">
                      <div class="waiting qb mb-4">
                        <img class="primary" src="{{asset('img/logo.svg') }}" />
                        <i class="fas fa-times mx-4 animated tada"></i>
                        <img class="external" src="{{asset('img/qb_3.png') }}" />
                      </div>

                      <button href="#" class="btn-qb"></button>
                      <button class="float-right btn btn-sm btn-primary" type="button" id="skip_qbo"  >Skip</button>
                    </div>

                    <div id="divConnectQB" style="display:none"><!-- divQB_1 -->
                      <label>Please wait while we connect to QuickBooks Online</label>
                      <div class="waiting qb mb-4">
                        <img class="primary" src="{{ asset('img/logo.svg') }}" />
                        <span class="_1"></span>
                        <span class="_2"></span>
                        <span class="_3"></span>
                        <span class="_4"></span>
                        <img class="external" src="{{asset('img/qb_3.png') }}" />
                      </div>
                      <div class="text-muted pt-3">
                        A new window has opened. Please login to your QuickBooks account to integrate with
                        Practice Gauge.<br />
                        <a href="javascript:doQuickbooksLogin();" data-href = "{{ $qbo_auth_url }}" target="_blank" class="text-primary qbo_connect_url">Click here</a> if the window hasn't opened.
                      </div>
                    </div>

                    <div id="divFetchQB" style="display:none">
                      <label>Please wait while we fetch data from QuickBooks Online</label>

                      <div class="waiting qb mb-4">
                        <img class="primary" src="{{ asset('img/logo.svg') }}" />
                        <i class="fas fa-sync mx-4 animated tada"></i>
                        <img class="external" src="{{asset('img/qb_3.png') }}"  />
                      </div>

                      <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                          aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                      </div>
                      <div class="text-muted">Fetching Accoutnts...</div>
                    </div>

                    <!-- <div id="divSuccessQB" style="display:none">

                      <i class="fas fa-check-circle text-success fa-3x mt-3">

                      </i>
                      <h3 class="mt-4">All Done</h3>
                      <div class="my-5">You have successfully configured your practice and inetegrated with QuickBooks
                      </div>

                    </div> -->
                  </div>

                  <!-- <div class="card-footer text-center text-muted d-none" id="divTheEnd">
                    <button class="btn btn-sm btn-success" onclick="window.close()"><i
                        class="fas fa-check-circle mr-1"></i> Done</button>
                  </div> -->
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="registration-footer-panel">
      <a href="#">Terms & Conditions</a>
      <a href="#">Privacy Policy</a>
    </div>
  </body>
</html>

<script type="text/javascript">
  $('.btn-qb').click(function() {
    $('#divConnectQB').show();
    doQuickbooksLogin();
  })

  function doQuickbooksLogin() {
    var qbo_url = $('.qbo_connect_url').data('href');
    window.open(qbo_url, '_blank',"width=800,height=740");
  }

  $(document).ready(function(){
    if ($('#chkQB_yes').is(':checked')) {
      $('#chkQB_yes').prop('checked', false); 
    }

    var check_error = getQueryParam('error');
    //doQuickbooksLogin();
    if(check_error == 'true'){
      strawberry.toast.show('QuickBooks file error', 'danger');
    }
  })
  
  $('input[name="cPhone"]').keyup(function(e) {
    if (/\D/g.test(this.value))
    {
      // Filter non-digits from input value.
      this.value = this.value.replace(/\D/g, '');
    }
  });

  $('input[name="zipcode"]').keyup(function(e) {
    if (/\D/g.test(this.value)) {
      // Filter non-digits from input value.
      this.value = this.value.replace(/\D/g, '');
    }
  });

  $("#skip_qbo").click(function(){
    strawberry.loader.showFull();
    var user_id_qbo = $("#user_id_qbo").val();

    $.ajax({
      url:"{{ route('skipQboStd') }}",
      type:"POST",
      data:{type:"skip",user_id_qbo:user_id_qbo},
      success: function(response) {
        strawberry.loader.hideFull();
        if(response > 0){
          window.location.href= WEBURL+'/qbo/skipsuccess';
        }else{
          $(".fade").removeClass('modal-backdrop');
          strawberry.toast.show("Mail sent failure", "warning");
        }
      }
    })
  })
</script>

<style type="text/css">
  .year_started {
    transform: translate3d(0px, 60px, 0px); 
    max-height: 250px;
    overflow-y: auto;
  }
</style>


