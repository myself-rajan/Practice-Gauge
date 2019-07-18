

    <table class="table table-sm" data-column-right=".2.3.4.">
      <thead class="bg-light">
        <tr>
          <th class="border-top-0">Year</th>
          <th class="border-top-0">Gross</th>
          <th class="border-top-0">Net Op. Inc.</th>
        </tr>
      </thead>
     
     <?php $previousValue = null;$previousOverallNet= null;$previousOverallIncome= null;
     ?>
      @foreach($netBarChartData['year'] as $yearKey => $yearValue)
      
        <tbody>
          @if($previousValue)
            <tr>
              <td>{{ $previousValue }}</td>
              <td>$ {{ $previousOverallNet }}</td>
              <td>$ {{ $previousOverallIncome }}</td>
            </tr>
          @endif
          <tr>
            <?php
              $netVal = isset($netBarChartData['overallNet'][$yearKey]) ? $netBarChartData['overallNet'][$yearKey] : 0;
              $incomelVal = isset($netBarChartData['overallIncome'][$yearKey]) ? $netBarChartData['overallIncome'][$yearKey] : 0;
            ?>
            <td>{{ $netBarChartData['year'][$yearKey] }}</td>
            <td>$ {{ number_format($netVal,2) }}</td>
            <td>$ {{ number_format($incomelVal,2) }}</td>
          </tr>
          
          @if($yearKey != 0)
            @php($previousValue = $netBarChartData['year'][$yearKey])
            @php($previousOverallNet = number_format($netVal,2))
            @php($previousOverallIncome = number_format($incomelVal,2))
            <tr>
              <?php 
                $aa = isset($netBarChartData['overallNet'][$yearKey]) ? $netBarChartData['overallNet'][$yearKey] : 0;
                $bb = isset($netBarChartData['overallNet'][$yearKey-1]) ? $netBarChartData['overallNet'][$yearKey-1] : 0;
                $overAllNetInc = $bb - $aa;//bcsub($aa, $bb, 2); 
              
                
                $cc = isset($netBarChartData['overallIncome'][$yearKey]) ? $netBarChartData['overallIncome'][$yearKey] : 0;
                $dd = isset($netBarChartData['overallIncome'][$yearKey-1]) ? $netBarChartData['overallIncome'][$yearKey-1] : 0;

                $overAllIncomeInc = $dd - $cc;//bcsub($cc, $dd, 2); 


                if($aa != 0) 
                  $overAllNetPercent = ($overAllNetInc/$aa)*100;
                else
                  $overAllNetPercent = 0;


                if($cc != 0) 
                  $overAllIncomePercent = ($overAllIncomeInc/$cc)*100;
                else
                  $overAllIncomePercent = 0;
                //print_r( $overAllNetInc);
              ?>
              <th class="border-secondary">$ Increase</th>
              <td class="border-secondary"> $ {{ number_format($overAllNetInc, 2) }}</td>
              <td class="border-secondary">$ {{ number_format($overAllIncomeInc, 2) }}</td>
            </tr>
            <tr>
              <th class="border-secondary">% Increase</th>
              <td class="border-secondary"> {{ number_format($overAllNetPercent, 2) }} %</td>
              <td class="border-secondary"> {{ number_format($overAllIncomePercent, 2) }} %</td>
            </tr>
          @endif
       </tbody>

      @endforeach
    </table>
