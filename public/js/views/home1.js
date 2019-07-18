window.workspaceScript = new function () {
  _this = this;
  _this.Chart = window.Chart;

  _this.randomScalingFactor = function () {
    return Math.round(window['Samples'].utils.rand(0, 400));
  };

  _this.randomScalingFactorNeg = function () {
    return Math.round(window['Samples'].utils.rand(-200, 0));
  };


  _this.YEARS = ["2015", "2016", "2017", "2018"];
  _this.MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  _this.MONTHS_Short = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  _this.MONTHS_Short = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  _this.MONTHS_Short_Year = [['Jan \'18'], ['Feb \'18'], ['Mar \'18'], ['Apr \'18'], ['May \'18'], ['Jun \'18'], ['Jul \'18'], ['Aug \'18'], ['Sep \'18'], ['Oct \'18'], ['Nov \'18'], ['Dec \'18']];
  _this.Colors = ["#FF9F40", "#36A2EB", "#9AB900", "#4BC0C0", "#FF6384", "#F03337", "#9158ff"];
  //var ColorsTrans = ["#8BAAD155", "#0099e655", "#8CBC4F55", "#01b53055", "#ff606055", "#e2003155", "#2a3aff55"];

  _this.makeTrans = function (color) {
    return color + "88";
  }


  _this.makeTransRGB = function (color) {
    console.log("rgba" + color.substr(3, name.length - 1) + ",0.5)");
    return "rgba" + color.substr(3, name.length - 1) + ",0.5)";
  }

  _this.makeTrans1 = function (color) {
    return color + "33";
  }

  //var color = Chart.helpers.color;

  _this.GvNData = {
    labels: ['2010', '2011', '2012', '2013'],
    datasets: [
      {
        type: 'bar',
        label: 'Gross Collection',
        backgroundColor: _this.Colors[1],
        borderColor: _this.Colors[1],
        pointHoverBackgroundColor: _this.Colors[1],
        borderWidth: 0,
        data: [
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor()
        ]
      }, {
        type: 'bar',
        label: 'Net',
        backgroundColor: _this.Colors[0],
        borderColor: _this.Colors[0],
        borderWidth: 0,
        data: [
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor()
        ]
      }
    ]

  };

  _this.MonthglyData = {
    labels: _this.MONTHS_Short,
    datasets: [
      {
        type: 'line',
        label: '2010',
        backgroundColor: 'transparent',
        borderColor: _this.Colors[4],
        borderWidth: 2,
        data: [
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor()
        ]
      }, {
        type: 'line',
        label: '2011',
        backgroundColor: 'transparent',
        borderColor: _this.Colors[3],
        borderWidth: 2,
        data: [
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor()
        ]
      }
      , {
        type: 'line',
        label: '2012',
        backgroundColor:'transparent',
        borderColor: _this.Colors[6],
        borderWidth: 2,
        data: [
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor()
        ]
      }, {
        type: 'line',
        label: '2013',
        backgroundColor:'transparent',
        borderColor: _this.Colors[2],
        borderWidth: 2,
        data: [
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor()
        ]
      }
    ]

  };

  
  _this.OprExpData = {
    labels: ['Dental Supplies','Lab Fees','Marketting','Occupancy','Employee Costs','Administrative'],
    datasets: [
      {
        type: 'bar',
        label: '2010',
        backgroundColor: _this.makeTrans1(_this.Colors[6]),
        borderColor: _this.Colors[6],
        borderWidth: 1,
        data: [
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor()
        ]
      }, {
        type: 'bar',
        label: '2011',
        backgroundColor: _this.makeTrans1(_this.Colors[5]),
        borderColor: _this.Colors[5],
        borderWidth: 1,
        data: [
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor()
        ]
      }
      , {
        type: 'bar',
        label: '2012',
        backgroundColor:_this.makeTrans1(_this.Colors[2]),
        borderColor: _this.Colors[2],
        borderWidth: 1,
        data: [
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor()
        ]
      }, {
        type: 'bar',
        label: '2013',
        backgroundColor:_this.makeTrans1(_this.Colors[1]),
        borderColor: _this.Colors[1],
        borderWidth: 1,
        data: [
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor(),
          _this.randomScalingFactor()
        ]
      }
    ]

  };

  _this.chartGvN = function () {
    var ctx = document.getElementById('cnvGrossNet').getContext('2d');
    window.chartGvN = new _this.Chart(ctx, {
      type: 'bar',
      data: _this.GvNData,
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
        responsive: false,
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
                return "$ " + label + 'K';
              }
            }
            ,
            stacked: false

          }]
        }
      }
    });
  }


  _this.chartMonthly = function () {
    var ctx = document.getElementById('cnvMonthlyCol').getContext('2d');
    window.chartGvN = new _this.Chart(ctx, {
      type: 'bar',
      data: _this.MonthglyData,
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
        responsive: false,
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
                return "$ " + label + 'K';
              }
            }
            ,
            stacked: false

          }]
        }
      }
    });
  }

  _this.chartOprExp = function () {
    var ctx = document.getElementById('cnvOprExp').getContext('2d');
    window.chartOprExp = new _this.Chart(ctx, {
      type: 'bar',
      data: _this.OprExpData,
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
        responsive: false,
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
                return "$ " + label + 'K';
              }
            }
            ,
            stacked: false

          }]
        }
      }
    });
  }


  _this.initChartJsCanvas = function () {
    $('canvas.chartjs').each(function () {

      if (window.innerWidth > 1600) {

        // if ($(this).hasClass('offset-lg')) {
        //   $(this).attr('height', $(this).parent().innerHeight() - 100);
        // }
        // else {
        //   $(this).attr('height', $(this).parent().innerHeight() -25 );
        // }
        $(this).attr('width', $(this).parent().innerWidth());
      }
      else {
        // if ($(this).hasClass('offset')) {
        //   $(this).attr('height', $(this).parent().innerHeight() - 100);
        // }
        // else {
        //   $(this).attr('height', $(this).parent().innerHeight() - 100);
        // }
        
        
        console.log($(this).innerHeight());
        $(this).attr('width', $(this).parent().innerWidth());
        $(this).attr('height', $(this).innerHeight() - 100);
        
      }
    })
  }

  _this.onLoad = function () {
    _this.initChartJsCanvas();
    _this.chartGvN();
    _this.chartMonthly();
    _this.chartOprExp();
  };
};
