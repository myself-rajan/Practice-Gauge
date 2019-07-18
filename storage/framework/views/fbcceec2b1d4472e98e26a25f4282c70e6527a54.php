<?php $__env->startSection('content'); ?>

<script src="<?php echo e(asset('/js/views/home.js')); ?> "></script>
<link rel="stylesheet" href=" <?php echo e(asset('/css/app_modules/dashboard.css')); ?>">


<script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<!--<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.min.js"></script>  -->
<div class="mb-3 d-flex justify-content-end">
  <?php if(isset($company)): ?>
    <?php if($company->is_report_verified == 1): ?>
      <label class="badge badge-pill badge-success mb-0 px-3 py-2 mr-2">
      <span>Verified
        <i class="fas fa-check-circle" aria-hidden="true"></i>
      </span>
      </label>
    <?php else: ?>
      <button class="btn btn-primary px-3 py-1 mr-2" onclick="verifyReport();">Mark as Verified</button>
    <?php endif; ?> 
  <?php endif; ?>
  <button onclick="saveAsPDF();" class="btn btn-primary px-3 py-1">Export To PDF</button>
</div>

<div style="overflow: hidden">
  <div class="dashboard-caption bg-white rounded-pill mb-2 shadow-sm">
    <i class="fas fa-table"></i>
    <div class="d-flex w-100">
      <div class="flex-fill">
        <h5 class="mb-0">
          Profitability Measures
        </h5>
      </div>


      <div class="">
        <div class="dropdown">
          <button class="btn btn-light rounded-pill filter_label" data-toggle="dropdown">As on Dec 31, 2016, 2017, 2018 and
            2019</button>
          <form class="dropdown-menu p-4 dropdown-menu-right rounded shadow" style="width: 270px;transform: translate3d(471px, 42px, 11px);!important">
            <?php echo csrf_field(); ?>
            <div class="form-group">
              <label for="exampleDropdownFormEmail2">As on date</label>
              <div class="input-group input-group-sm mr-10">
                <select class="form-control form-control-sm w-auto" id="filter_type" name="filter_type">
                  <option value="1" selected>Year to date</option>
                  <!-- <option value="2">Month</option> -->
                  <option value="3">This Month</option>
                  <option value="4">Last Month</option>
                  <option value="5">This Quarter</option>
                  <option value="6">Last Quarter</option>
                  <option value="7">This Year</option>
                  <option value="8">Last Year</option>
                  <option value="9">Custom Date Range</option>
                </select>

                <select class="form-control form-control-sm w-auto" id="month" name="month">
                  <option value="1">January</option>
                  <option value="2">February</option>
                  <option value="3">March</option>
                  <option value="4">April</option>
                  <option value="5">May</option>
                  <option value="6">June</option>
                  <option value="7">July</option>
                  <option value="8">August</option>
                  <option value="9">September</option>
                  <option value="10">October</option>
                  <option value="11">November</option>
                  <option value="12">December</option>
                </select>

                <input type="hidden" id="date_val" name="date_val" value="31">
              </div>
            </div>

            <div class="mt-2 date_div" style="display: none">
              <div class="form-group">
                <label>From</label>
                <input class="form-control form-control-sm" type="date" id="from_date">
              </div>        
     
              <div class="form-group">
                <label>To</label>
                <input class="form-control form-control-sm" type="date" id="to_date">
              </div>     
            </div>

            <div class="row compare_with" style="display: none">
              <div class="col-12">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" name="compare_with" id="compare_with" value="1" checked="">
                  <label class="custom-control-label" for="compare_with">Compare another period</label>
                </div>
              </div>
            </div><br>

            <div class="form-group years_div">
              <label for="exampleDropdownFormPassword2">Select years</label>
              <div class="row">
                <div class="col-6">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="year" id="chk2016" value="2016" checked>
                    <label class="custom-control-label" for="chk2016">2016</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="year" id="chk2017" value="2017" checked>
                    <label class="custom-control-label" for="chk2017">2017</label>
                  </div>
                </div>
                <div class="col-6">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="year" id="chk2018" value="2018" checked>
                    <label class="custom-control-label" for="chk2018">2018</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="year" id="chk2019" value="2019" checked> 
                    <label class="custom-control-label" for="chk2019">2019</label>
                  </div>
                  <?php 
                  if(date("Y") > 2019) {
                    for($i=2019; $i<date("Y") ; $i++) {?>
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="year" id="chk<?php echo e(date('Y')); ?>" value="<?php echo e(date('Y')); ?>" checked> 
                        <label class="custom-control-label" for="chk<?php echo e(date('Y')); ?>"><?php echo e(date('Y')); ?></label>
                      </div>
                      <?php
                    }
                  }?>
                </div>
              </div>
            </div>

            <div class="form-group compare_period" style="display: none;">
              <div class="row">
                <div class="col-12">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="compare_period" id="chkPP" value="PP" checked>
                    <label class="custom-control-label" for="chkPP">Previous Period(PP)</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="compare_period" id="chkPY" value="PY" checked>
                    <label class="custom-control-label" for="chkPY">Previous Year(PY)</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="compare_period" id="chkYTD" value="YTD" checked>
                    <label class="custom-control-label" for="chkYTD">Year-to-date(YTD)</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="compare_period" id="chkLTY" value="LTY" checked>
                    <label class="custom-control-label" for="chkLTY">Last Two Years(LTY)</label>
                  </div>
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-primary btn-sm py-1 btn-block" id="apply_filter" data-toggle="dropdown" >Apply Filter</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row"> 
      <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm mb-4 table_sm  ">
          <div class="card-body">
            <h4 class="gradient-title">Monthly Net Collection Trends
            </h4>
            <span class="monthly_collection_data"></span>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 table_sm  ">
        <div class="card border-0 mb-4">
          <div class="card-body">
            <h4 class="gradient-title mb-4">Net Collection vs Net Operating Income</h4>
            <span class="gross_net_collection"></span>
          </div>
        </div>
      </div>

      <div class="col-12 table_sm">
        <div class="card border-0  mb-4">
          <div class="card-body">
            <h4 class="gradient-title mb-4">Overhead Analysis</h4>
            <span class="operationasl_expenses"></span>
          </div>
        </div>
      </div>
  </div>


  <div class="" id="fullPage" style="display: none;"><!-- style="visibility: hidden" -->
    <div id="contentPage" style="margin-left:10%;padding: 0px 0px 0px 0px;width: 80%;height: 100%;"><!-- style="margin-left:10%;padding: 0px 0px 0px 0px;width: 80%;height: 100%;" -->
        <div><!--  style="height:100px" -->
          <h2 style="text-align: center"><?php echo e(\Session::get('company')['global_company_name']); ?>

          </h2>
          <input type="hidden" name="company_name" id="company_name" value="<?php echo e(\Session::get('company')['global_company_name']); ?>">
          <h3 class="setYear" style="text-align: center">
          </h3>
        </div>

        <div>
          <div>
            <div class="card  rounded card-chart">
              <div class="card-header">
                <h5 class="mb-0">
                  <i class="far "></i>
                Monthly Net Collection Trends</h5>
              </div>
              <div class="card-body">
                
                <div class="" id="cns_parent">
                  <canvas id="cnvMonthlyCol" class="chartjs" ></canvas>
                </div>
              </div>
            </div>
          </div>

          <div>
            <div class="card-body">
              <h3 style="font-weight: lighter;">Monthly Net Collection Trends
              </h3>
              <span class="monthly_collection_data" ></span>
            </div>
          </div>

          <div class="col-12 col-xl-12">
            <div class="card  rounded card-chart mb-5">
              <div class="card-header">
                <h5 class="mb-0">
                  <i class="fas "></i>
                Net Collection vs Net Operating Income</h5>
              </div>
              <div class="card-body">
                <div class="text-right mb-2">
                  <div class="dropdown d-inline-block mr-2" data-toggle="tooltip" title="Download">
                  </div>
                </div>

                <div id="bar_parent">
                  <canvas id="cnvGrossNet" class="chartjs"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class=" col-xl-12">
            <div class="card border-0  mb-4">
              <div class="card-body">
                <h3 class="mb-4">Net Collection vs Net Operating Income</h3>
                <span class="gross_net_collection"></span>
              </div>
            </div>
          </div>

          <div class="col-12 col-xl-12"> 
            <div class="rounded card-chart">
              <div class="card-header">
                <h5 class="mb-0">
                  <i class="fas fa-balance-scale"></i>
                Employee Costs</h5>
              </div>
              <div class="card-body">
                <div class="text-right mb-2">
                  <div class="dropdown d-inline-block mr-2" data-toggle="tooltip" title="Download">
                  </div>
                </div>

                <div class="position-relative" id="emp_cost_parent">
                  <canvas id="cnvEmpCostExp" class="chartjs"></canvas>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="col-12 col-xl-12">
            <div class="card  rounded card-chart">
              <div class="card-header">
                <h5 class="mb-0">
                  <i class="fas "></i>
                Overhead Analysis</h5>
              </div>
              <div class="card-body">
                <div class="text-right mb-2">
                  <div class="dropdown d-inline-block mr-2" data-toggle="tooltip" title="Download">
                  </div>
                </div>

                <div class="" id="analysis_parent">
                  <canvas id="cnvOprExp" class="chartjs"></canvas>
                </div>

                <div class="">
                </div>

              </div>
            </div>
          </div>

          <div class=" col-xl-12">
            <div class="card border-0  mb-4">
              <div class="card-body">
                <h3 class="mb-4">Overhead Analysis</h3>
                <span class="operationasl_expenses"></span>
              </div>
            </div>
          </div>

        </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $(document).ready(function() {
      $("#compare_with").removeAttr("checked");
      $('#month').hide();

      var currentYear = (new Date).getFullYear();
      getDashboard(checkedVal = (currentYear-3)+','+(currentYear-2)+','+(currentYear-1)+','+currentYear, month = "0", dateVal='', filter_type=1);
  });

  $(".formnewdropdown").submit(function(e) {
    e.preventDefault();
  });

  $('#filter_type').change(function() {
      //alert(121)
      //$('input:checkbox').removeAttr('checked');
      $('input:checkbox').each(function() { 
        this.checked = false; 
      });
      $('#chk2018').prop('checked',false);
      $('#chk2019').prop('checked',false);
      $('.years_div').show();

      val = $('#filter_type').val();
      $('#month').hide();
      $('.date_div').hide();
      $('.compare_with').hide();
      
      if(val == 2) {
        $('#month').show();
      }
      if(val == 9) {
        $('.date_div').show();
        $('.years_div').hide();
        $('.compare_with').show();
      }

      if(val == 8) {
        $('#chk2018').prop('checked',true);
      } else {
        $('#chk2019').prop('checked',true);
      }
  });

  $('.compare_with').change(function() {
    $('.compare_period').hide();
    if($('#compare_with').val() == 1) {
      $('.compare_period').show();
    }
  });

  $('#apply_filter').click(function() {
    var checked_arry = [];
    $.each($("input[name='year']:checked"), function(){            
      checked_arry.push($(this).val());
    });

    checkedVal = checked_arry.join(",");
   
    var period_arry = [];
    $.each($("input[name='compare_period']:checked"), function(){            
      period_arry.push($(this).val());
    });
    checked_period = period_arry.join(",");

    dateVal     = $('#date_val').val();
    filter_type = $('#filter_type').val();
    from_date = $('#from_date').val();
    to_date = $('#to_date').val();
    checked_box = $('#compare_with:checked').val();

    compare_with = 1; //Checkbox checked or not
    if(checked_box == undefined) {
      compare_with = 0;
    }

    var text = '';
    if(filter_type == 1) {
      text = 'As on ';
    }
    $('.filter_label').html(text +$("#filter_type option:selected").text()+' '+checkedVal);

    if(filter_type == 1)
      month = 0;
    else
      month = $('#month').val();


    /*$('#from_date').css('border-color', '');
    $('#to_date').css('border-color', '');
    if(filter_type == 9 && $('#from_date').val() == '') {
      $('#from_date').css('border-color', 'red');
      $('#to_date').css('border-color', 'red');
      return false;
    }*/
    getDashboard(checkedVal, month, dateVal, filter_type, from_date, to_date, compare_with, checked_period);
    //generatePdf(checkedVal, month, dateVal, filter_type, from_date, to_date);
  });

    function getDashboard(checkedVal, month, dateVal='', filter_type='', from_date='', to_date='', compare_with, checked_period) {
          var monthArr    = { 1 : "January", 2 : "February", 3 : "March", 4 : "April", 5 : "May", 6 : "June", 7 : "July", 8 : "August", 9 : "September", 10 : "October", 11 : "November", 12 : "December"};

          var yearVal = checkedVal.split(',');

          var now = new Date();
          var currentYear = now.getFullYear();
          var currentMonth = now.getMonth();
          var day = now.getDate();
          var num = currentYear.toString();
      
        if(filter_type == 1) {
          
          if (jQuery.inArray(num, yearVal) !== -1) {
           //January 1, 2016 - June 05, 2019 
            $('.setYear').html("January 1, "+yearVal[0]+' - '+monthArr[currentMonth]+' '+day+', '+yearVal[yearVal.length-1]);/*, '.current(yearVal).' - '.monthArr[ltrim(date('m'), '0')].' '.date("d").', '.date("Y")*/
          } 
          else {
            $('.setYear').html("January 1, "+yearVal[0]+' - December 31, '+yearVal[yearVal.length-1]);
            //echo 'January 1, '.current(yearVal).' - December 31'.', '.end(yearVal);
          }
          
        } 
        else if(filter_type == 3) {
          $('.setYear').html(monthArr[currentMonth]+', '+yearVal);
          //echo monthArr[ltrim(date('m'), '0')].', '.CommaYear;
        }
        else if(filter_type == 4) {
          $('.setYear').html(monthArr[currentMonth-1]+', '+yearVal);
        }
        else if(filter_type == 5 || filter_type == 6) {
          if(monthArr[currentMonth] == 1 || monthArr[currentMonth] == 2 || monthArr[currentMonth] == 3) {
                  if(filter_type == 5) {
                    $('.setYear').html('January 1 - March 31, '+yearVal);
                  }
                  else {
                    $('.setYear').html('October 1 - December 31, '+yearVal);
                  }
              }
              else if(monthArr[currentMonth] == 4 || monthArr[currentMonth] == 5 || monthArr[currentMonth] == 6) {
                 if(filter_type == 5) {
                  $('.setYear').html('April 1 - June 30, '+yearVal);
                 }
                 else {
                   $('.setYear').html('January 1 - March 31, '+yearVal);
                 }
              }
              else if(monthArr[currentMonth] == 7 || monthArr[currentMonth] == 8 || monthArr[currentMonth] == 9) {
                 if(filter_type == 5) {
                  $('.setYear').html('July 1 - September 30, '+yearVal);
                 }
                 else {
                  $('.setYear').html('April 1 - June 30, '+yearVal);
                 }
              }
              else if(monthArr[currentMonth] == 10 || monthArr[currentMonth] == 11 || monthArr[currentMonth] == 12) {
                 if(filter_type == 5) {
                  $('.setYear').html('October 1 - December 31, '+yearVal);
                 }
                 else {
                  $('.setYear').html('July 1 - September 30, '+yearVal);
                 }
              }     
          }
        else if(filter_type == 7 || filter_type == 8) {
          $('.setYear').html('January 1 - December 31, '+yearVal);
        }
        else if(filter_type == 9) {
          fDate = from_date.split('-');
          tDate = to_date.split('-');
          
          woFdate = 1;
          woTdate = 12;
          if(fDate != '') {
            woFdate = fDate[1].replace(/^0+/, '');
          }
          if(tDate != '') {
            woTdate = tDate[1].replace(/^0+/, '');
          }
          $('.setYear').html(monthArr[woFdate]+' '+fDate[2]+', '+yearVal[0]+' - '+monthArr[woTdate]+' '+tDate[2]+', '+yearVal);
          //echo monthArr[ltrim(fDate[1], '0')].' '.fDate[0].', '.CommaYear.' - '.monthArr[ltrim(tDate[1], '0')].' '.tDate[0].', '.CommaYear;

        }

      $.ajax({
        url:'<?php echo e(route("monthly_collection_report")); ?>',
        type:'POST',
        data:{
          year:checkedVal,
          month:month,
          date_val:dateVal,
          filter_type:filter_type,
          from_date:from_date,
          to_date:to_date,
          compare_with:compare_with,
          checked_period:checked_period
        },
        success: function(response) {
          //var obj = JSON.parse(response);
          //var obj = $.parseJSON(response);
          $('.monthly_collection_data').html(response);
        }
      });

      var check_array = [];
      $.each($("input[name='year']:checked"), function(){            
        check_array.push($(this).val());
      });
      check_array.reverse();
      revCheckedVal = check_array.join(",");

      $.ajax({
        url:'<?php echo e(route("gross_net_collection_report")); ?>',
        type:'POST',
        data:{
          year:revCheckedVal,
          month:month,
          date_val:dateVal,
          filter_type:filter_type,
          from_date:from_date,
          to_date:to_date,
          compare_with:compare_with,
          checked_period:checked_period
        },

        success: function(response) {
          //var obj = $.parseJSON(response);
          $('.gross_net_collection').html(response);
        }
      });


      $.ajax({
        url:'<?php echo e(route("operational_expenses_report")); ?>',
        type:'POST',
        data:{
          year:checkedVal,
          month:month,
          date_val:dateVal,
          filter_type:filter_type,
          from_date:from_date,
          to_date:to_date,
          compare_with:compare_with,
          checked_period:checked_period
        },
        success: function(response) {
          $('.operationasl_expenses').html(response);
          
        }
      });

      $.ajax({
      url:'<?php echo e(route("get_monthly_collection")); ?>',
      type:'POST',
      data:{
        year:checkedVal,
        month:month,
        date_val:dateVal,
        filter_type:filter_type,
        from_date:from_date,
        to_date:to_date,
        compare_with:compare_with,
        checked_period:checked_period
      },
      success: function(response) {
        //var obj = JSON.parse(response);
        $('#fullPage').css('display', 'block');

        var obj = $.parseJSON(response);
        dataset = [];
        $.each( obj, function( key, value ) {        
          dataset[key] = {'label':value.year, 'backgroundColor':'transparent', 'borderColor':value.color,'borderWidth':0,'data':value.data};
        });
        //console.log(dataset);
        getLineData(dataset);

        $('#fullPage').css('display', 'none');
      }
    });

    $.ajax({
      url:'<?php echo e(route("get_net_collection")); ?>',
      type:'POST',
      data:{
        year:checkedVal,
        month:month,
        date_val:dateVal,
        filter_type:filter_type,
        from_date:from_date,
        to_date:to_date,
        compare_with:compare_with,
        checked_period:checked_period
      },

      success: function(response) {
        $('#fullPage').css('display', 'block');
        var obj = $.parseJSON(response);

        dataset = []; dataLabel = [];
        $.each( obj, function( key, value ) {             
          dataset[0]   = {type: 'bar','label': 'Net Collection', 'backgroundColor':['#8B0000', '#8B0000', '#8B0000', '#8B0000'], 'borderColor':['#8B0000', '#8B0000', '#8B0000', '#8B0000'],'borderWidth':0, 'pointHoverBackgroundColor':['#8B0000', '#8B0000', '#8B0000', '#8B0000'],'data':value.overallNet};
        });

        $.each( obj, function( key, value ) {             
          dataset[1]   =  {type: 'bar','label': 'Net Operating Income', 'backgroundColor':['#33A933','#33A933','#33A933','#33A933'], 'borderColor':['#33A933','#33A933','#33A933','#33A933'],'borderWidth':0, 'pointHoverBackgroundColor':['#33A933','#33A933','#33A933','#33A933'], 'data':value.overallIncome};
        });
        //console.log(dataset);
        getNetCollection(dataset, obj[0]['year']);
        $('#fullPage').css('display', 'none');
      }
    });

    $.ajax({
      url:'<?php echo e(route("get_operational_expenses")); ?>',
      type:'POST',
      data:{
        year:checkedVal,
        month:month,
        date_val:dateVal,
        filter_type:filter_type,
        from_date:from_date,
        to_date:to_date,
        compare_with:compare_with,
        checked_period:checked_period
      },
      success: function(response) {
        $('#fullPage').css('display', 'block');
        var data = $.parseJSON(response);

        dataset  = []; var i=0;
        //console.log(data.optimum);
        $.each( data.year, function( key, value ) {
          dataset[key] = {type: 'bar','label': value, 'backgroundColor':data.color[value], 'borderColor':data.color[value],'borderWidth':0, 'pointHoverBackgroundColor':data.color[value],'data':data.data[value]};
          i++;
        });
        //console.log('optimum',data.year[data.year.length-1]);
        //For optimum
        dataset[i] = {type: 'bar','label': 'Optimum', 'backgroundColor': "#F4B400", 'borderColor': "#F4B400" ,'borderWidth':0, 'pointHoverBackgroundColor' : "#F4B400", 'data': data.optimum[data.year[data.year.length-1]]};

        getOpExpenses(dataset, data.label);
        $('#fullPage').css('display', 'none');
      }
    });

    $.ajax({
      url:'<?php echo e(route("get_employee_costs_expenses")); ?>',
      type:'POST',
      data:{
        year:checkedVal,
        month:month,
        date_val:dateVal,
        filter_type:filter_type,
        from_date:from_date,
        to_date:to_date,
        compare_with:compare_with,
        checked_period:checked_period
      },
      success: function(response) {
        $('#fullPage').css('display', 'block');
        var data = $.parseJSON(response);

        dataset  = []; var i=0;
        $.each( data.year, function( key, value ) {
          dataset[i] = {type: 'bar','label': value, 'backgroundColor':data.color[value], 'borderColor':data.color[value],'borderWidth':1, 'pointHoverBackgroundColor':data.color[value],'data':data.data[value]};
          i++;
        });
        //For optimum
        dataset[i] = {type: 'bar','label': 'Optimum', 'backgroundColor': "#F4B400", 'borderColor': "#F4B400" ,'borderWidth':1, 'pointHoverBackgroundColor' : "#F4B400", 'data': data.optimum[data.year[data.year.length-1]]};

        getEmployeeCostsExpenses(dataset, data.label);
        $('#fullPage').css('display', 'none');
      }
    });
  }


    function getLineData(dataset) {
    $('#cnvMonthlyCol').remove();
    $('#cns_parent').append('<canvas id="cnvMonthlyCol"><canvas>');

    var options = {
      type: 'line',
      responsive: true,
      maintainAspectRatio:false,
      data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: dataset,
      },
      options: {
        elements: {
          line: {
            tension: 0.000001
          }
        },
        title: {
          display: false,
          text: 'Gross vs Net'
        },
        legend: {
          display: true,
          position: "top"
        },
        tooltips: {
          mode: 'index',
          intersect: true
        },
        responsive: true,
        maintainAspectRatio:false,
        scales: {
          xAxes: [{
            stacked: false,
            gridLines: {
              display: false,
              drawBorder: false
            }
          }],
          yAxes: [{
            ticks: {
              callback: function (label, index, labels) {
                return "$ " + label/1000 + 'K';
              }
            }
            ,
            stacked: false

          }]
          /*yAxes: [{
            ticks: {
          reverse: false
            }
          }]*/
        },
      }
    }
    var ctx = document.getElementById('cnvMonthlyCol').getContext('2d');
    new Chart(ctx, options);
  }

  $('#download_monthly_chart').click(function() {
    var canvas = document.getElementById('cnvMonthlyCol').getContext('2d');
    canvas.toBlob(function(blob) {
      saveAs(blob, "pretty image.png");
    });
  });

  function getNetCollection(dataset, dataLabel) {
    $('#cnvGrossNet').remove();
    $('#bar_parent').append('<canvas id="cnvGrossNet"><canvas>');

    var options = {
      type: 'bar',
      data:{
        labels: dataLabel,
        datasets: dataset,
      },
      options: {
        elements: {
          line: {
            tension: 0.000001
          }
        },
        title: {
          display: false,
          text: 'Gross vs Net'
        },
        legend: {
          display: true,
          position: "top"
        },
        tooltips: {
          mode: 'index',
          intersect: true
        },
        responsive: true,
        maintainAspectRatio:false,
        scales: {
          xAxes: [{
            stacked: false,
            gridLines: {
              display: false,
              drawBorder: false
            }
          }],
          yAxes: [{
            ticks: {
              /*callback: function (label, index, labels) {
                return "$ " + label/1000 + 'K';
              }*/
              beginAtZero: true,
              userCallback: function(label, index, labels) {
                 //when the floored value is the same as the value we have a whole number

                 if (Math.floor(label) === label) {
                    //return label;
                    return "$ " + label/1000 + 'K';
                 }
              },
            },
            stacked: false

          }]
        }
      }
    }

    var ctx = document.getElementById('cnvGrossNet').getContext('2d');
    new Chart(ctx, options);
  }

  function getOpExpenses(dataset, dataLabel) {
    $('#cnvOprExp').remove();
    $('#analysis_parent').append('<canvas id="cnvOprExp"><canvas>');

    var options = {
      type: 'bar',
      data:{
        labels: [ 'Dental Supplies', 'Lab Fees', 'Marketing', 'Occupancy', 'Employee Costs', 'General & Admin', 'Net Operating income'], 
        datasets: dataset,

      },//'Fees Collected','Depreciation Expense', 'Amortization Expense', 'Interest Expense', 'Officer & Family Wages', 'Gain or Loss on Sales', 'Miscellaneous Income'
      options: {
        elements: {
          line: {
            tension: 0.000001
          }
          ,
          point: {
            pointStyle: 'line'
          }

        },
        title: {
          display: false,
          text: 'Gross vs Net'
        },
        legend: {
          display: true,
          position: "top",
            // reverse: true
        },
        tooltips: {
          mode: 'index',
          intersect: true
        },
        responsive: true,
        maintainAspectRatio:false,
        scales: {
          xAxes: [{
            stacked: false,
            gridLines: {
              display: false,
              drawBorder: false
            },
            ticks: {
              beginAtZero: true,
            }

          }],
          yAxes: [{
            ticks: {
              callback: function (label, index, labels) {

                return label + '%';
              },
              beginAtZero: true,
            }
            ,
            stacked: false

          }]
        }
      }
    }

    var ctx = document.getElementById('cnvOprExp').getContext('2d');
    new Chart(ctx, options);
  }

  function getEmployeeCostsExpenses(dataset, dataLabel) {
    $('#cnvEmpCostExp').remove();
    $('#emp_cost_parent').append('<canvas id="cnvEmpCostExp"><canvas>');

    var options = {
      type: 'bar',
      data:{
        labels: [ 'Associate Wages', 'Assistant Wages', 'Hygienist Wages', 'Clerical Salaries', 'Taxes & Benefits'], 
        datasets: dataset,
      },
      options: {
        elements: {
          line: {
            tension: 0.000001
          }
          ,
          point: {
            pointStyle: 'line'
          }

        },
        title: {
          display: false,
          text: 'Gross vs Net'
        },
        legend: {
          display: true,
          position: "top",
            // reverse: true
        },
        tooltips: {
          mode: 'index',
          intersect: true
        },
        responsive: true,
        maintainAspectRatio:false,
        scales: {
          xAxes: [{
            stacked: false,
            gridLines: {
              display: false,
              drawBorder: false
            },
            ticks: {
              beginAtZero: true,
            }

          }],
          yAxes: [{
            ticks: {
              callback: function (label, index, labels) {

                return label + '%';
              },
              beginAtZero: true,
            }
            ,
            stacked: false

          }]
        }
      }
    }

    var ctx = document.getElementById('cnvEmpCostExp').getContext('2d');
    new Chart(ctx, options);
  }

  function generatePdf() {
    var checked_arry = [];
    $.each($("input[name='year']:checked"), function(){            
      checked_arry.push($(this).val());
    });

    checkedVal = checked_arry.join(",");

    dateVal     = $('#date_val').val();
    filter_type = $('#filter_type').val();
    from_date = $('#from_date').val();
    to_date = $('#to_date').val();

    if(filter_type == 1)
      month = 0;
    else
      month = $('#month').val();

    $.ajax({
      url:'<?php echo e(route("generate_pdf")); ?>',
      type:'POST',
      data:{
        year:checkedVal,
        month:month,
        date_val:dateVal,
        filter_type:filter_type,
        from_date:from_date,
        to_date:to_date,
      },
      success: function(response) {
        window.location.href= response.redirect;
      }
    });
  }


  function saveAsPDF() { 
    $('#fullPage').css('display', 'block');
    $('#fullPage').css('visibility', 'unset');
    strawberry.loader.showFull();
   
    var HTML_Width          = $("#fullPage").width();
    var HTML_Height         = $("#fullPage").height();
    var top_left_margin     = 15;
    var PDF_Width           = HTML_Width+(top_left_margin*2);
    var PDF_Height          = (PDF_Width*1.5)+(top_left_margin*2);
    var canvas_image_width  = HTML_Width;
    var canvas_image_height = HTML_Height;
    var totalPDFPages       = Math.ceil(HTML_Height/PDF_Height)-1;  

    var tarketContent = document.getElementById("fullPage");
      html2canvas($("#fullPage")[0]).then(function(canvas) {
      var imgData = canvas.toDataURL("image/jpeg", 1.0);

      /*var imgWidth = 210; 
      var pageHeight = 296;  
      var imgHeight = canvas.height * imgWidth / canvas.width;
      var heightLeft = imgHeight;
      var doc = new jsPDF('p', 'mm');
      var position = 0;

      doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
      heightLeft -= pageHeight;

      while (heightLeft >= 0) {
        position = heightLeft - imgHeight;
        doc.addPage();
        doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;
      }
      var companyName = $('#company_name').val();
      doc.save(companyName+".pdf");*/

      var pdf    = new jsPDF("p", "pt",[PDF_Width, PDF_Height]);
        pdf.addImage(imgData, 'JPG', 20, 0, canvas_image_width, canvas_image_height);
        
        console.log('PDF_Width  new jsPDF == (HTML_Width+(top_left_margin*2)) =>', PDF_Width);
        console.log('PDF_Height (PDF_Width*1.5)+(top_left_margin*2) =>', PDF_Height);
        console.log('canvas_image_width pdf.addImage == $("#fullPage").width() =>', canvas_image_width);
        console.log('canvas_image_height $("#fullPage").height() =>', canvas_image_height);
        console.log('totalPDFPages Math.ceil(canvas_image_height/($("#fullPage").width()+(top_left_margin*2)*1.5)+(top_left_margin*2))-1 =>', totalPDFPages);

        for (var i = 1; i <= totalPDFPages; i++) {
          console.log('INSIDE totalPDFPages PDF_Width =>', PDF_Width);
          console.log('INSIDE totalPDFPages PDF_Height =>', PDF_Height);
          console.log('right margin =>', -(PDF_Height*i)+(top_left_margin*4));
          pdf.addPage(PDF_Width, PDF_Height);
          pdf.addImage(imgData, 'JPG', 20, -(PDF_Height*i),canvas_image_width,canvas_image_height);
        }

      var companyName = $('#company_name').val();
      pdf.save(companyName+".pdf");

    });

    $('#fullPage').css('visibility', 'hidden');
    var l = window.location;
    var WEBURL = l.protocol + "//" + l.host ;

    setTimeout(function(){ 
      strawberry.loader.hideFull(); 
     }, 3000); 
  }

  function verifyReport() {
    strawberry.dialog.confirm({
      title: 'Verify Report',
      body : 'Are you sure you want to change report status to verified?',
      yes  : confirmReport,
    })
  }

  function confirmReport() {
    $.ajax({
      url:'<?php echo e(route("verify_report")); ?>',
      type:'POST',
      data:{
      },
      success: function(response) {
        window.location.reload();
      }
    });
  }


 </script>

 <style type="text/css">
  .table_sm td,.table_sm th{padding:.3rem}

  .verify-btn {
    background-color: green; /* Blue background */
    border: none; /* Remove borders */
    color: white; /* White text */
    padding: 12px 16px; /* Some padding */
    font-size: 16px; /* Set a font size */
    cursor: pointer; /* Mouse pointer on hover */
  }

  /* Darker background on mouse-over */
  .verify-btn:hover {
    /*background-color: #9FC131FF;*/
  }

  .badge {
    font-size: 0.9375rem;
    line-height: 1.2;
    vertical-align: middle;
    font-weight: 400;
  }

  .badge-pill {
      border-radius: 10rem;
  }
 </style>

 <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>