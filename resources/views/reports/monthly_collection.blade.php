
  <table class="table table-sm" data-column-right=".2.3.4.5.">
    <thead class="bg-light">
      <tr>
        <th class="border-top-0">Month</th>

        @foreach($fetchData['year'] as $yearKey => $yearVal)        
          <th class="border-top-0">{{ $yearVal }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach($fetchData as $monthKey => $monthVal)
        @if($monthKey != 'tatalSum' && $monthKey != 'year') 
          <tr>
            <td>{{ $monthKey }}</td>
            @foreach($monthVal as $yearKey => $yearVal)
              <td>$ {{  number_format($yearVal,2) }}</td>
            @endforeach  
          </tr>
        @endif
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <th class="border-secondary">Totals</th>
        @foreach($fetchData['tatalSum'] as $sKey => $sVal)
            <th class="border-secondary"> $ {{ number_format($sVal,2) }}</th>
        @endforeach
      </tr>
    </tfoot>
  </table> 
</div>
