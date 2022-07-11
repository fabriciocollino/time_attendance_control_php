
<div style="overflow: auto;height: 100%">
    <nav id="MobileMenuDivWrapper">
        <ul id="menu" style="overflow: auto;height: 100%">

            <!-- EMPLOYEE MENU -->
            <?php if( $_SESSION['PERSONA_LOGIN']){?>

                <?php if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >=5) { ?>

                    <!-- HOME -->
                    <li class="">
                        <a href="#inicio_empleado" title="Inicio">
                            <i class="fa fa-lg fa-fw fa-home"></i>
                            <span class="menu-item-parent">
                                Inicio
                            </span>
                        </a>
                    </li>

                    <!-- MY ACCOUNT -->
                    <li>

                        <a href="#mi-usuario">
                            <i class="fa fa-lg fa-fw fa-user"></i>
                            <span class="menu-item-parent">
                                Mi Usuario
                            </span>
                        </a>

                    </li>

                    <!-- REPORTS -->
                    <li>

                        <a href="#">
                            <i class="fa fa-lg fa-fw fa-bar-chart-o"></i>
                            <span class="menu-item-parent">
                                Reportes
                            </span>
                        </a>

                        <ul>

                            <li>
                                <a href="#reporte_ausencias_empleado">
                                    <i class="fa fa-fw fa-hourglass-o"></i>
                                    Ausencias
                                </a>
                            </li>

                            <li>
                                <a href="#reporte_llegadas_tarde_empleado">
                                    <i class="fa fa-fw fa-hourglass-half"></i>
                                    Llegadas Tarde
                                </a>
                            </li>

                            <li>
                                <a href="#reporte_salidas_temprano_empleado">
                                    <i class="fa fa-fw fa-hourglass-end"></i>
                                    Salidas Temprano
                                </a>
                            </li>


                            <!-- <li>
                                <a href="#reporte_feriados">Feriados</a>
                            </li> -->
                            <li>
                                <a href="#reporte_entradas_salidas_empleado">
                                    <i class="fa fa-fw fa-arrow-right"></i>
                                    Entradas y Salidas
                                </a>
                            </li>

                            <li>
                                <a href="#reporte_dias_horas_trabajadas_empleado">
                                    <i class="fa fa-fw fa-plus"></i>
                                    Horas Trabajadas
                                </a>
                            </li>

                            <li>
                                <a href="#reporte_payroll_por_dia_empleado">
                                    <i class="fa fa-fw fa-check"></i>
                                    Liquidaciones
                                </a>
                            </li>

                        </ul>
                    </li>



                <?php } ?>


            <?php }

            //  <!-- ADMINS MENU -->
            else{ ?>

                <?php if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() == 10) { ?>

                    <!-- PERSONAS -->
                    <li>
                        <a href="#personas">
                            <i class="fa fa-lg fa-fw fa-user"></i>
                            <span class="menu-item-parent">
                                Personas
                            </span>
                        </a>
                    </li>

                    <!-- EQUIPOS -->
                    <li>
                        <a href="#equipos">
                            <i class="fa fa-lg fa-fw fa-hdd-o"></i>
                            <span class="menu-item-parent">
                                Equipos
                            </span>
                        </a>
                    </li>

                <?php } ?>

                <?php if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 20) { ?>

                    <!-- HOME -->
                    <li style="padding-top: 10px;">
                        <a href="#inicio" title="Inicio">
                            <i class="fa fa-lg fa-fw fa-home"></i>
                            <span class="menu-item-parent">
                                Inicio
                            </span>
                        </a>
                    </li>

                    <!-- PERSONS -->
                    <li style="padding-top: 10px;">
                        <a href="#">
                            <i class="fa fa-lg fa-fw fa-user"></i>

                            <span class="menu-item-parent">
                                Personas
                            </span>
                        </a>

                        <ul>
                            <!-- LIST OF PERSONS -->
                            <li>
                                <a href="#personas">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    <span class="menu-item-parent">
                                        Listado de Personas
                                    </span>
                                </a>
                            </li>

                            <!-- GROUPS -->
                            <li>
                                <a href="#grupos">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    <span class="menu-item-parent">
                                        Grupos de Personas
                                    </span>
                                </a>
                            </li>

                        </ul>

                    </li>

                    <!-- SCHEDULES -->
                    <li style="padding-top: 10px;">

                        <a href="#">
                            <i class="fa fa-lg fa-fw fa-clock-o"></i>
                            <span class="menu-item-parent">
                                Horarios
                            </span>
                        </a>


                        <ul>
                            <li>
                                <a href="#horarios">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    <span class="menu-item-parent">
                                        Horarios de Trabajo
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="#horarios_flexibles">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    <span class="menu-item-parent">
                                        Horarios Flexibles
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="#horarios_multiples">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    <span class="menu-item-parent">
                                        Horarios Múltiples
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="#horarios_rotativos">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    <span class="menu-item-parent">
                                        Horarios Rotativos
                                    </span>
                                </a>
                            </li>

                            <!-- CONFIGURACION: TODO -->
                            <li>
                                <a href="#horarios-configuracion">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    <span class="menu-item-parent">
                                        Configuración
                                    </span>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <!-- MESSAGES     -->
                    <!-- <li>
                       <a href="#">
                           <i class="fa fa-lg fa fa-fw fa-envelope-o"></i>
                           <span class="menu-item-parent">
                              Mensajes
                           </span>
                       </a>

                       <ul>

                           <li>
                               <a href="#message_inbox" class="set_session">
                                   <i class="fa fa-fw fa-folder-o"></i>
                                   <span class="menu-item-parent">
                                       Buzón de Entrada
                                   </span>
                               </a>
                           </li>

                           <li>
                               <a href="#message_outbox" class="set_session">
                                   <i class="fa fa-fw fa-check-square-o"></i>
                                   <span class="menu-item-parent">
                                       Buzón de Salida
                                   </span>
                               </a>
                           </li>

                           <li>
                               <a href="#message_scheduled" class="set_session">
                                   <i class="fa fa-fw fa-calendar-times-o"></i>
                                   <span class="menu-item-parent">
                                       Programados
                                   </span>
                               </a>
                           </li>



                       </ul>
                   </li>
                   -->

                    <!-- CALENDAR -->
                    <li style="padding-top: 10px;">
                        <a href="#">
                            <i class="fa fa-lg fa-fw fa-calendar"></i>
                            <span class="menu-item-parent">
                                Calendario
                            </span>
                        </a>

                        <ul>


                            <!-- SUSPENSIONS -->
                            <li>
                                <a href="#suspensions" class="set_session">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    <span class="menu-item-parent">
                                         Suspenciones
                                     </span>
                                </a>
                            </li>

                            <li>
                                <a href="#licencias" class="set_session">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    <span class="menu-item-parent">
                                        Licencias
                                    </span>
                                </a>
                            </li>
                            <!--
                                                     <li>
                                                         <a href="#permissions" class="set_session">
                                                             <i class="fa fa-fw fa-check-square-o"></i>
                                                             <span class="menu-item-parent">
                                                                 Permisos
                                                             </span>
                                                         </a>
                                                     </li>
                          -->
                            <li>
                                <a href="#feriados" class="set_session">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    <span class="menu-item-parent">
                                         Feriados
                                     </span>
                                </a>
                            </li>





                        </ul>
                    </li>

                    <!-- NOTIFICATIONS -->
                    <li style="padding-top: 10px;">

                        <a href="#">
                            <i class="fa fa-lg fa-fw fa-bullhorn"></i>
                            <span class="menu-item-parent">
                                Notificaciones
                            </span>
                        </a>
                        <ul>
                            <li>
                                <a href="#alertas">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    <span class="menu-item-parent">
                                        Alertas
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="#reportes_automaticos">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    <span class="menu-item-parent">
                                        Reportes Automáticos
                                    </span>
                                </a>
                            </li>
                        </ul>

                    </li>

                    <!-- REPORTS -->
                    <li style="padding-top: 10px;">

                        <a href="#">
                            <i class="fa fa-lg fa-fw fa-bar-chart-o"></i>
                            <span class="menu-item-parent">
                                Reportes
                            </span>
                        </a>

                        <ul>

                            <li>
                                <a href="#reporte_registros">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    Registros
                                </a>
                            </li>

                            <li>
                                <a href="#reporte_marcaciones">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    Marcaciones
                                </a>
                            </li>

                            <li>
                                <a href="#reporte_listado_asistencias">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    Asistencias
                                </a>
                            </li>

                            <li>
                                <a href="#reporte_listado_ausencias">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    Ausencias
                                </a>
                            </li>


                            <li>
                                <a href="#reporte_llegadas_tarde">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    Llegadas Tarde
                                </a>
                            </li>

                            <li>
                                <a href="#reporte_salidas_temprano">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    Salidas Temprano
                                </a>
                            </li>


                            <li>
                                <a href="#reporte_jornada">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    Jornadas
                                </a>
                            </li>

                            <li>
                                <a href="#reporte_intervalo">
                                    <i class="fa fa-fw fa-dot-circle-o"></i>
                                    Intervalos
                                </a>
                            </li>



                        </ul>
                    </li>



                <?php } ?>


                <!-- LOGS, PLANNING, SUCURSALES, ACCIONES DE SISTEMA-->
                <?php if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 999) { ?>


                    <li style="padding-top: 10px;">
                        <a href="#"><i class="fa fa-lg fa-fw fa-list-alt"></i>
                            <span class="menu-item-parent">Dev</span></a>
                        <ul>

                            <li>
                                <a href="#logs_web">
                                    <i class="fa fa-fw fa-list-alt"></i>
                                    <span class="menu-item-parent">
                                        Logs Sistema
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="#logs_email">
                                    <i class="fa fa-fw fa-envelope"></i>
                                    <span class="menu-item-parent">
                                        Logs Email
                                    </span></a>
                            </li>

                            <li>
                                <a href="#logs_equipos">
                                    <i class="fa fa-fw fa-hdd-o"></i>
                                    <span class="menu-item-parent">
                                        Logs Equipos
                                    </span>
                                </a>
                            </li>

                            <!-- PLANNING  -->
                            <li>
                                <a href="#planning">
                                    <i class="fa fa-fw fa-calendar"></i>
                                    <span class="menu-item-parent">
                                        Planning
                                    </span>
                                </a>
                            </li>

                            <!-- SUCURSALES -->
                            <li>
                                <a href="#feriados">
                                    <i class="fa fa-fw fa-industry"></i>
                                    <span class="menu-item-parent">
                                        Sucursales
                                    </span>
                                </a>
                            </li>

                            <!-- SYSTEM ACTIONS -->
                            <li>
                                <a href="#acciones">
                                    <i class="fa fa-fw fa-briefcase"></i>
                                    <span class="menu-item-parent">
                                        Acciones del Sistema
                                    </span>
                                </a>
                            </li>


                        </ul>

                    </li>







                <?php } ?>

            <?php }?>

        </ul>

    </nav>
</div>

