<table class="table table-sm">
  <thead class="bg-light">
    <tr data-column-center=".2.3.4.5.6.7.8">
      <th class="border-top-0 align-middle" rowspan="2" style="width:40%">Expenses</th>
      @foreach($categoryBarData['year'] as $yearKey => $yearData)
        <th class="border-top-0 border-right" colspan="2">{{ $yearData }}</th>
      @endforeach
      <th class="border-top-0" colspan="2">Optimum {{ $categoryBarData['YrSelect'] }}</th>

    </tr>

    <tr data-column-center=".1.2.3.4.5.6.7.8.9.10">
      @foreach($categoryBarData['year'] as $yearKey => $yearData)
      <th>$</th>
      <th >%</th>
      @endforeach
      <th>$</th>
      <th class="align-right">%</th>
    </tr>
  </thead>
  <tbody data-column-right=".2.3.4.5.6.7.8.9.10.11.12">
    

    @foreach($categoryBarData['label'] as $cKey => $cValue)
      <tr>

          @if($cValue == 'Fees Collected' || $cValue == 'Dental Supplies' || $cValue == 'Lab Fees' || $cValue == 'Marketing' || $cValue == 'Occupancy')

            <td> {{ $cValue }}</td>
          
            @foreach($categoryBarData['data'][$cKey] as $cdKey => $cdValue)
              
              <td>${{ number_format((float)$cdValue['totalVal'],2) }}</td>
              <td >{{ number_format((float)$cdValue['percentage'],2) }}%</td>
            @endforeach
          
              <td>${{ isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal']) ? number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal'],2) : '$0.00' }}</td>
              <td class="text-right">{{ isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage']) ? number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage'],2) : '0.00' }}%</td>

          @endif
       
      </tr>
    @endforeach

      <tr>
        <td>Employee Costs</td>
        @foreach($categoryBarData['totalEmployeeCost'] as $cdKey => $cdValue)
          
        <td> ${{ number_format((float)$cdValue['amount'],2) }} </td>
        <td > {{ number_format((float)$cdValue['percentage'],2) }} %</td>
        @endforeach

        <td>${{ isset($categoryBarData['totalEmployeeCostOptimum'][$categoryBarData['YrSelect']]['amount']) ? number_format((float)$categoryBarData['totalEmployeeCostOptimum'][$categoryBarData['YrSelect']]['amount'],2) : '0.00' }}</td>
        <td class="text-right">{{ isset($categoryBarData['totalEmployeeCostOptimum'][$categoryBarData['YrSelect']]['percentage']) ? number_format((float)$categoryBarData['totalEmployeeCostOptimum'][$categoryBarData['YrSelect']]['percentage'],2) : '0.00' }}%</td>
      </tr>


      @foreach($categoryBarData['label'] as $cKey => $cValue)
        <tr>
          @php($dispLabel = '')
          @if($cValue == 'Associate Wages' || $cValue == 'Assistant Wages' || $cValue == 'Hygienist Wages' || $cValue == 'Clerical Salaries' || $cValue == 'Taxes & Benefits' || $cValue == 'General & Admin')

            @if($cValue == 'General & Admin')
              @php($dispLabel = '')
            @else
              @php($dispLabel = 'padding-left:35px')
            @endif
          
            <td style="{{ $dispLabel }}"> {{ $cValue }}</td>
        
            @foreach($categoryBarData['data'][$cKey] as $cdKey => $cdValue)
              
              <td>${{ number_format((float)$cdValue['totalVal'],2) }}</td>
              <td >{{ number_format((float)$cdValue['percentage'],2) }}%</td>
            @endforeach
          
              <td>${{ isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal']) ? number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal'],2) : '$0.00' }}</td>
              <td class="text-right">{{ isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage']) ? number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage'],2) : '0.00' }}%</td>

          @endif
        </tr>
      @endforeach


      <tr>
        <th>Total Operating Expenses</th>
        @foreach($categoryBarData['totalSumCat'] as $cdKey => $cdValue)
          
        <th> ${{ number_format((float)$cdValue['amount'],2) }} </th>
        <th > {{ number_format((float)$cdValue['percentage'],2) }} %</th>
        @endforeach

        <th>${{ isset($categoryBarData['totalOptimum'][$categoryBarData['YrSelect']]['amount']) ? number_format((float)$categoryBarData['totalOptimum'][$categoryBarData['YrSelect']]['amount'],2) : '0.00' }}</th>
        <th class="text-right">{{ isset($categoryBarData['totalOptimum'][$categoryBarData['YrSelect']]['percentage']) ? number_format((float)$categoryBarData['totalOptimum'][$categoryBarData['YrSelect']]['percentage'],2) : '0.00' }}%</th>
      </tr>

    <!-- <tr>
      <th>Total Operating Expenses</th>
      <th>$104,055</th>
      <th >100%</th>
      <th>$111,377</th>
      <th >100%</th>
      <th>$103,003</th>
      <th>100%</th>
      <th>$104,055</th>
      <th >100%</th>
      <th>$104,055</th>
      <th >100%</th>
    </tr> -->
    @foreach($categoryBarData['label'] as $cKey => $cValue)
      <tr>
        @if($cValue == 'Net Operating Income')
            <td style="background-color: lightgreen">{{ $cValue }}</td>
        
          @foreach($categoryBarData['data'][$cKey] as $cdKey => $cdValue)
            
            <td style="background-color: lightgreen">${{ number_format((float)$cdValue['totalVal'],2) }}</td>
            <td  style="background-color: lightgreen">{{ number_format((float)$cdValue['percentage'],2) }}%</td>
          @endforeach
        
            <td style="background-color: lightgreen">${{ isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal']) ? number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal'],2) : '$0.00' }}</td>
            <td class="text-right"  style="background-color: lightgreen">{{ isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage']) ?  number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage'],2) : '0.00' }}%</td>
        @endif
      </tr>
    @endforeach

    <tr>
      <td colspan="20" class="bg-light">Non-Operating Expenses</td>
    </tr>

    @foreach($categoryBarData['label'] as $cKey => $cValue)
      <tr>
        @if($cValue == 'Depreciation Expense' || $cValue == 'Amortization Expense' || $cValue == 'Interest Expense' || $cValue == 'Officer & Family Wages' || $cValue == 'Gain or Loss on Sales' || $cValue == 'Miscellaneous Income')
            <td>{{ $cValue }}</td>
        
          @foreach($categoryBarData['data'][$cKey] as $cdKey => $cdValue)
            
            <td>${{ number_format((float)$cdValue['totalVal'],2) }}</td>
            <td >{{ number_format((float)$cdValue['percentage'],2) }}%</td>
          @endforeach
        
            <td>${{ isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal']) ?  number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['totalVal'],2) : '$0.00' }}</td>
            <td class="text-right">{{ isset($categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage']) ?  number_format((float)$categoryBarData['optimum'][$cKey][$categoryBarData['YrSelect']]['percentage'],2) : '0.00' }}%</td>
        @endif
      </tr>
    @endforeach

      <tr>
        <td>Net Income</td>
        @foreach($categoryBarData['netIncome'][$cKey] as $cdKey => $cdValue)
        <td>${{ number_format((float)$cdValue['totalVal'],2) }}</td>
        <td>{{ number_format((float)$cdValue['percentage'],2) }}%</td>
        @endforeach
    
        <td>${{ isset($categoryBarData['netIncomeOptimum'][$cKey][$categoryBarData['YrSelect']]['totalVal']) ? number_format((float)$categoryBarData['netIncomeOptimum'][$cKey][$categoryBarData['YrSelect']]['totalVal'],2) : '$0.00' }}</td>
        <td class="text-right">{{ isset($categoryBarData['netIncomeOptimum'][$cKey][$categoryBarData['YrSelect']]['percentage']) ?  number_format((float)$categoryBarData['netIncomeOptimum'][$cKey][$categoryBarData['YrSelect']]['percentage'],2) : '0.00' }}%</td>
      </tr>
  </tbody>
</table>



    