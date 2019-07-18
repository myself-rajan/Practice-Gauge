@extends('layouts.app_layout')
@section('content')
<link rel="stylesheet" href="{{ asset('/css/app_modules/dashboard.css') }}">
<div class="dashboard-caption bg-white rounded-pill mb-5 shadow-sm">
  <i class="fas fa-th-large"></i>
  <div class="d-flex w-100">
    <div class="flex-fill">
      <h5 class="mb-0">
        Practice management Dashboard
      </h5>
    </div>
    <div class="flex-fill text-right">
      <div class="dropdown">
        <button class="btn btn-light rounded-pill filter_label" data-toggle="dropdown">As on Dec 31, 2016, 2017, 2018 and
            2019</button>
        <form class="dropdown-menu p-4 dropdown-menu-right rounded shadow formnewdropdown" style="width: 270px;transform: translate3d(471px, 42px, 11px);!important">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
            <input class="form-control form-control-sm" type="date" id="from_date" required>
            </div>        
   
            <div class="form-group">
            <label>To</label>
            <input class="form-control form-control-sm" type="date" id="to_date" required>
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
                      <input type="checkbox" class="custom-control-input" name="year" id="chk{{ date('Y') }}" value="{{ date('Y') }}" checked> 
                      <label class="custom-control-label" for="chk{{ date('Y') }}">{{ date('Y') }}</label>
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
  <div class="col-12 col-xl-6">
    <div class="card shadow-sm rounded card-chart mb-5">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="far fa-calendar-alt"></i>
        Monthly Net Collection Trends</h5>
      </div>
      <div class="card-body">
        <div class="text-right mb-2">
          <div class="dropdown d-inline-block mr-2" data-toggle="tooltip" title="Download">
            <!-- <button class="btn btn-sm btn-chart-right btn-primary" data-toggle="dropdown"><i
                class="fas fa-download"></i></button>
            <div class="dropdown-menu rounded dropdown-menu-right">
              <a class="dropdown-item" id="download_monthly_chart">
                <i class="fas fa-file-pdf mr-1 text-danger" ></i> Download Chart
              </a>
              <a class="dropdown-item">
                <i class="fas fa-file-excel mr-1 text-success"></i> Download Table
              </a>
            </div> -->
          </div>
          <!-- <button class="btn btn-sm btn-chart-right btn-light" data-toggle="tooltip" title="Table View"><i
                class="fas fa-table"></i></button> -->
        </div>

        <div class="position-relative" id="cns_parent" style="height:300px;">
          <canvas id="cnvMonthlyCol" class="chartjs"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-xl-6">
    <div class="card shadow-sm rounded card-chart mb-5">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-balance-scale"></i>
        Net Collection vs Net Operating Income</h5>
      </div>
      <div class="card-body">
        <div class="text-right mb-2">
          <div class="dropdown d-inline-block mr-2" data-toggle="tooltip" title="Download">
            <!-- <button class="btn btn-sm btn-chart-right btn-primary" data-toggle="dropdown"><i
                class="fas fa-download"></i></button>

            <div class="dropdown-menu rounded dropdown-menu-right">
              <a class="dropdown-item">
                <i class="fas fa-file-pdf mr-1 text-danger"></i> Download Chart
              </a>
              <a class="dropdown-item">
                <i class="fas fa-file-excel mr-1 text-success"></i> Download Table
              </a>
            </div> -->
          </div>

          <!-- <button class="btn btn-sm btn-chart-right btn-light" data-toggle="tooltip" title="Table View"><i
            class="fas fa-table"></i></button> -->
        </div>

        <div class="position-relative" id="bar_parent" style="height:300px;">
          <canvas id="cnvGrossNet" class="chartjs"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-xl-12">
    <div class="card shadow-sm rounded card-chart">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-balance-scale"></i>
        Overhead Analysis</h5>
      </div>
      <div class="card-body">
        <div class="text-right mb-2">
          <div class="dropdown d-inline-block mr-2" data-toggle="tooltip" title="Download">
          </div>
        </div>

        <div class="position-relative" id="analysis_parent" style="height:300px;">
          <canvas id="cnvOprExp" class="chartjs"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-50 col-12 col-xl-12" style="margin-top: 50px"> 
    <div class="card shadow-sm rounded card-chart">
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

        <div class="position-relative" id="emp_cost_parent" style="height:300px;">
          <canvas id="cnvEmpCostExp" class="chartjs"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $(document).ready(function() {
    
    window.workspaceScript.onLoad();
    strawberry.dropdownSelectToggler();
    strawberry.dropdownFormBubbler();

    //$( "#from_date" ).datepicker();
    //$( "#to_date" ).datepicker();
    $("#compare_with").removeAttr("checked");

    $('#month').hide();

    var currentYear = (new Date).getFullYear();
    getDashboard(checkedVal = (currentYear-3)+','+(currentYear-2)+','+(currentYear-1)+','+currentYear, month = "0");
  });

  $(".formnewdropdown").submit(function(e) {
    e.preventDefault();
  });

  $('#filter_type').change(function() {
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
    $('.compare_period').hide();
    
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

    getDashboard(checkedVal, month, dateVal, filter_type, from_date, to_date, compare_with, checked_period);
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
      url:'{{route("get_monthly_collection")}}',
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
        var obj = $.parseJSON(response);
        dataset = [];
        $.each( obj, function( key, value ) {        
          dataset[key] = {'label':value.year, 'backgroundColor':'transparent', 'borderColor':value.color,'borderWidth':2,'data':value.data};
        });
        //console.log(dataset);
        getLineData(dataset);
      }
    });

    $.ajax({
      url:'{{route("get_net_collection")}}',
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
      }
    });

    $.ajax({
      url:'{{route("get_operational_expenses")}}',
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

        var data = $.parseJSON(response);

        dataset  = []; var i=0;
        $.each( data.year, function( key, value ) {
          dataset[key] = {type: 'bar','label': value, 'backgroundColor':data.color[value], 'borderColor':data.color[value],'borderWidth':1, 'pointHoverBackgroundColor':data.color[value],'data':data.data[value]};
          i++;
        });
        //For optimum
        dataset[i] = {type: 'bar','label': 'Optimum', 'backgroundColor': "#F4B400", 'borderColor': "#F4B400" ,'borderWidth':1, 'pointHoverBackgroundColor' : "#F4B400", 'data': data.optimum[data.year[data.year.length-1]]};

        getOpExpenses(dataset, data.label);
      }
    });

    $.ajax({
      url:'{{route("get_employee_costs_expenses")}}',
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
        var data = $.parseJSON(response);
        console.log(data);
        console.log(data.year);
        dataset  = []; var i=0;
        $.each( data.year, function( key, value ) {
          dataset[i] = {type: 'bar','label': value, 'backgroundColor':data.color[value], 'borderColor':data.color[value],'borderWidth':1, 'pointHoverBackgroundColor':data.color[value],'data':data.data[value]};
          i++;
        });
        //For optimum
        dataset[i] = {type: 'bar','label': 'Optimum', 'backgroundColor': "#F4B400", 'borderColor': "#F4B400" ,'borderWidth':1, 'pointHoverBackgroundColor' : "#F4B400", 'data': data.optimum[data.year[data.year.length-1]]};
        console.log(dataset);
        getEmployeeCostsExpenses(dataset);
        //getEmployeeCostsExpenses(dataset, obj.year);
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

  function getEmployeeCostsExpenses(dataset) {
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
  </script>

  @endsection
