<?php
include("includes/config.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Calandar</title>
    
    <link href='<?=$dir;?>packages/core/main.css' rel='stylesheet' />
    <link href='<?=$dir;?>packages/daygrid/main.css' rel='stylesheet' />
    <link href='<?=$dir;?>packages/timegrid/main.css' rel='stylesheet' />
    <link href='<?=$dir;?>packages/list/main.css' rel='stylesheet' />
    <link href='<?=$dir;?>packages/bootstrap/css/bootstrap.css' rel='stylesheet' />
    <link href="<?=$dir;?>packages/jqueryui/custom-theme/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">
    <link href='<?=$dir;?>packages/datepicker/datepicker.css' rel='stylesheet' />
    <link href='<?=$dir;?>packages/colorpicker/bootstrap-colorpicker.min.css' rel='stylesheet' />
    <link href='<?=$dir;?>style.css' rel='stylesheet' />

    <script src='<?=$dir;?>packages/core/main.js'></script>
    <script src='<?=$dir;?>packages/daygrid/main.js'></script>
    <script src='<?=$dir;?>packages/timegrid/main.js'></script>
    <script src='<?=$dir;?>packages/list/main.js'></script>
    <script src='<?=$dir;?>packages/interaction/main.js'></script>
    <script src='<?=$dir;?>packages/jquery/jquery.js'></script>
    <script src='<?=$dir;?>packages/jqueryui/jqueryui.min.js'></script>
    <script src='<?=$dir;?>packages/bootstrap/js/bootstrap.js'></script>
    <script src='<?=$dir;?>packages/datepicker/datepicker.js'></script>
    <script src='<?=$dir;?>packages/colorpicker/bootstrap-colorpicker.min.js'></script>
    <script src='<?=$dir;?>calendar.js'></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        $('body').on('click', '.datetimepicker', function() {
            $(this).not('.hasDateTimePicker').datetimepicker({
                controlType: 'select',
                changeMonth: true,
                changeYear: true,
                dateFormat: "dd-mm-yy",
                timeFormat: 'HH:mm:ss',
                yearRange: "1900:+10",
                showOn:'focus',
                firstDay: 1
            }).focus();
        });

        $(".colorpicker").colorpicker();
        
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            defaultDate: '<?=date('Y-m-d');?>',
            navLinks: true, // can click day/week names to navigate views
            businessHours: true, // display business hours
            editable: true,
            events: '<?=$dir;?>api/load_calendar.php',
            /*select: function(arg) {
                var title = prompt('Event Title:');
                if (title) {
           
                    $.ajax({
                        url:"<?=$dir;?>api/insert_calendar.php",
                        type:"POST",
                        data:{title:title, start:arg.endStr, end:arg.endStr},
                    })

                }
                calendar.refetchEvents();
            },*/
            eventDrop: function(arg) {
                var start = arg.event.start.toDateString()+' '+arg.event.start.getHours()+':'+arg.event.start.getMinutes()+':'+arg.event.start.getSeconds();
                var end = arg.event.end.toDateString()+' '+arg.event.end.getHours()+':'+arg.event.end.getMinutes()+':'+arg.event.end.getSeconds();

                $.ajax({
                  url:"<?=$dir;?>api/update_calendar.php",
                  type:"POST",
                  data:{id:arg.event.id, start:start, end:end},
                });
            },
            eventResize: function(arg) {
                var start = arg.event.start.toDateString()+' '+arg.event.start.getHours()+':'+arg.event.start.getMinutes()+':'+arg.event.start.getSeconds();
                var end = arg.event.end.toDateString()+' '+arg.event.end.getHours()+':'+arg.event.end.getMinutes()+':'+arg.event.end.getSeconds();

                $.ajax({
                  url:"<?=$dir;?>api/update_calendar.php",
                  type:"POST",
                  data:{id:arg.event.id, start:start, end:end},
                });
            },
            eventClick: function(arg) {
                if(confirm("Are you sure you want to remove it?")) {
                    $.ajax({
                        url:"<?=$dir;?>api/delete_calendar.php",
                        type:"POST",
                        data:{id:arg.event.id},
                    });          
                }
                calendar.refetchEvents();
            }
        });

        calendar.render();

        $('#createEvent').submit(function(event) {

            // stop the form refreshing the page
            event.preventDefault();

            $('.form-group').removeClass('has-error'); // remove the error class
            $('.help-block').remove(); // remove the error text

            // process the form
            $.ajax({
                type        : "POST",
                url         : '<?=$dir;?>api/insert_calendar.php',
                data        : $(this).serialize(),
                dataType    : 'json',
                encode      : true
            }).done(function(data) {

                // insert worked
                if (data.success) {
                    
                    //remove any form data
                    $('#createEvent').trigger("reset");

                    //close model
                    $('#addeventmodal').modal('hide');

                    //refresh calendar
                    calendar.refetchEvents();

                } else {

                    //if error exists update html
                    if (data.errors.date) {
                        $('#date-group').addClass('has-error');
                        $('#date-group').append('<div class="help-block">' + data.errors.date + '</div>');
                    }

                    if (data.errors.title) {
                        $('#title-group').addClass('has-error');
                        $('#title-group').append('<div class="help-block">' + data.errors.title + '</div>');
                    }

                }

            });
        });
    });
    </script>
</head>
<body>

<div class="modal fade" id="addeventmodal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="container-fluid">

                    <form id="createEvent" class="form-horizontal">

                    <div class="row">

                        <div class="col-md-6">

                            <div id="date-group" class="form-group">
                                <label class="control-label" for="date">Date</label>
                                <input type="text" class="form-control datetimepicker" name="date">
                                <!-- errors will go here -->
                            </div>

                            <div id="title-group" class="form-group">
                                <label class="control-label" for="title">Title</label>
                                <input type="text" class="form-control" name="title">
                                <!-- errors will go here -->
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div id="color-group" class="form-group">
                                <label class="control-label" for="color">Colour</label>
                                <input type="text" class="form-control colorpicker" name="color" value="#6453e9">
                                <!-- errors will go here -->
                            </div>

                            <div id="textcolor-group" class="form-group">
                                <label class="control-label" for="textcolor">Text Colour</label>
                                <input type="text" class="form-control colorpicker" name="text_color" value="#ffffff">
                                <!-- errors will go here -->
                            </div>

                        </div>

                    </div>

                    

                </div>

            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>

            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

    <div class="container">

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addeventmodal">
          Add Event
        </button>

        <div id="calendar"></div>
    </div>

</body>
</html>
