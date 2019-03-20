$(document).ready(function () {

    "use strict";


    var window_width = $(window).width(),

        window_height = window.innerHeight,

        header_height = $(".default-header").height(),

        header_height_static = $(".site-header.static").outerHeight(),

        headerB_height = $(".default-header").height(),

        headerB_height_static = $(".site-header.static").outerHeight(),

        fitscreen = window_height - header_height - headerB_height;


    $(".fullscreen").css("height", window_height)

    $(".fitscreen").css("height", fitscreen);


    if (document.getElementById("default-select")) {

        $('select').niceSelect();

    }
    ;




    //  Counter Js


    if (document.getElementById("counter")) {

        $('.counter').counterUp({

            delay: 10,

            time: 1000

        });

    }
    ;


  
    // Nodes

    getBlockDifficulty();

    setInterval(getBlockDifficulty, 180 * 1000);

    function getBlockDifficulty() {

        $.get("../_php/getBlockDifficulty.php", function (data) {

            $('.block-difficulty').html(data);

            $('.block-difficulty').counterUp({

                delay: 10,

                time: 1000

            });

        });

    }


    getBlockCount();

    setInterval(getBlockCount, 180 * 1000);

    function getBlockCount() {

        $.get("../_php/getBlockCount.php", function (data) {

            $('.block-count').html(data);

            $('.block-count').counterUp({

                delay: 10,

                time: 1000

            });

        });

    }


    getTopCountry();

    setInterval(getTopCountry, 120 * 1000);

    function getTopCountry() {

        $.getJSON("../_php/getTopCountry.php", function (data) {

            if (!data['status']) return;


            var str = '';

            for (var i = 0; i < data['item'].length; i++) {

                var item = data['item'][i];

                            str += '<div class="table-row">' + 

                    '       <div class="rank">' +

                    '       <img src="' + item.flag + '" alt="flag" height="34" width="34">' + '\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + item.rank + '</div>' +

                    '       <div class="country"> ' + item.country + '</div>' +

                    '       <div class="nodes">' + item.count + '</div>' +

                    '       <div class="col-md-10 col-lg-6">' +
                    '       <div class="progress-linear">' +
                    '       <div class="progress-header">' +
                    '       <span class="progress-value">' + item.count + '</span>' +
                    '       </div>' +
                    '       <div class="progress-bar-linear-wrap">' +
                    '       <div class="progress-bar-linear">' + '</div>'+
                    '       </div>' +
                    '       </div>' +
                    '       </div>' +
                    '       </div>'


 //                   '       <div class="col-md-10 col-lg-6"><article class="progress-linear"><div class="progress-header"><p class="progress-title">' + item.country + '</p><span class="progress-value">' + item.count + '</span></div><div class="progress-bar-linear-wrap"><div class="progress-bar-linear"></div></div></article></div>' +
                  
 //                   '       </div>'
 
              
                
               
                
                
                  
                
// </div>'





//                  '       <div class="col-md-10 col-lg-6 progress-linear"><div class="progress-header"><p></p><span class="progress-value">' + item.count + '</span></div><div class="progress-bar-linear-wrap"><div class="progress-bar-linear"></div></div></div>' + 

//                  '       </div>' 
                   

//                    '       <div class="progress-linear"><div class="progress-header"><p></p><span class="progress-value">' + item.count + '</span>' + '</div>' +                    

 //                   '   <div class="progress-bar-linear-wrap"><div class="progress-bar-linear"></div></div></div>'


 //                   '       <div class="percentage">' + item.count + '</div>' +                    

 //                   '   </div>'

 //                   '       <div class="percentage"><div class="progress"><div class="percentage progress-bar color-4" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">' + item.count + '%' + '</div>' +                    

 //                   '   </div></div></div>'




            }

            $('.ranking-table > .table-body').html(str);

            if (data['fullNodeCount'] != -1) {

                $('.node-count').html(data['fullNodeCount']);

                $('.node-count').counterUp({

                    delay: 10,

                    time: 1000

                });

            }


            if (data['totalCount'] != -1) {

                $('.connection-count').html(data['totalCount']);

                $('.connection-count').counterUp({

                    delay: 10,

                    time: 1000

                });

            }


        });

    }

    function showNodeStatus(type, info) {
        if (type == 0) {
            $('#check-node-status').html('<div class="alert text-center alert-danger" style="display: block;">\n' +
                '    <p class="fa fa-exclamation-circle"></p>&nbsp;Enter valid IP/Port address.\n' +
                '</div>');
        } else if (type == 1) {
            $('#check-node-status').html('<div class="alert text-center alert-danger" style="display: block;">\n' +
                '    <p class="fa fa-exclamation-circle"></p>&nbsp;' + $('#ip').val() +  ' is unreachable.' +
                '</div>');
        } else if (type ==  2) {
            $('#check-node-status').html('<div class="alert text-center alert-success" style="display: block;">\n' +
                '    <p class="fa fa-check-circle"></p>&nbsp;\n' +
                '    <a title="Node status">' + info.ip_addr + ':' + info.port +  '\xa0\xa0' + info.version + '\xa0\xa0' + 'is reachable.</a>\n' +
                '</div>\n');
        }
    }


    $('#search-node > .custom-btn2').click(function() {
        var ip = $('#ip').val(),
            port = $('#port').val();

        if (ip == '' || port == '') {
            showNodeStatus(0, null);
            return;
        }
        $('#check-node-status').html('');
        $('.loading').show();
        $.post("../_php/getNodeStatus.php",
            { ip: ip, port: port},
            function (data) {
                $('.loading').hide();

                data = $.parseJSON(data);
                if (!data['status']) {
                    showNodeStatus(1, null);
                } else {
                    showNodeStatus(2, data.info);
                }
            }
        );
    });

});

