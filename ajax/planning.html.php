<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/' . basename(__FILE__, '.html.php') . '.php'; ?>
<?php $Filtro_Form_Action = "ajax/" . basename(__FILE__); ?>

<?php require_once APP_PATH . '/includes/top-mensajes.inc.php'; ?>


<!-- BREAD CRUMB -->
<div class="row">

    <!-- col -->
    <div class="col-xs-8 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-calendar"></i>
            <?php echo _('Planning') ?>
            <span>>
                <?php echo _('Planning') ?>
			</span>
        </h1>
    </div>

</div>



<!-- BODY -->
<section id="widget-grid" class="">

    <div class="row">

        <!-- PERSONAS -->
        <div class="col-sm-12 col-md-12 col-lg-3">
            <div class                      =   "jarviswidget jarviswidget-color-blueDark"
                 data-widget-editbutton     =   "false"
                 data-widget-colorbutton    =   "false"
                 data-widget-togglebutton   =   "false"
                 data-widget-deletebutton   =   "false"
                 data-widget-sortable       =   "false">

                <!-- HEADER: PERSONAS -->
                <header>
                    <h2> Personas </h2>
                </header>

                <!-- BODY: PERSONAS/GRUPOS, DETALLE, COLOR -->
                <div>
                    <div class="widget-body">

                        <form id="add-event-form">

                            <!-- SELECT PERSONA/GRUPO; INPUT DETALLE; COLOR SELECT -->
                            <fieldset>

                                <!-- SELECT: PERSONA/GRUPO -->
                                <div class="form-group smart-form" style="margin-bottom: 10px">
                                        <label>
                                            Seleccionar Personas/Grupos
                                        </label>

                                    <!-- SELECT: PERSONA -->
                                    <div>
                                        <label class="select">

                                            <!-- ICON -->
                                            <span class="icon-prepend fa fa-user"></span>

                                            <!-- SELECT: PERSON -->
                                            <select name="persona" id="selPersona" style="padding-left: 32px;">
                                                <?php 
													if(isset($_SESSION['filtro']['persona'])){
														echo HtmlHelper::array2htmloptions(Persona_L::obtenerTodos(0, 0, 0, 'per_Hor_Id <> 0 and per_Eliminada=0 and per_Excluir=0'), $_SESSION['filtro']['persona'], true, true, 'PersonayRol', _('Todas las Personas'));
													}	
													?>
                                            </select>
                                            <i></i>

                                        </label>
                                    </div>

                                    <!-- SELECT: GRUPO -->
                                    <div id="selRol" style="margin-top: 5px;">
                                        <label class="select">

                                            <!-- ICON -->
                                            <span class="icon-prepend fa fa-users"></span>

                                            <!-- SELECT: GROUP -->
                                            <select name="rolF" style="padding-left: 32px;">
                                                <?php  
													if(isset($_SESSION['filtro']['rolF'])){
														echo HtmlHelper::array2htmloptions(Grupo_L::obtenerTodos(), $_SESSION['filtro']['rolF'], true, true, '', _('Seleccione un Grupo'));
													}
													?>
                                            </select>
                                            <i></i>

                                        </label>
                                    </div>
                                </div>

                                <!-- INPUT: DETALLE -->
                                <div class="form-group">

                                    <!-- LABEL -->
                                    <label>
                                        Detalle
                                    </label>

                                    <!-- TEXT AREA -->
                                    <textarea class         ="form-control"
                                              placeholder   ="breve descripción (opcional)"
                                              rows          ="3"
                                              maxlength     ="40"
                                              id            ="description">
                                    </textarea>

                                    <!-- NOTE: MAX 40 CHAR -->
                                    <p class="note">
                                        Máximo 40 caracteres
                                    </p>

                                </div>

                                <!-- INPUT: COLOR SELECT -->
                                <div class="form-group">

                                    <!-- LABEL -->
                                    <label>
                                        Seleccionar color
                                    </label>

                                    <!-- COLOR SELECT -->
                                    <div class="btn-group btn-group-justified btn-select-tick" data-toggle="buttons">

                                        <!-- WHITE -->
                                        <label class="btn bg-color-darken active">
                                            <input type="radio" name="priority" id="option1" value="bg-color-darken" checked>
                                            <i class="fa fa-check txt-color-white"></i>
                                        </label>

                                        <!-- BLUE -->
                                        <label class="btn bg-color-blue">
                                            <input type="radio" name="priority" id="option2" value="bg-color-blue">
                                            <i class="fa fa-check txt-color-white"></i>
                                        </label>

                                        <!-- ORANGE -->
                                        <label class="btn bg-color-orange">
                                            <input type="radio" name="priority" id="option3" value="bg-color-orange">
                                            <i class="fa fa-check txt-color-white"></i>
                                        </label>

                                        <!-- GREEN -->
                                        <label class="btn bg-color-greenLight">
                                            <input type="radio" name="priority" id="option4" value="bg-color-greenLight">
                                            <i class="fa fa-check txt-color-white"></i>
                                        </label>

                                        <!-- BLUE LIGHT -->
                                        <label class="btn bg-color-blueLight">
                                            <input type="radio" name="priority" id="option5" value="bg-color-blueLight">
                                            <i class="fa fa-check txt-color-white"></i>
                                        </label>

                                        <!-- RED -->
                                        <label class="btn bg-color-red">
                                            <input type="radio" name="priority" id="option6" value="bg-color-red">
                                            <i class="fa fa-check txt-color-white"></i>
                                        </label>

                                    </div>
                                </div>

                            </fieldset>

                            <!-- BUTTON: ADD EVENT -->
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-default" type="button" id="add-event" >
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>

        <!-- CALENDAR -->
        <div class="col-sm-12 col-md-12 col-lg-9">
            <div class                      =   "jarviswidget jarviswidget-color-blueDark"
                 data-widget-editbutton     =   "false"
                 data-widget-colorbutton    =   "false"
                 data-widget-togglebutton   =   "false"
                 data-widget-deletebutton   =   "false"
                 data-widget-sortable       =   "false">

                <!-- HEADER -->
                <header>

                    <!-- ICON: CALENDAR -->
                    <span class="widget-icon">
                        <i class="fa fa-calendar"></i>
                    </span>

                    <!-- LABEL: CALENDARIO -->
                    <h2>
                        Calendario
                    </h2>

                    <!-- ICON: SPINNER -->
                    <span class="jarviswidget-ctrls" style="line-height: 8px;font-size: 8px;padding-top: 4px;">
                        <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                    </span>

                </header>

                <!-- WIDGET -->
                <div>
                    <div class="widget-body no-padding">

                        <!-- BUTTONS: PREVIOUS/NEXT -->
                        <div class="widget-body-toolbar">

                            <div id="calendar-buttons">

                                <div class="btn-group">
                                    <a href="javascript:void(0)" class="btn btn-default" id="btn-prev"><i class="fa fa-chevron-left"></i></a>
                                    <a href="javascript:void(0)" class="btn btn-default" id="btn-next"><i class="fa fa-chevron-right"></i></a>
                                </div>

                            </div>

                        </div>

                        <!-- DIV: CALENDAR -->
                        <div id="calendar">
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

</section>


<link rel='stylesheet' href='https://static.enpuntocontrol.com/app/v1/css/fullcalendar.min.css' />

<script src='https://static.enpuntocontrol.com/app/v1/js/plugin/fullcalendar/fullcalendar.min.js'></script>
<script src='https://static.enpuntocontrol.com/app/v1/js/plugin/fullcalendar/locale/es.js'></script>

<style>

    .popover-title {
    margin: 0 !important;
    padding: 8px 14px !important;
    }

    .popover-content {
    padding: 9px 14px !important;
    }

</style>

<script type="text/javascript">

    var timeoutObj; //para los popover

    pageSetUp();

    if ($('.DTTT_dropdown.dropdown-menu').length) {
        $('.DTTT_dropdown.dropdown-menu').remove();
    }

    <?php
    //INCLUYO el js de las datatables
    require_once APP_PATH . '/includes/data_tables.js.php';
    ?>

    //esto asigna el ID al modal cada vez que se hace click en el boton
    $(document).ready(function () {

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var hdr = {
            left: 'title',
            center: 'month,agendaWeek,agendaDay',
            right: 'prev,today,next'
        };

        let addEvent = function (title, priority, description, icon, persona, grupo) {

            priority = priority.length === 0 ? "label label-default" : priority;

            //let firstDay = moment($('#calendar').fullCalendar('getCalendar').view.start);
            firstDay = moment(firstDay).utc();  //le agrego un dia para que aparezca en el primer cuadradito

            let eventObject = {
                title: $.trim(title),
                persona: persona,
                grupo: grupo,
                description: $.trim(description),
                start: firstDay,
                icon: $.trim(icon),
                className: $.trim(priority),
                allDay: true
            };
            /*

                        $('#calendar').fullCalendar('renderEvent', {
                            title: "Birmingham Comic Con",
                            start: new Date('2018-03-08T09:00'),
                            end: new Date('2018-03-10T19:00'),
                            id: 1
                        }, true);


                        es decir, el evento va por multiples dias con cierta hora
                        lo que tengo que hacer es modificar el codigo del calendar para que me permita resizar eventos como ese
                        tambien que permita hacer drag and drop en la vista de mes de eso

                        me esta faltando tambien hacer que el spiner se hide or show
                        y poner la hora completa en el evento en la vista de mes


            $('#calendar').fullCalendar('renderEvent', eventObject, true);

            saveEvent($('#calendar').fullCalendar('clientEvents')[$('#calendar').fullCalendar('clientEvents').length-1]);
    */
        };

        $('#add-event').click(function () {
            let title = "";
            let icon = "";
            let persona = 0;
            let grupo = 0;
            if ($('#selPersona').val() === "SelectRol") {
                grupo = $('#selRol select').val();
                title = $('#selRol select option:selected').text();
                icon = "fa-users";

            }
            else {
                persona = $('#selPersona').val();
                title = $('#selPersona option:selected').text();
                icon = "fa-user";
            }

            let priority = $('input:radio[name=priority]:checked').val(),
                description = $('#description').val();

            addEvent(title, priority, description, icon, persona, grupo);
        });


/*



        // initialize the external events


        $('#external-events > li').each(function () {
            initDrag($(this));
        });



        // initialize the calendar

       */
        /*
                $('#calendar').fullCalendar({

                    header: hdr,
                    editable: true,
                    //droppable: true, // this allows things to be dropped onto the calendar !!!
                    navLinks: true, // can click day/week names to navigate views
                    locale: 'es',


                    drop: function (date, allDay) { // this function is called when something is dropped

                        // retrieve the dropped element's stored Event Object
                        var originalEventObject = $(this).data('eventObject');

                        // we need to copy it, so that multiple events don't have a reference to the same object
                        var copiedEventObject = $.extend({}, originalEventObject);

                        // assign it the date that was reported
                        copiedEventObject.start = date;
                        copiedEventObject.allDay = allDay;

                        // render the event on the calendar
                        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                        // is the "remove after drop" checkbox checked?
                        if ($('#drop-remove').is(':checked')) {
                            // if so, remove the element from the "Draggable Events" list
                            $(this).remove();
                        }

                    },

                    select: function (start, end, allDay) {
                        var title = prompt('Event Title:');
                        if (title) {
                            calendar.fullCalendar('renderEvent', {
                                    title: title,
                                    start: start,
                                    end: end,
                                    allDay: allDay
                                }, true // make the event "stick"
                            );
                        }
                        calendar.fullCalendar('unselect');
                    },
        */
            events: 'codigo/controllers/planning.php',
            /*events: {
                url: 'codigo/controllers/planning.php',
                type: 'GET',
                success: function(a,b,c) {
                    //alert('success');
                    debugger;
                },
                error: function(a,b,c) {
                    //alert('there was an error while fetching events!');
                    debugger;
                }
            },
*/
            eventRender: function (event, element, icon) {
                if (!event.description == "") {
                    element.find('.fc-title').append("<br/><span class='ultra-light'>" + event.description +
                        "</span>");
                }
                if (!event.icon == "") {
                    element.find('.fc-title').append("<i style='line-height:17px;' class='air air-top-right fa " + event.icon +
                        " '></i>");
                }
                element.addClass('event_pop');
                element.data('content','<h2>'+event.title+'</h2><div><button class="btn btn-default" data-type="delete" data-id="'+event._id+'" >Eliminar</button></div>');

            },

            eventAfterAllRender: function( view ) {
                $('.event_pop').popover({
                    offset: 10,
                    trigger: 'manual',
                    html: true,
                    placement: 'auto',
                    container:'body',
                    template: '<div class="popover" onmouseover="clearTimeout(timeoutObj);$(this).mouseleave(function() {$(this).hide();});"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>'
                }).mouseenter(function (e) {
                    $(this).popover('show');
                }).mouseleave(function (e) {
                    var ref = $(this);
                    timeoutObj = setTimeout(function () {
                        ref.popover('hide');
                    }, 50);
                });

                $('body').off('click', 'button[data-type=delete]');
                $('body').on('click', 'button[data-type=delete]', function () {
                    var data_id = '';
                    if (typeof $(this).data('id') !== 'undefined') {
                        data_id = $(this).data('id');
                    }
                    loadURLwData('planning', $('#content'), {tipo: 'deleteEvent', id: data_id});

                });


            },

            windowResize: function (event, ui) {
                //$('#calendar').fullCalendar('render');
            },

            eventResize: function(event, delta, revertFunc) {

                saveEvent(event,revertFunc);

            },

            eventDrop: function(event, delta, revertFunc) {

                saveEvent(event,revertFunc);

            }


        });

        //$('#calendar').fullCalendar('option', 'timezone', "<?php echo $timezone ?>");

        /* hide default buttons */
        $('.fc-right').hide();


        $('#calendar-buttons #btn-prev').click(function () {
            $('.fc-prev-button').click();
            return false;
        });

        $('#calendar-buttons #btn-next').click(function () {
            $('.fc-next-button').click();
            return false;
        });

        $('#calendar-buttons #btn-today').click(function () {
            $('.fc-today-button').click();
            return false;
        });

        $('.widget-body-toolbar').prepend($('.fc-toolbar'));

        $('.fc-button').removeClass('fc-button').addClass('btn btn-default');
        $('.fc-state-default').removeClass('fc-state-default');



        function saveEvent(eventObject, revertFunc){

            delete eventObject.source;

            $.ajax({
                cache: false,
                type: 'POST',
                url: '<?php echo 'codigo/controllers/planning.php' ?>',
                data: {tipo: 'saveEvent', event: JSON.stringify(eventObject)},
                success: function (data) {
                    console.log(data);

                },
                error: function (data){
                    console.log(data);
                    revertFunc();
                }
            });

        }


    });


    $('#selPersona').change(function () {

        if ($(this).find('option:selected').attr('value') === 'SelectRol') {
            $('#selRol').show();
        }
        else {
            $('#selRol').hide();
        }

    });

    $('#selPersona').trigger("change");

</script>
<?php // require_once APP_PATH . '/includes/chat_widget.php'; ?>
