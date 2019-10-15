<script>
$(document).ready(function(){

    $("#static_modelcount").text($("#table_modellist tr").length-1);
    $("#static_apicount").text($(".api_item .api_active[checked]").length);
    $("#static_versioncount").text($(".version_item").length);

	var json={"action":"getdata"
	           ,"app_id":"{{$appinfo.id}}"};

	getJSON("{{$rootpath}}api/statistics", json, function (data) {

                var tablesdata=new Array();
                $("#static_tablescount").text(data.tables.length);
                for(var i=0;i<data.tables.length;i++){
                    var rows=Number(data.tables[i].TABLE_ROWS);
                    var tabledata={   name:data.tables[i].TABLE_NAME,y:rows };
                    tablesdata.push(tabledata);
                }
                $('#static_table_chart').highcharts({
                    chart: {type: 'column'},
                    title: { text: ''},
                    subtitle: {text: ''},
                    xAxis: {type: 'category'},
                    yAxis: {title: {text: ''}},
                    legend: {
                        enabled: false
                    },

                    series: [{
                        name: '数据空间',
                        colorByPoint: true,
                        data: tablesdata
                    }]
                });



                var spaces=new Array();
                var total_space=0;
                for(var i in data.spaces){
                    var c=Number(data.spaces[i]);
                    total_space+=c;
                    spaces.push([i,c]);
                }

                $('#static_space_chart').highcharts({
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false
                    },
                    title: {
                        text: '使用云空间('+bytesToSize(total_space)+')'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false
                            },
                            showInLegend: true
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: '占用空间',
                        data: spaces
                    }]
                });


                var spaces=new Array();
                var total_space=0;
                for(var i in data.spaces){
                    var c=Number(data.spaces[i]);
                    total_space+=c;
                    spaces.push([i,c]);
                }

                $('#static_space_chart').highcharts({
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false
                    },
                    title: {
                        text: '使用云空间('+bytesToSize(total_space)+')'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false
                            },
                            showInLegend: true
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: '占用空间',
                        data: spaces
                    }]
                });




                  var cats=new Array();
                    var catdata=new Array();
                    for(var i in data.apioutput){
                        cats.push(i);
                        catdata.push(Number(data.apioutput[i]));
                    }
                    $('#static_apioutput_chart').highcharts({
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: ''
                    },
                    xAxis: {
                        type:"datetime",
                        categories: cats
                    },
                    yAxis: {
                        title: {
                            text: '单位：字节'
                        }
                    },
                    tooltip: {
                        valueSuffix: '字节'
                    },
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: false
                            }
                        }
                    },
                    legend:{
                        enabled:false
                    },
                    series: [{
                        name:"统计",
                        data: catdata
                    }]
                });

                    var trstr="";
                for(var i in data.callsummary){
                    trstr+="<tr><td>"+data.callsummary[i].url
                        +"</td><td>"+data.callsummary[i].callcount
                        +"</td><td>"+(Math.ceil(data.callsummary[i].total_data_length/data.callsummary[i].callcount)).toString()
                        +"</td></tr>";
                }
                $("#static_callsummary_trs").append(trstr);
                $("#static_callsummary").DataTable();





            }, function () {




    });




});
        function bytesToSize(bytes) {  
            if (bytes === 0) return '0 B';  
  
            var k = 1024;  
  
            sizes = ['B','KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];  
  
            i = Math.floor(Math.log(bytes) / Math.log(k));  
  
            return (bytes / Math.pow(k, i)).toFixed(2) + ' ' + sizes[i];   
            //toPrecision(3) 后面保留一位小数，如1.0GB                                                                                                                  //return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];  
        } 
</script>