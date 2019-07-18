<div class="row">
  <div class="col-12 col-md-6">

  	<?php 
  	$monthArr    = [ '1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'];?>
    <div class="card-body">
    	<h2 class="my-0 text-muted" style="text-align: center">{{ \Session::get('company')['global_company_name'] }}</h2>
    	<h3 class="my-0 text-muted" style="text-align: center">
    	<?php
    	if($filterType == 1) {
    		
    		$yearVal = explode(',', $CommaYear);
    		if (in_array(date("Y"), $yearVal)) {

    			echo 'January 1, '.current($yearVal).' - '.$monthArr[ltrim(date('m'), '0')].' '.date("d").', '.date("Y");
    		} 
    		else {
    			echo 'January 1, '.current($yearVal).' - December 31'.', '.end($yearVal);
    		}
    		
    	} 
    	else if($filterType == 3) {
    		echo $monthArr[ltrim(date('m'), '0')].', '.$CommaYear;
    	}
    	else if($filterType == 4) {
    		echo $monthArr[ltrim(date('m')-1, '0')].', '.$CommaYear;
    	}
    	else if($filterType == 5 || $filterType == 6) {
    		if(ltrim(date('m'), '0') == 1 || ltrim(date('m'), '0') == 2 || ltrim(date('m'), '0') == 3) {
                if($filterType == 5) {
                	echo  'January 1 - March 31, '.$CommaYear;
                }
                else {
                	echo  'October 1 - December 31, '.$CommaYear;
                }
            }
            else if(ltrim(date('m'), '0') == 4 || ltrim(date('m'), '0') == 5 || ltrim(date('m'), '0') == 6) {
               if($filterType == 5) {
                echo  'April 1 - June 30, '.$CommaYear;
               }
               else {
               	 echo  'January 1 - March 31, '.$CommaYear;
               }
            }
            else if(ltrim(date('m'), '0') == 7 || ltrim(date('m'), '0') == 8 || ltrim(date('m'), '0') == 9) {
               if($filterType == 5) {
                echo  'July 1 - September 30, '.$CommaYear;
               }
               else {
               	echo  'April 1 - June 30, '.$CommaYear;
               }
            }
            else if(ltrim(date('m'), '0') == 10 || ltrim(date('m'), '0') == 11 || ltrim(date('m'), '0') == 12) {
               if($filterType == 5) {
                echo  'October 1 - December 31, '.$CommaYear;
               }
               else {
               	echo  'July 1 - September 30, '.$CommaYear;
               }
            }    	
        }
    	else if($filterType == 7 || $filterType == 8) {
    		echo 'January 1 - '.'December 31, '.$CommaYear;
    	}
    	else if($filterType == 9) {
    		$fDate = explode('-',$fromDate);
    		$tDate = explode('-',$toDate);
    		echo $monthArr[ltrim($fDate[1], '0')].' '.$fDate[0].', '.$CommaYear.' - '.$monthArr[ltrim($tDate[1], '0')].' '.$tDate[0].', '.$CommaYear;

    	}

    	//$month = ltrim(date('m'), '0');

    	?>
    	</h3>
	  <h2 class="gradient-title mb-4"><u>Monthly Collection Trends</u></h2>
	  <table class="table table-sm" data-column-right=".2.3.4.5.6.7">
	    <thead class="bg-light">
	      <tr>
	        <th class="border-top-0" style="text-align: left;">Month</th>

	        @foreach($fetchData[0]['year'] as $yearKey => $yearVal)        
	          <th class="border-top-0">{{ $yearVal }}</th>
	        @endforeach
	      </tr>
	    </thead>
	    <tbody>
	      @foreach($fetchData as $monthKey => $monthVal)
	        @if($monthKey != 'tatalSum') 
	          <tr>
	            <td style="text-align: left;">{{ $monthKey }}</td>
	            @foreach($monthVal as $yearKey => $yearVal)
	              <td>$ {{  number_format($yearVal,2) }}</td>
	            @endforeach  
	          </tr>
	        @endif
	      @endforeach
	    </tbody>
	    <tfoot>
	      <tr>
	        <th class="border-secondary" style="text-align: left;">Totals</th>
	        @foreach($fetchData['tatalSum'] as $sKey => $sVal)
	            <th class="border-secondary"> $ {{ number_format($sVal,2) }}</th>
	        @endforeach
	      </tr>
	    </tfoot>
	  </table> 
	</div>
  </div>

  <div class="col-12 col-md-6 gross_net_collection">
  	<div class="card border-0 shadow-sm mb-4">
	  <div class="card-body">
	    <h2 class="gradient-title mb-4"><u>Gross Collection VS Net</u></h2>

	    <table class="table table-sm" data-column-right=".2.3.4." style="height: 80%">
	      <thead class="bg-light">
	        <tr>
	          <th class="border-top-0" style="text-align: left;">Year</th>
	          <th class="border-top-0">Gross</th>
	          <th class="border-top-0">Net Op. Inc.</th>
	        </tr>
	      </thead>
	     
	     <?php $previousValue = null;$previousOverallNet= null;$previousOverallIncome= null;?>
	      @foreach($netBarChartData['year'] as $yearKey => $yearValue)
	      
	        <tbody>
	          @if($previousValue)
	            <tr>
	              <td style="text-align: left;">{{ $previousValue }}</td>
	              <td>$ {{ $previousOverallNet }}</td>
	              <td>$ {{ $previousOverallIncome }}</td>
	            </tr>
	          @endif
	          <tr>
	            <td style="text-align: left;">{{ $netBarChartData['year'][$yearKey] }}</td>
	            <td>$ {{ number_format($netBarChartData['overallNet'][$yearKey],2) }}</td>
	            <td>$ {{ number_format($netBarChartData['overallIncome'][$yearKey],2) }}</td>
	          </tr>
	          
	          @if($yearKey != 0)
	            @php($previousValue = $netBarChartData['year'][$yearKey])
	            @php($previousOverallNet = number_format($netBarChartData['overallNet'][$yearKey],2))
	            @php($previousOverallIncome = number_format($netBarChartData['overallIncome'][$yearKey],2))
	            <tr>
	              <?php 
	                $aa = isset($netBarChartData['overallNet'][$yearKey]) ? $netBarChartData['overallNet'][$yearKey] : 0;
	                $bb = isset($netBarChartData['overallNet'][$yearKey-1]) ? $netBarChartData['overallNet'][$yearKey-1] : 0;
	                $overAllNetInc = bcsub($aa, $bb, 2); 

	                $cc = isset($netBarChartData['overallIncome'][$yearKey]) ? $netBarChartData['overallIncome'][$yearKey] : 0;
	                $dd = isset($netBarChartData['overallIncome'][$yearKey-1]) ? $netBarChartData['overallIncome'][$yearKey-1] : 0;

	                $overAllIncomeInc = bcsub($cc, $dd, 2); 


	                if($aa != 0) 
	                  $overAllNetPercent = ($overAllNetInc/$aa)*100;
	                else
	                  $overAllNetPercent = 0;

	                if($cc != 0) 
	                  $overAllNetPercent = ($overAllIncomeInc/$cc)*100;
	                else
	                  $overAllNetPercent = 0;
	                //print_r( $overAllNetInc);
	              ?>
	              <th class="border-secondary" style="text-align: left;">$ Increase</th>
	              <td class="border-secondary"> $ {{ number_format($overAllNetInc, 2) }}</td>
	              <td class="border-secondary">$ {{ number_format($overAllIncomeInc, 2) }}</td>
	            </tr>
	            <tr>
	              <th class="border-secondary" style="text-align: left;">% Increase</th>
	              <td class="border-secondary"> {{ number_format($overAllNetPercent, 2) }} %</td>
	              <td class="border-secondary"> {{ number_format($overAllNetPercent, 2) }} %</td>
	            </tr>
	          @endif
	       </tbody>

	      @endforeach
	    </table>
	  </div>
	</div>
  </div>

  <div class="col-12 operationasl_expenses">
  	<div class="card border-0 shadow-sm mb-4">
	  <div class="card-body">
	    <h2 class="gradient-title mb-4"><u>Operating Expenses</u></h2>

	    <table class="table table-sm">
	      <thead class="bg-light">
	        <tr data-column-center=".2.3.4.5.6.7.8">
	          <th class="border-top-0 align-middle" rowspan="2" style="text-align: left;">Expenses</th>
	          @foreach($categoryBarData['year'] as $yearKey => $yearData)
	            <th class="border-top-0 border-right pad-center" colspan="2" style="padding-right: 55px">{{ $yearData }}</th>
	          @endforeach
	          <th class="border-top-0" colspan="2" style="padding-right: 55px">Optimum {{ $categoryBarData['YrSelect'] }}</th>

	        </tr>

	        <tr data-column-center=".1.2.3.4.5.6.7.8.9.10">
	          @foreach($categoryBarData['year'] as $yearKey => $yearData)
	          <th>$</th>
	          <th >%</th>
	          @endforeach
	          <th>$</th>
	          <th>%</th>
	        </tr>
	      </thead>
	      <tbody data-column-right=".2.3.4.5.6.7.8.9.10.11.12">
	       
	        @foreach($categoryBarData['label'] as $cKey => $cValue)
	          
	            @if($cValue != 'Depreciation Expense' && $cValue != 'Amortization Expense' && $cValue != 'Interest Expense' && $cValue != 'Officer & Family Wages' &&  $cValue != 'Net Operating Income')
	            <tr>
	                <td style="text-align: left;">{{ $cValue }}</td>
	            
	              @foreach($categoryBarData['data'][$cKey] as $cdKey => $cdValue)
	                
	                <td>${{ number_format((float)$cdValue['totalVal'],2) }}</td>
	                <td >{{ number_format((float)$cdValue['percentage'],2) }}%</td>
	              @endforeach
	            
	                <td>{{ isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal']) ? number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal'],2) : 0 }}</td>
	                <td >{{ isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage']) ? number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage'],2) : 0 }}%</td>
	            </tr>
	            @endif
	          
	        @endforeach

          <tr>
              <th style="text-align: left;">Total Operating Expenses</th>
              @foreach($categoryBarData['totalSumCat'] as $cdKey => $cdValue)
                
              <th> ${{ number_format((float)$cdValue['amount'],2) }} </th>
              <th > {{ number_format((float)$cdValue['percentage'],2) }} %</th>
              @endforeach

              <th>${{ isset($categoryBarData['totalOptimum'][$categoryBarData['YrSelect']]['amount']) ? number_format((float)$categoryBarData['totalOptimum'][$categoryBarData['YrSelect']]['amount'],2) : 0}}</th>
              <th >{{ isset($categoryBarData['totalOptimum'][$categoryBarData['YrSelect']]['percentage']) ? number_format((float)$categoryBarData['totalOptimum'][$categoryBarData['YrSelect']]['percentage'],2) : 0}}%</th>
          </tr>

	      
	        @foreach($categoryBarData['label'] as $cKey => $cValue)
	          
	            @if($cValue == 'Net Operating Income')
	            <tr>
	                <td style="background-color: lightgreen;text-align: left">{{ $cValue }}</td>
	            
	              @foreach($categoryBarData['data'][$cKey] as $cdKey => $cdValue)
	                
	                <td style="background-color: lightgreen">${{ number_format((float)$cdValue['totalVal'],2) }}</td>
	                <td  style="background-color: lightgreen">{{ number_format((float)$cdValue['percentage'],2) }}%</td>
	              @endforeach
	            
	                <td style="background-color: lightgreen">{{ isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal']) ?  number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal'],2) : 0 }}</td>
	                <td  style="background-color: lightgreen">{{ isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage']) ? number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage'],2) : 0}}%</td>
	            </tr>
	            @endif
	          
	        @endforeach

	        <tr>
	          <td colspan="20" class="bg-light" style="text-align: left">Non-Operating Expenses</td>
	        </tr>

	        @foreach($categoryBarData['label'] as $cKey => $cValue)
	          
	            @if($cValue == 'Depreciation Expense' || $cValue == 'Amortization Expense' || $cValue == 'Interest Expense' || $cValue == 'Officer & Family Wages')
	            <tr>
	                <td style="text-align: left;">{{ $cValue }}</td>
	            
	              @foreach($categoryBarData['data'][$cKey] as $cdKey => $cdValue)
	                
	                <td>${{ number_format((float)$cdValue['totalVal'],2) }}</td>
	                <td >{{ number_format((float)$cdValue['percentage'],2) }}%</td>
	              @endforeach
	            
	                <td>{{ isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal']) ? number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal'],2) : 0 }}</td>
	                <td >{{ isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage']) ? number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage'],2) : 0 }}%</td>
	            </tr>
	            @endif
	          
	        @endforeach

	      </tbody>

	    </table>
	  </div>
	</div>

  </div>
</div>

<style type="text/css">
tr {
	border: 1px solid;
}

td, th {
	text-align: right;
}

.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #373a3c;
    font-family: 'Ubuntu', 'Segoe UI', sans-serif;
    font-size: .8rem;

}

table {
    border-collapse: collapse;
}

*, *::before, *::after {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}

table {
    display: table;
    border-collapse: separate;
    border-spacing: 2px;
    border-color: grey;
}

thead {
    display: table-header-group;
    vertical-align: middle;
    border-color: inherit;
}

tbody {
    display: table-row-group;
    vertical-align: middle;
    border-color: inherit;
}

tr {
    display: table-row;
    vertical-align: inherit;
    border-color: inherit;
}

.table-sm th, .table-sm td {
    padding: 0.3rem;
}

.table th, .table td {
    padding: 0.40rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}

td {
    display: table-cell;
    vertical-align: inherit;
}

/*[data-column-right*=".1."] td:nth-child(1), [data-column-right*=".2."] td:nth-child(2), [data-column-right*=".3."] td:nth-child(3), [data-column-right*=".4."] td:nth-child(4), [data-column-right*=".5."] td:nth-child(5), [data-column-right*=".6."] td:nth-child(6), [data-column-right*=".7."] td:nth-child(7), [data-column-right*=".8."] td:nth-child(8), [data-column-right*=".9."] td:nth-child(9), [data-column-right*=".10."] td:nth-child(10), [data-column-right*=".1."] th:nth-child(1), [data-column-right*=".2."] th:nth-child(2), [data-column-right*=".3."] th:nth-child(3), [data-column-right*=".4."] th:nth-child(4), [data-column-right*=".5."] th:nth-child(5), [data-column-right*=".6."] th:nth-child(6), [data-column-right*=".7."] th:nth-child(7), [data-column-right*=".8."] th:nth-child(8), [data-column-right*=".9."] th:nth-child(9), [data-column-right*=".10."] th:nth-child(10), [data-column-right*=".1."] td:nth-child(1) .form-control, [data-column-right*=".2."] td:nth-child(2) .form-control, [data-column-right*=".3."] td:nth-child(3) .form-control, [data-column-right*=".4."] td:nth-child(4) .form-control, [data-column-right*=".5."] td:nth-child(5) .form-control, [data-column-right*=".6."] td:nth-child(6) .form-control, [data-column-right*=".7."] td:nth-child(7) .form-control, [data-column-right*=".8."] td:nth-child(8) .form-control, [data-column-right*=".9."] td:nth-child(9) .form-control, [data-column-right*=".10."] td:nth-child(10) .form-control, [data-column-right*=".1."] th:nth-child(1) .form-control, [data-column-right*=".2."] th:nth-child(2) .form-control, [data-column-right*=".3."] th:nth-child(3) .form-control, [data-column-right*=".4."] th:nth-child(4) .form-control, [data-column-right*=".5."] th:nth-child(5) .form-control, [data-column-right*=".6."] th:nth-child(6) .form-control, [data-column-right*=".7."] th:nth-child(7) .form-control, [data-column-right*=".8."] th:nth-child(8) .form-control, [data-column-right*=".9."] th:nth-child(9) .form-control, [data-column-right*=".10."] th:nth-child(10) .form-control {
  text-align: right;
}

[data-column-center*=".1."] td:nth-child(1), [data-column-center*=".2."] td:nth-child(2), [data-column-center*=".3."] td:nth-child(3), [data-column-center*=".4."] td:nth-child(4), [data-column-center*=".5."] td:nth-child(5), [data-column-center*=".6."] td:nth-child(6), [data-column-center*=".7."] td:nth-child(7), [data-column-center*=".8."] td:nth-child(8), [data-column-center*=".9."] td:nth-child(9), [data-column-center*=".10."] td:nth-child(10), [data-column-center*=".1."] th:nth-child(1), [data-column-center*=".2."] th:nth-child(2), [data-column-center*=".3."] th:nth-child(3), [data-column-center*=".4."] th:nth-child(4), [data-column-center*=".5."] th:nth-child(5), [data-column-center*=".6."] th:nth-child(6), [data-column-center*=".7."] th:nth-child(7), [data-column-center*=".8."] th:nth-child(8), [data-column-center*=".9."] th:nth-child(9), [data-column-center*=".10."] th:nth-child(10), [data-column-center*=".1."] td:nth-child(1) .form-control, [data-column-center*=".2."] td:nth-child(2) .form-control, [data-column-center*=".3."] td:nth-child(3) .form-control, [data-column-center*=".4."] td:nth-child(4) .form-control, [data-column-center*=".5."] td:nth-child(5) .form-control, [data-column-center*=".6."] td:nth-child(6) .form-control, [data-column-center*=".7."] td:nth-child(7) .form-control, [data-column-center*=".8."] td:nth-child(8) .form-control, [data-column-center*=".9."] td:nth-child(9) .form-control, [data-column-center*=".10."] td:nth-child(10) .form-control, [data-column-center*=".1."] th:nth-child(1) .form-control, [data-column-center*=".2."] th:nth-child(2) .form-control, [data-column-center*=".3."] th:nth-child(3) .form-control, [data-column-center*=".4."] th:nth-child(4) .form-control, [data-column-center*=".5."] th:nth-child(5) .form-control, [data-column-center*=".6."] th:nth-child(6) .form-control, [data-column-center*=".7."] th:nth-child(7) .form-control, [data-column-center*=".8."] th:nth-child(8) .form-control, [data-column-center*=".9."] th:nth-child(9) .form-control, [data-column-center*=".10."] th:nth-child(10) .form-control {
  text-align: center;*/
}

@page{ margin: 0;}

.page{
   
    overflow: hidden; 

    page-break-after: always;
}

div {
	overflow:hidden;
}

html {
    height: 0;
}

</style>