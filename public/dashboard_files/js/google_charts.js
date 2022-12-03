google.charts.load('current', {'packages':['corechart']});

$(function () {
    google.charts.setOnLoadCallback(googleIsLoaded);

    var drawAreaChart = function (json_data,title,ElementId) {

        var arr_data  = [
            ['', 'اﻻرباح']
        ];

        console.log(json_data);

        $.each(json_data,function(i,v){
            arr_data.push([i.toString(),parseFloat(v)]);
        });

        console.log('arr_data',arr_data);

        var data = google.visualization.arrayToDataTable(arr_data);

        var options = {
            title: title,
            vAxis: {minValue: 0},
            pointSize: 10,
        };

        var chart = new google.visualization.AreaChart(document.getElementById(ElementId));
        chart.draw(data, options);
    };

    var drawBarChart = function(json_data,title,ElementId, colors){

        var arr_data  = [
            ["", "العدد"]
        ];

        $.each(json_data,function(i,v){
            arr_data.push([i,parseFloat(v)]);
        });


        var data = google.visualization.arrayToDataTable(arr_data);

        var view = new google.visualization.DataView(data);
        view.setColumns([
            0,
            1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            }
        ]);

        var options = {
            title: title,
            bar: {groupWidth: "25%"},
            legend: { position: "none" },
            colors: ["#605ca8"]
        };
        var chart = new google.visualization.ColumnChart(document.getElementById(ElementId));
        chart.draw(view, options);

    };

    var drawBirChart = function (json_data,title,ElementId) {

        var arr_data  = [
            ["", ""]
        ];

        $.each(json_data,function(i,v){
            arr_data.push([i,parseFloat(v)]);
        });

        var data = google.visualization.arrayToDataTable(arr_data);

        var options = {
            title: title,
            is3D: true
        };

        var chart = new google.visualization.PieChart(document.getElementById(ElementId));

        chart.draw(data, options);

    };

    function googleIsLoaded(){


        if($("#daily_orders_count_chart").length){
            drawBarChart($("#daily_orders_count_chart").data("value"),"عدد الطلبات اليومية","daily_orders_count_chart");
        }

        if($("#monthly_orders_count_chart").length){
            drawBarChart($("#monthly_orders_count_chart").data("value"),"عدد الطلبات الشهرية","monthly_orders_count_chart");
        }

        if($("#yearly_orders_count_chart").length){
            drawBarChart($("#yearly_orders_count_chart").data("value"),"عدد الطلبات السنوية","yearly_orders_count_chart");
        }




    };


});
