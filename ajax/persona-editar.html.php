<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/personas.php'; ?>


<!-- HEADER -->
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="modalTitle"><?php
        if ($o_Persona->getId() == null)
            echo _("Agregar Persona");
        else
            echo _("Editar Persona");
        ?></h4>
</div>

<!-- TABS LINKS -->
<div class="tab">
    <button id="PersonalTab" class="tablinks" onclick="openTab(event, 'Personal')">Personal</button>
    <button class="tablinks" onclick="openTab(event, 'Imagen')">Imagen</button>
    <button class="tablinks" onclick="openTab(event, 'Trabajo')">Trabajo</button>
    <button class="tablinks" onclick="openTab(event, 'Notas')" style="display: none;">Notas</button>
</div>


<!-- BODY -->
<div class="modal-body" style="padding-top: 0;">
    <form class                 ="smart-form"
          novalidate            ="novalidate"
          data-async method     ="post"
          id                    ="editar-form"
          action                ="<?php echo 'ajax/' . $Item_Name . 's.html.php' ?>?tipo=<?php
          if ($o_Persona->getId() == null)
              echo "add";
          else
              echo "edit&id=" . $o_Persona->getId();
          ?>">


        <!-- TAB PERSONAL -->
        <div id="Personal" class="tabcontent">
            <!-- LEGAJO, ID, NOMBRE, APELLIDO, FECHA NACIMIENTO, GENERO, ESTADO CIVIL, DNI, NRO CONTIRBUYENTE, TALLA CAMISA -->
            <div class="row">

                <section>
                    <fieldset>
                        <legend>Información básica</legend>

                        <!-- LEGAJO, ID, ESTADO -->
                        <div class="row">


                            <!-- ID -->
                            <section class="col col-1">
                                ID
                                <label class="input" readonly>
                                    <input type="text" name="ID" placeholder="" readonly style="background-color: lightgrey;"
                                           value="<?php echo $o_Persona->getId(); ?>">
                                </label>
                            </section>

                            <!-- LEGAJO -->
                            <section class="col col-3">
                                Legajo
                                <label class="input">
                                    <input type="text" name="legajo" placeholder=""
                                           value="<?php echo htmlentities($o_Persona->getLegajo(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>

                        </div>

                        <!-- ESTADO -->
                        <div class="row">

                            <!-- ESTADO -->
                            <section class="col col-4">
                                Estado
                                <label class="select">
                                    <span class="icon-prepend fa fa-check-circle"></span>
                                    <select name="estado" id="estadoSel1" style="padding-left: 32px;">
                                        <option value="">-Seleccione-</option>
                                        <option value="Activo">Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select> <i></i> </label>
                            </section>

                        </div>

                        <!-- NOMBRE, SEGUNDO NOMBRE, APELLIDO -->
                        <div class="row">

                            <!-- NOMBRE -->
                            <section class="col col-4">
                                Nombre
                                <label class="input">
                                    <input type="text" name="nombre" placeholder="Nombre"
                                           value="<?php echo htmlentities($o_Persona->getNombre(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>

                            <!-- SEGUNDO NOMBRE -->
                            <section class="col col-4">
                                <br>
                                <label class="input">
                                    <input type="text" name="segundoNombre" placeholder="Segundo nombre"
                                           value="<?php echo htmlentities($o_Persona->getSegundoNombre(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>

                            <!-- APELLIDO -->
                            <section class="col col-4">
                                Apellido
                                <label class="input">
                                    <input type="text" name="apellido" placeholder="Apellido"
                                           value="<?php echo htmlentities($o_Persona->getApellido(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>

                        </div>

                        <!-- FECHA DE NACIMIENTO -->
                        <div class="row" >
                            <section class="col col-4">
                                Fecha de nacimiento
                                <div class="form-group">
                                    <div class="input-group">
                                        <input class="form-control " style="padding-left: 5px;font-size: 12px;height: 31px;"
                                               name             ="fechaNacimiento"
                                               id               ="fechaNacimiento"
                                               placeholder      ="Aaaa-mm-dd"
                                               type             ="text"
                                               value            ="<?php

                                               if($o_Persona->getFechaNacimiento() != null &&  $o_Persona->getFechaNacimiento() != '0000-00-00'){
                                                   echo $o_Persona->getFechaNacimiento('Y-m-d');
                                               }
                                               else{
                                                   echo "";
                                               }
                                               ?>">
                                        <span id="btnfechaNacimiento" class="input-group-addon">
                                            <i class="fa fa-calendar" style="cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i>
                                        </span>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <!-- GENERO, ESTADO CIVIL -->
                        <div class="row">

                            <!-- GENERO -->
                            <section class="col col-4">
                                Género
                                <label class="select">
                                    <span class="icon-prepend fa fa-venus-mars"></span>
                                    <select name="genero" id="generoSel1" style="padding-left: 32px;">
                                        <option value="">-Seleccione-</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Femenino">Femenino</option>
                                    </select> <i></i> </label>
                            </section>

                            <!-- ESTADO CIVIL -->
                            <section class="col col-4">
                                Estado civil
                                <label class="select">
                                    <span class="icon-prepend fa fa-user"></span>
                                    <select name="estadoCivil" id="estadoCivilSel1" style="padding-left: 32px;">
                                        <option value="">-Seleccione-</option>
                                        <option value="Soltero/a">Soltero/a</option>
                                        <option value="Casado/a">Casado/a</option>
                                        <option value="Viudo/a">Viudo/a</option>
                                        <option value="Divorciado/a">Divorciado/a</option>
                                    </select> <i></i> </label>
                            </section>

                        </div>

                        <!-- DNI -->
                        <div class="row">
                            <section class="col col-4">
                                DNI
                                <label class="input">
                                    <input type="text" name="dni" placeholder="" value="<?php echo htmlentities($o_Persona->getDni(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>
                        </div>

                        <!-- NRO CONTRIBUYENTE -->
                        <div class="row">
                            <section class="col col-4">
                                Nro. de contribuyente
                                <label class="input">
                                    <input type="text" name="nroContribuyente" placeholder=""  value="<?php echo $o_Persona->getNroContribuyente(); ?>">
                                </label>
                            </section>
                        </div>

                        <!-- TALLA DE CAMISA -->
                        <div class="row">
                            <section class="col col-4">
                                Talla de camisa
                                <label class="select">
                                    <span class="icon-prepend fa fa-user"></span>
                                    <select name="talleCamisa" id="talleCamisaSel1" style="padding-left: 32px;">
                                        <option value="">-Seleccione-</option>
                                        <option value="XS">XS</option>
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                    </select> <i></i> </label>
                            </section>
                        </div>


                    </fieldset>
                </section>

            </div>

            <!-- DOMICILIO -->
            <div class="row">

                <section>
                    <fieldset>
                        <legend>Domicilio</legend>

                        <!-- DIRECCION 1 -->
                        <div class="row">
                            <section class="col col-6">
                                <label class="input">
                                    <input type="text" name="direccion1" placeholder="Dirección 1"
                                           value="<?php echo htmlentities($o_Persona->getDireccion1(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>
                        </div>

                        <!-- DIRECCION 2 -->
                        <div class="row">
                            <section class="col col-6">
                                <label class="input">
                                    <input type="text" name="direccion2" placeholder="Dirección 2"
                                           value="<?php echo htmlentities($o_Persona->getDireccion2(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>
                        </div>

                        <!-- CIUDAD, PROVINCIA, CODIGO POSTAL -->
                        <div class="row">
                            <section class="col col-4">
                                <label class="input">
                                    <input type="text" name="ciudad" placeholder="Ciudad"
                                           value="<?php echo htmlentities($o_Persona->getCiudad(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="input">
                                    <input type="text" name="provincia" placeholder="Provincia"
                                           value="<?php echo htmlentities($o_Persona->getProvincia(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <input type="text" name="codigoPostal" placeholder="Código Postal"
                                           value="<?php echo htmlentities($o_Persona->getCodigoPostal(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>
                        </div>

                        <!-- PAÍS -->
                        <div class="row">
                            <section class="col col-4">
                                País
                                <label class="select">
                                    <span class="icon-prepend fa fa-flag-o"></span>
                                    <select name="pais" id="paisSel1" style="padding-left: 32px;">
                                        <option value="">-Seleccione-</option>
                                        <?php foreach ($_Listado_Paises as $_pais){ ?>
                                            <option value="<?php echo $_pais;?>"><?php echo $_pais;?></option>
                                        <?php }?>
                                    </select> <i></i> </label>
                            </section>
                        </div>


                    </fieldset>
                </section>

            </div>

            <!-- CONTACTO -->
            <div class="row">

                <section>
                    <fieldset>
                        <legend>Contacto</legend>

                        <!-- TEL TRABAJO -->
                        <div class="row">
                            <section class="col col-4">
                                Teléfono
                                <label class="input">
                                    <i class="icon-prepend fa fa-building"></i>
                                    <input type="tel" name="te_trabajo" placeholder="Teléfono Trabajo" data-mask="+549 (999) 999-9999"
                                           value="<?php echo htmlentities($o_Persona->getTeTrabajo(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>
                        </div>

                        <!-- TEL MOVIL -->
                        <div class="row">
                            <section class="col col-4">
                                <label class="input">
                                    <i class="icon-prepend fa fa-mobile"></i>
                                    <input type="tel" name="te_movil" placeholder="Teléfono Móvil" data-mask="(999) 999-9999"
                                           value="<?php echo htmlentities($o_Persona->getTeCelular(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>
                        </div>

                        <!-- TEL PERSONAL-->
                        <div class="row">
                            <section class="col col-4">
                                <label class="input">
                                    <i class="icon-prepend fa fa-home"></i>
                                    <input type="tel" name="te_personal" placeholder="Teléfono Personal" data-mask="(999) 999-9999"
                                           value="<?php echo htmlentities($o_Persona->getTeFijo(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>
                        </div>

                        <!-- CORREO ELECTRONICO TRABAJO -->
                        <div class="row">
                            <section class="col col-6">
                                Correo Electrónico
                                <label class="input">
                                    <i class="icon-prepend fa fa-building"></i>
                                    <input type="email" name="email" placeholder="Correo electrónico de trabajo"
                                           value="<?php echo htmlentities($o_Persona->getEmail(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>
                        </div>

                        <!-- CORREO ELECTRONICO PERSONAL -->
                        <div class="row">
                            <section class="col col-6">
                                <label class="input">
                                    <i class="icon-prepend fa fa-home"></i>
                                    <input type="email" name="emailPersonal" placeholder="Correo electrónico personal"
                                           value="<?php echo htmlentities($o_Persona->getEmailPersonal(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>
                        </div>


                    </fieldset>
                </section>

            </div>

            <!-- ENLACES SOCIALES-->
            <div class="row">

                <section>
                    <fieldset>
                        <legend>Enlaces sociales</legend>

                        <!-- LINKEDIN -->
                        <div class="row">
                            <section class="col col-6">
                                <label class="input">
                                    <i class="icon-prepend fa fa-linkedin"></i>
                                    <input type="text" name="linkedin" placeholder="LinkedIn"
                                           value="<?php echo htmlentities($o_Persona->getLinkedin(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>
                        </div>

                        <!-- TWITTER -->
                        <div class="row">
                            <section class="col col-6">
                                <label class="input">
                                    <i class="icon-prepend fa fa-twitter"></i>
                                    <input type="text" name="twitter" placeholder="Twitter"
                                           value="<?php echo htmlentities($o_Persona->getTwitter(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>
                        </div>

                        <!-- FACEBOOK -->
                        <div class="row">
                            <section class="col col-6">
                                <label class="input">
                                    <i class="icon-prepend fa fa-facebook"></i>
                                    <input type="text" name="facebook" placeholder="Facebook"
                                           value="<?php echo htmlentities($o_Persona->getFacebook(), ENT_COMPAT, 'utf-8'); ?>">
                                </label>
                            </section>
                        </div>


                    </fieldset>
                </section>

            </div>

        </div>

        <!-- TAB IMAGEN -->
        <div id="Imagen" class="tabcontent">

            <!-- IMAGEN DE PERFIL -->
            <div class="row">

                <section>

                    <fieldset>

                        <!-- LEGEND-->
                        <legend>
                            Imagen de Perfil
                        </legend>

                        <div class="row" id="ImageUpload">

                            <!-- IMAGE URL, CAMARA DIV, IMAGE CANVAS -->   <!-- DELETE IMAGE -->
                            <section class="col col-6">

                                <!-- IMAGE URL, CAMARA DIV, IMAGE CANVAS -->
                                <div class="thumbPersonaEditar">

                                    <!-- IMAGE URL-->
                                    <?php
                                    if ($o_Persona->getImagen() == '') {
                                        echo '<img style="border-radius: 50%;" src="https://static.enpuntocontrol.com/app/v1/img/avatars/male-big.png" id="finalImg" alt="me" >';
                                    } else {
                                        echo '<img style="border-radius: 50%;" src="imagen.php?per_id=' . $o_Persona->getId() . '" id="finalImg" alt="me" />';
                                    }
                                    ?>

                                    <!-- CAMERA DIV -->
                                    <div id="camara" style="display: none;width: 250px;height: 250px;">
                                    </div>

                                    <!-- IMAGE CANVAS -->
                                    <canvas style="display:block;" id="canvas" width="333" height="250">
                                    </canvas>

                                </div>

                                <!-- DELETE IMAGE -->
                                <span id="btnEliminarImagen"  style="<?php if ($o_Persona->getImagen() == '') echo 'display:none;'; ?> " >
                                <a href="#" onclick="setEliminarImagen();return false;" >
                                    Eliminar Imagen
                                </a>
                            </span>

                            </section>

                            <!-- DROP DOWN AREA, UPLOAD PROGRESS, CAMERA BUTTON, TAKE PHOTO BUTTON -->
                            <section class="col col-6">

                                <!-- TEXT:  ARRASTRA IMAGEN AQUI-->
                                <div id="dropbox">
                                    Arrastra una imagen aquí
                                </div>

                                <!-- DIV: UPLOAD PROGRESS -->
                                <div class="upload-progress">
                                </div>

                                <!-- BUTTON: SELECT IMAGE -->
                                <input type="file" id="fileElem" multiple="true" accept="image/*" onchange="handleFiles(this.files)" />

                                <!-- BUTTON: USE CAMERA -->
                                <button type="button" id="botonCamara"  title="Usar Webcam" class="btn btn-default btn-sm fa fa-camera fa-lg" style="line-height: .75em;padding-right: 5px;padding-left: 9px; float:right;">
                                    &nbsp;
                                </button>

                                <!-- BUTTON: TAKE PHOTO -->
                                <button type="button" id="botonSnapshot" class="btn btn-default btn-sm" style="line-height: .75em;padding: 12px;display:none;">
                                    Tomar Foto
                                </button>

                                <!-- DIV: SNAP UPLOAD PROGRESS -->
                                <div id="snapUploadProgress" class="fa fa-cog fa-lg fa-spin" style="line-height: .75em;padding: 12px;display:none;">
                                </div>

                            </section>


                            <!-- VAR: DELETE IMAGE -->
                            <input type="hidden" id="inputBorrarImagen" name="inputBorrarImagen"/>

                            <!-- VAR: IMAGE EXTENSION -->
                            <input type="hidden" id="inputImageExtension" name="inputImageExtension"/>

                            <!-- VAR: X,Y,W,H -->
                            <input type="hidden" id="inputIMGx" name="inputIMGx"/>
                            <input type="hidden" id="inputIMGy" name="inputIMGy"/>
                            <input type="hidden" id="inputIMGw" name="inputIMGw"/>
                            <input type="hidden" id="inputIMGh" name="inputIMGh"/>

                            <!-- VAR: IMAGE SOURCE -->
                            <input type="hidden" id="inputIMGsrc" name="inputIMGsrc" value=""/>

                        </div>


                    </fieldset>
                </section>
            </div>

        </div>

        <!-- TAB TRABAJO -->
        <div id="Trabajo" class="tabcontent">


            <!-- FECHA DE NACIMIENTO -->
            <div class="row" >
                <section class="col col-4">
                    Fecha Contratación
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control " style="padding-left: 5px;font-size: 12px;height: 31px;"
                                   name             ="fechaContratacion"
                                   id               ="fechaContratacion"
                                   placeholder      ="Aaaa-mm-dd"
                                   type             ="text"
                                   value            ="<?php

                                   if($o_Persona->getFechaD() != null &&  $o_Persona->getFechaD() != '0000-00-00' &&  $o_Persona->getFechaD() != '0000-00-00'){
                                       echo $o_Persona->getFechaD();
                                   }
                                   else{
                                       echo "";
                                   }
                                   ?>">
                            <span id="btnDesde" class="input-group-addon">
                                            <i class="fa fa-calendar" style="cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i>
                                        </span>
                        </div>
                    </div>
                </section>
            </div>

            <!-- HORARIO -->
            <div class="row">

                <section>

                    <fieldset>
                        <legend>Horario</legend>

                        <!-- HORARIOS -->
                        <div class="row">
                            <section class="col col-6">
                                <label class="select"> <span class="icon-prepend fa fa-clock-o"></span>
                                    <select name="horario_tipo" id="selTipo" style="padding-left: 32px;">
                                        <?php echo HtmlHelper::array2htmloptions($a_Tipos_De_Horario, $o_Persona->getHorTipo(), true, false, '', _('Seleccione un Tipo de Horario')); ?>
                                    </select> <i></i> </label>
                            </section>
                            <section class="col col-6" id="sel-horarioNormal">
                                <label class="select"> <span class="icon-prepend fa fa-clock-o"></span>
                                    <select name="horarioNormId" id="horarioNormId" style="padding-left: 32px;">
                                        <?php echo HtmlHelper::array2htmloptions(Hora_Trabajo_L::obtenerTodos(), $o_Persona->getHorId(), true, true, '', 'Seleccione un Horario'); ?>
                                    </select> <i></i> </label>
                            </section>
                            <section class="col col-6" id="sel-horarioFlexible">
                                <label class="select"> <span class="icon-prepend fa fa-clock-o"></span>
                                    <select name="horarioFlexId" id="horarioFlexId" style="padding-left: 32px;">
                                        <?php echo HtmlHelper::array2htmloptions(Horario_Flexible_L::obtenerTodos(), $o_Persona->getHorId(), true, true, '', 'Seleccione un Horario'); ?>
                                    </select> <i></i> </label>
                            </section>
                            <section class="col col-6" id="sel-horarioRotativo">
                                <label class="select"> <span class="icon-prepend fa fa-clock-o"></span>
                                    <select name="horarioRotId" id="horarioRotId" style="padding-left: 32px;">
                                        <?php echo HtmlHelper::array2htmloptions(Horario_Rotativo_L::obtenerTodos(), $o_Persona->getHorId(), true, true, '', 'Seleccione un Horario'); ?>
                                    </select> <i></i> </label>
                            </section>
                            <section class="col col-6" id="sel-horarioMultiple">
                                <label class="select"> <span class="icon-prepend fa fa-clock-o"></span>
                                    <select name="horarioMultId" id="horarioMultId" style="padding-left: 32px;">
                                        <?php echo HtmlHelper::array2htmloptions(Horario_Multiple_L::obtenerTodos(), $o_Persona->getHorId(), true, true, '', 'Seleccione un Horario'); ?>
                                    </select> <i></i> </label>
                            </section>

                        </div>

                    </fieldset>

                </section>

            </div>

            <!-- EQUIPOS -->
            <div class="row">

                <section>
                    <fieldset>
                        <legend>Equipos</legend>
                        <div class="row">
                            <section class="col col-xs-10">
                                <i class="icon-prepend icon-prepend-chosen fa fa-hdd-o"></i>
                                <div class="div-chosen-select">
                                    <select name="equipo[]" id="selEquipo" multiple class="chosen-select"
                                            style="width:100%;padding-left: 32px;">
                                        <?php
                                        $a_o_Equipo = Equipo_L::obtenerTodos();
                                        $array_equipos = explode(':', $o_Persona->getEquipos());
                                        if ($a_o_Equipo != null) {
                                            foreach ($a_o_Equipo as $o_Equipo) {
                                                $checked = '';
                                                if (in_array($o_Equipo->getId(), $array_equipos))
                                                    $checked = 'selected="selected"';

                                                echo '<option value="' . $o_Equipo->getId() . '" ' . $checked . ' >';
                                                echo $o_Equipo->getDetalle();
                                                echo "</option>";
                                            }
                                        }

                                        ?>
                                    </select>

                                </div>
                            </section>

                        </div>
                    </fieldset>

                </section>

            </div>

            <!-- GRUPOS -->
            <div class="row">

                <section>

                    <fieldset>
                        <legend>Grupos</legend>

                        <!-- GRUPOS -->
                        <div class="row">
                            <section class="col col-xs-10">
                                <i class="icon-prepend icon-prepend-chosen fa fa-users"></i>
                                <div class="div-chosen-select">
                                    <select name="grupo[]" id="selGrupo" multiple class="chosen-select"
                                            style="width:100%;padding-left: 32px;">
                                        <?php
                                        $a_Grupos_Personas = Grupos_Personas_L::obtenerARRAYPorPersona($o_Persona->getId());
                                        if(!is_null($a_Grupos_Personas)) {
                                            $a_o_Grupos = Grupo_L::obtenerTodos();

                                            if ($a_o_Grupos != null) {
                                                foreach ($a_o_Grupos as $o_Grupo) {
                                                    $checked = '';
                                                    if (in_array($o_Grupo->getId(), $a_Grupos_Personas))
                                                        $checked = 'selected="selected"';

                                                    echo '<option value="' . $o_Grupo->getId() . '" ' . $checked . ' >';
                                                    echo $o_Grupo->getDetalle();
                                                    echo "</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </section>
                        </div>


                    </fieldset>

                </section>

            </div>

        </div>

        <!-- TAB NOTAS -->
        <div id="Notas" class="tabcontent" style="display: none;">

            <div id="notas-previas">
                <!-- NOTAS PREVIAS -->
                <?php foreach ($o_Notas as $_notaId => $nota) {?>

                    <fieldset>


                        <!-- CREATION DATE -->
                        <div class="row">
                            <section class="col col-10" style="width: 100%">
                                <label class="input">

                                    <i class="icon-prepend fa fa-calendar-o"></i>
                                    <input  style="border-color: #ffffff;outline: none;resize: none"
                                            readonly
                                            required
                                            type         ="text"
                                            value="<?php
                                            $creado_el = $nota->getCreationDateTime();
                                            $o_Usuario = Usuario_L::obtenerPorId($nota->getUserWriterId());
                                            $nombre_usuario = $o_Usuario->getNombreCompleto();

                                            echo $creado_el. " &nbsp;(" . $nombre_usuario . ")"; ?>
                                                    ">
                            </section>
                        </div>

                        <!-- NOTA -->
                        <div class="row">
                            <section class="col col-10" style="width: 100%">
                                <label class="textarea textarea-resizable">

                                    <i class="icon-prepend fa fa-sticky-note-o"></i>
                                    <textarea   style="outline: none;resize: none"
                                                readonly
                                                rows="5"
                                                placeholder="Nueva Nota"><?php echo stripcslashes($nota->getBody()); ?></textarea>
                                </label>
                            </section>
                        </div>


                    </fieldset>

                <?php }?>
            </div>

            <div class="row">
                <section class="col col-10" style="width: 100%">
                    <button style="margin-left: 15px;" type="button" class="btn btn-default" onclick="agregarNota();">
                        Agregar Nota
                    </button>
                </section>
            </div>


        </div>




    </form>
</div>
<!-- FOOTER -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
        Salir
    </button>
    <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-editar">
        <?php
        if ($o_Persona->getId() == null)
            echo _("Agregar");
        else
            echo _("Guardar");
        ?>
    </button>
</div>




<style>

    @media (min-width: 768px) {
        .modal-dialog {
            width: 60% !important;
        }
    }

    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #ffffff;
    }

    /* Style the buttons inside the tab */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        -webkit-animation: fadeEffect 1s;
        animation: fadeEffect 1s;
    }

    /* Fade in tabs */
    @-webkit-keyframes fadeEffect {
        from {opacity: 0;}
        to {opacity: 1;}
    }

    @keyframes fadeEffect {
        from {opacity: 0;}
        to {opacity: 1;}
    }
</style>



<script type="text/javascript">

    function openTab(evt, tabId) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabId).style.display = "block";
        evt.currentTarget.className += " active";
    }

    document.getElementById("PersonalTab").click();



    $("#pepe").on($.support.transition.end, function(e){
        e.stopPropagation()
    });





    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    var video;
    var video_stream;


    loadScript("https://static.enpuntocontrol.com/app/v1/js/plugin/chosen/chosen.jquery.min.js", function () {

        $("#selEquipo").chosen({
            placeholder_text_multiple: 'Seleccione los Equipos',
            width: '100%'
        });

        $("#selGrupo").chosen({
            placeholder_text_multiple: 'Seleccione los Grupos',
            width: '100%'
        });

        loadScript("https://static.enpuntocontrol.com/app/v1/js/plugin/jcrop/jquery.Jcrop.min.js", function () {
            loadScript("https://static.enpuntocontrol.com/app/v1/js/plugin/jcrop/jquery.color.min.js", function (){
                loadScript("https://static.enpuntocontrol.com/app/v1/js/plugin/jpeg-camera/jpeg_camera_with_dependencies.min.js",pagefunction)
            });
        });
    });

    function pagefunction() {


        var dropbox;
        var api;

        dropbox = document.getElementById("dropbox");
        dropbox.addEventListener("dragenter", dragenter, false);
        dropbox.addEventListener("dragleave", dragleave, false);
        dropbox.addEventListener("dragover", dragover, false);
        dropbox.addEventListener("drop", drop, false);

        function defaults(e) {
            e.stopPropagation();
            e.preventDefault();
        }

        function dragenter(e) {
            $(this).addClass("active");
            defaults(e);
        }

        function dragover(e) {
            defaults(e);
        }

        function dragleave(e) {
            $(this).removeClass("active");
            defaults(e);
        }

        function drop(e) {
            $(this).removeClass("active");
            defaults(e);

            // dataTransfer -> which holds information about the user interaction, including what files (if any) the user dropped on the element to which the event is bound.
            //console.log(e);
            var dt = e.dataTransfer;
            var files = dt.files;

            handleFiles(files, e);
        }

        handleFiles = function (files, e) {
            // alert(files);
            // Traverse throught all files and check if uploaded file type is image
            var imageType = /image.*/;
            var file = files[0];
            // check file type
            if (!file.type.match(imageType)) {
                alert("El archivo \"" + file.name + "\" no es una imagen válida.");
                return false;
            }
            // check file size
            if (parseInt(file.size / 1024) > 2050) {
                alert("La imagen \"" + file.name + "\" es muy grande");
                return false;
            }

            $('#inputImageExtension').val(file.type);

            var info = '<div class="preview active-win"><div class="preview-image"><img ></div><div class="progress-holder"><span id="progress"></span></div><span class="percents"></span><div style="float:left;">Uploaded <span class="up-done"></span> KB of ' + parseInt(file.size / 1024) + ' KB</div>';

            $("#dropbox").hide();
            $("#fileElem").hide();

            $(".upload-progress").show(function () {
                $(".upload-progress").html(info);
                uploadFile(file);
            });

        };

        uploadFile = function (file) {
            // check if browser supports file reader object
            if (typeof FileReader !== "undefined") {
                //alert("uploading "+file.name);

                $('#btnEliminarImagen').show();
                reader = new FileReader();
                reader.onload = function (e) {
                    $('.preview img').attr('src', e.target.result).css("width", "70px").css("height", "70px").css("border-radius","50%");
                }

                reader.readAsDataURL(file);

                xhr = new XMLHttpRequest();
                xhr.open("post", "image_upload.php", true);

                xhr.upload.addEventListener("progress", function (event) {
                    //console.log(event);
                    if (event.lengthComputable) {
                        $("#progress").css("width", (event.loaded / event.total) * 100 + "%");
                        $(".percents").html(" " + ((event.loaded / event.total) * 100).toFixed() + "%");
                        $(".up-done").html((parseInt(event.loaded / 1024)).toFixed(0));
                    }
                    else {
                        alert("Failed to compute file upload length");
                    }
                }, false);

                xhr.onreadystatechange = function (oEvent) {
                    //console.log(oEvent);
                    //console.log(xhr);
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            $("#progress").css("width", "100%");
                            $(".percents").html("100%");
                            $(".up-done").html((parseInt(file.size / 1024)).toFixed(0));
                            //if reach here means the image uploaded ok
                            $("#imgCropDiv").remove();
                            $(".upload-progress").hide();
                            $(".upload-progress").after("<div id=\"imgCropDiv\" style=\"overflow:hidden;\"><img id=\"imgCrop\" src=\"imagen.php?temp_img=" + xhr.responseText + "\" /></div>");
                            //<button type=\"button\" class=\"btn btn-default\" id=\"CancelCrop\" style=\"padding: 6px 12px;margin-top: 10px;\">Cancelar</button>
                            $("#finalImg").attr("src", "imagen.php?temp_img=" + xhr.responseText);
                            $("#inputIMGsrc").attr("value", xhr.responseText);

                            api = $('#imgCrop').Jcrop({
                                onChange: showPreview,
                                onSelect: showPreview,
                                boxWidth: 250,
                                boxHeight: 250,
                                aspectRatio: 1,
                                setSelect: [($('#imgCrop').width() / 2) - 250,
                                    ($('#imgCrop').height() / 2) - 250,
                                    ($('#imgCrop').width() / 2) + 250,
                                    ($('#imgCrop').height() / 2) + 250
                                ]
                            });
                            //$('#CancelCrop').click(function(){
                            //		debugger;
                            //	api.disable();
                            //});

                        } else {
                            alert("Error" + xhr.statusText);
                        }
                    }
                };

                // Set headers
                //xhr.setRequestHeader("Content-Type", "multipart/form-data");
                //xhr.setRequestHeader("Content-type","multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2));

                xhr.setRequestHeader("X-File-Name", file.fileName);
                xhr.setRequestHeader("X-File-Size", file.fileSize);
                xhr.setRequestHeader("X-File-Type", file.type);

                // Send the file (doh)
                xhr.send(file);

            } else {
                alert("Your browser doesnt support FileReader object");
            }
        };


        function showPreview(coords) {
            var maxX = 250;
            var maxY = 250;

            var rx = maxX / coords.w;
            var ry = maxY / coords.h;

            rx = (rx == 0) ? 1 : rx;
            ry = (ry == 0) ? 1 : ry;

            photoX = $("#imgCrop").width();
            photoY = $("#imgCrop").height();

            $("#finalImg").css({
                width: Math.round(rx * photoX) + 'px',
                height: Math.round(ry * photoY) + 'px',
                marginLeft: '-' + Math.round(rx * coords.x) + 'px',
                marginTop: '-' + Math.round(ry * coords.y) + 'px'
            });

            $("#finalImgCamera").css({
                width: Math.round(rx * photoX) + 'px',
                height: Math.round(ry * photoY) + 'px',
                marginLeft: '-' + Math.round(rx * coords.x) + 'px',
                marginTop: '-' + Math.round(ry * coords.y) + 'px'
            });

            $('#inputIMGx').val(coords.x);
            $('#inputIMGy').val(coords.y);
            $('#inputIMGw').val(coords.w);
            $('#inputIMGh').val(coords.h);
        }

        var options = {
            shutter_ogg_url: "https://static.enpuntocontrol.com/app/v1/js/plugin/jpeg-camera/shutter.ogg",
            shutter_mp3_url: "https://static.enpuntocontrol.com/app/v1/js/plugin/jpeg-camera/shutter.mp3",
            swf_url: "https://static.enpuntocontrol.com/app/v1/js/plugin/jpeg-camera/jpeg_camera.swf",
            mirror: true
        };

        // var camera;
        // Elements for taking the snapshot


        $('#botonCamara').click(function () {
            $('#camara').html('<video id="video" width="250"  autoplay></video>');
            $('#camara').show();
            $('#finalImg').hide();
            $('#botonSnapshot').show();
            $('#botonCamara').hide();
            $('#dropbox').hide();
            $('#fileElem').hide();
            $('#btnEliminarImagen').show();
            video = document.getElementById('video');
            $("#imgCropDiv").remove();
            // Get access to the camera!
            if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                // Not adding `{ audio: true }` since we only want video now
                navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
                    video.srcObject = stream;
                    video_stream = stream;
                    video.play();
                });
            }else{
                $('#camara').html('<div >Por favor, permita el acceso a la camara cuando el navegador lo pida. <br><br>Busca el icono de la camara en la barra de direcciones.</div>');
            }
        });

        $('#botonSnapshot').click(function () {
            context.drawImage(video, 0, 0, 333, 250);

            var dataURL = canvas.toDataURL('image/png');
            video_stream.getVideoTracks()[0].stop();
            $('#btnEliminarImagen').show();
            $('#botonSnapshot').hide();
            $('#snapUploadProgress').show();
            $('#submit-editar').prop('disabled', true);
            $('#submit-editar').prop('title', 'Espere a que se termine de procesar la imagen');
            $("#imgCropDiv").remove();

            $.ajax({
                url: "image_upload.php",
                url: "image_upload.php",
                type:"POST",
                data:{
                    'dataURL' :dataURL
                },
                success: function(result){

                    $('#camara').html("<img id=\"finalImgCamera\" src=\"imagen.php?temp_img=" + result + "\" />");

                    $("#finalImgCamera").load(function() {
                        //esto es para asignar los datos de la imagen, una vez que esta se subio al servidor y es descargadaal navegador
                        var img = document.getElementById('finalImgCamera');
                        $('#inputIMGw').val(img.naturalWidth);
                        $('#inputIMGh').val(img.naturalWidth);  //los dos en width para que sea cuadrada
                        $('#inputIMGx').val(0);
                        $('#inputIMGy').val(0);
                        $('#submit-editar').prop('disabled', false);
                        $('#submit-editar').prop('title', '');
                    });


                    $("#inputIMGsrc").attr("value", result);
                    $('#inputImageExtension').val("image/png");
                    $('#botonCamara').show();
                    $('#snapUploadProgress').hide();

                    $(".upload-progress").after("<div id=\"imgCropDiv\" style=\"overflow:hidden;\"><img id=\"imgCrop\" src=\"imagen.php?temp_img=" + result + "\" /></div>");

                    api = $('#imgCrop').Jcrop({
                        onChange: showPreview,
                        onSelect: showPreview,
                        boxWidth: 250,
                        boxHeight: 250,
                        aspectRatio: 1,
                        setSelect: [($('#imgCrop').width() / 2) - 250,
                            ($('#imgCrop').height() / 2) - 250,
                            ($('#imgCrop').width() / 2) + 250,
                            ($('#imgCrop').height() / 2) + 250
                        ]
                    });

                }});

        });

    }

    // DATA VALIDATION
    $(function () {
        // Validation
        $("#editar-form").validate({
            // Rules for form validation
            rules: {
                nombre: {
                    required: true,
                    minlength: 2,
                    maxlength: 50
                },
                apellido: {
                    required: true,
                    minlength: 2,
                    maxlength: 50
                },
                email: {
                    required: false,
                    email: true
                },
                dni: {
                    required: true,
                    minlength: 8,
                    maxlength: 8,
                    remote: {
                        url: "<?php echo 'ajax/' . $Item_Name . 's.html.php' ?>",
                        type: "post",
                        data: {'tipo': 'check', 'tipo_check': 'c_dni', 'id': $("#ItemID").val()},
                        dataFilter: function (data) {
                            if (data.responseText !== "true") {
                                return "\"" + data + "\"";
                            } else {
                                return success;
                            }
                        }
                    }
                },
                legajo: {
                    required: true,
                    minlength: 2,
                    maxlength: 20,
                    remote: {
                        url: "<?php echo 'ajax/' . $Item_Name . 's.html.php' ?>",
                        type: "post",
                        data: {'tipo': 'check', 'tipo_check': 'c_legajo', 'id': $("#ItemID").val()},
                        dataFilter: function (data) {
                            if (data.responseText !== "true") {
                                return "\"" + data + "\"";
                            } else {
                                return success;
                            }
                        }
                    }
                },

                horarioNormId: {
                    required: "#horarioNormId:visible"
                },
                horarioFlexId: {
                    required: "#horarioFlexId:visible"
                },
                horarioRotId: {
                    required: "#horarioRotId:visible"
                },
                horarioMultId: {
                    required: "#horarioMultId:visible"
                } /*,
                'grupo[]': {
                    minlength: 1,
                    required: true
                },
                'equipo[]': {
                    minlength: 1,
                    required: true
                }  */
            },
            // Messages for form validation
            messages: {
                nombre: {
                    required: '<?php echo _('Por favor ingrese el nombre') ?>',
                    minlength: '<?php echo _('El nombre ingresado es muy corto.') ?>',
                    maxlength: '<?php echo _('El nombre ingresado es muy largo.') ?>'
                },
                apellido: {
                    required: '<?php echo _('Por favor ingrese el apellido ') ?>',
                    minlength: '<?php echo _('El apellido ingresado es muy corto.') ?>',
                    maxlength: '<?php echo _('El apellido ingresado es muy largo.') ?>'
                },
                email: {
                    required: '<?php echo _('Por favor ingrese un email') ?>',
                    email: '<?php echo _('Por favor ingrese un email válido') ?>'
                },
                dni: {
                    required: '<?php echo _('Por favor ingrese el DNI') ?>',
                    minlength: '<?php echo _('El DNI es muy corto. Debe ser de 8 caracteres, por ej.: 12346578') ?>',
                    maxlength: '<?php echo _('El DNI es muy largo. Debe ser de 10 caracteres, por ej.: 12345678') ?>'
                },
                tag: {
                    required: '<?php echo _('Por favor ingrese el TAG') ?>',
                    minlength: '<?php echo _('El TAG es muy corto. Debe ser de 10 caracteres, por ej.: 0000ABCDEF') ?>',
                    maxlength: '<?php echo _('El TAG es muy largo. Debe ser de 10 caracteres, por ej.: 0000ABCDEF') ?>'
                },
                horario_tipo: {
                    required: '<?php echo _('Por favor seleccione un tipo de horario de trabajo') ?>'
                },
                horarioNormId: {
                    required: "Seleccione un Horario"
                },
                horarioFlexId: {
                    required: "Seleccione un Horario"
                },
                horarioRotId: {
                    required: "Seleccione un Horario"
                },
                horarioMultId: {
                    required: "Seleccione un Horario"
                },
                'grupo[]': {
                    required: '<?php echo _('Por favor seleccione al menos un grupo') ?>'
                },
                'equipo[]': {
                    required: '<?php echo _('Por favor seleccione al menos un equipo') ?>'
                },
                legajo: {
                    required: '<?php echo _('Por favor ingrese un legajo') ?>',
                    minlength: '<?php echo _('El legajo es muy corto.') ?>',
                    maxlength: '<?php echo _('El legajo es muy largo.') ?>'
                }
            },
            ignore: ':hidden:not("#selGrupo"):not("#selEquipo")',
            // Do not change code below
            errorPlacement: function (error, element) {
                if (element.parent('.div-chosen-select').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });


    function setEliminarImagen(e) {
        $("#finalImg").attr("src", "https://static.enpuntocontrol.com/app/v1/img/avatars/male-big.png");
        $('#inputBorrarImagen').val('true');
        $("#finalImg").css('width',214);
        $("#finalImg").css('height',214);
        $("#finalImg").css('margin-top',0);
        $("#finalImg").css('margin-left',0);
        $('#inputIMGsrc').val('');
        $('#inputIMGw').val(214);
        $('#inputIMGh').val(214);  //los dos en width para que sea cuadrada
        $('#inputIMGx').val(0);
        $('#inputIMGy').val(0);
        $('#imgCropDiv').remove();
        var $el = $('#fileElem');
        $el.wrap('<form>').closest('form').get(0).reset();
        $el.unwrap();
        $('#camara').html('');
        $('#camara').hide();
        $('#botonSnapshot').hide();
        $('#botonCamara').show();
        $('#dropbox').show();
        $('#fileElem').show();
        $('#finalImg').show();
        if(video_stream !==undefined)
            video_stream.getVideoTracks()[0].stop();
        $('#btnEliminarImagen').hide();
        $(video).hide();
        context.clearRect(0, 0, 333, 250);
    }


    $(document).ready(function () {

        document.getElementById("estadoSel1").value      = "<?php echo $o_Persona->getEstado();?>";
        document.getElementById("generoSel1").value      = "<?php echo $o_Persona->getGenero();?>";
        document.getElementById("estadoCivilSel1").value = "<?php echo $o_Persona->getEstadoCivil();?>";
        document.getElementById("talleCamisaSel1").value = "<?php echo $o_Persona->getTalleCamisa();?>";
        document.getElementById("paisSel1").value = "<?php echo $o_Persona->getPais();?>";


        $('#selTipo').change(function () {

            if ($(this).find('option:selected').attr('value') ==<?php echo HORARIO_NORMAL; ?>) {
                $('#sel-horarioNormal').show();
                $('#sel-horarioFlexible').hide();
                $('#sel-horarioRotativo').hide();
                $('#sel-horarioMultiple').hide();
            } else if ($(this).find('option:selected').attr('value') ==<?php echo HORARIO_FLEXIBLE; ?>) {
                $('#sel-horarioNormal').hide();
                $('#sel-horarioFlexible').show();
                $('#sel-horarioRotativo').hide();
                $('#sel-horarioMultiple').hide();
            } else if ($(this).find('option:selected').attr('value') ==<?php echo HORARIO_ROTATIVO; ?>) {
                $('#sel-horarioNormal').hide();
                $('#sel-horarioFlexible').hide();
                $('#sel-horarioRotativo').show();
                $('#sel-horarioMultiple').hide();
            }else if ($(this).find('option:selected').attr('value') ==<?php echo HORARIO_MULTIPLE; ?>) {
                $('#sel-horarioNormal').hide();
                $('#sel-horarioFlexible').hide();
                $('#sel-horarioRotativo').hide();
                $('#sel-horarioMultiple').show();
            } else {
                $('#sel-horarioNormal').hide();
                $('#sel-horarioFlexible').hide();
                $('#sel-horarioRotativo').hide();
                $('#sel-horarioMultiple').hide();
            }
        });
        $('#selTipo').trigger("change");


        $('#submit-editar').click(function () {
            var $form = $('#editar-form');

            if (!$('#editar-form').valid()) {
                return false;
            } else {
                $('#editar').modal('hide');

                function showProcesando() {
                    $('#content').css({opacity: '0.0'}).html("<div style=\"padding:15px;height:75px;\"><h1 class=\"ajax-loading-animation\"><i class=\"fa fa-cog fa-spin\"></i> Procesando...</h1></div>").delay(50).animate({opacity: '1.0'}, 300);
                }
                setTimeout(showProcesando, 300);

                $.ajax({
                    type: $form.attr('method'),
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    async: true,
                    success: function (data, status) {
                        $('#editar').modal('hide');
                        function refreshpage() {
                            $('#content').css({opacity: '0.0'}).html(data).delay(50).animate({opacity: '1.0'}, 300);
                            //$('body').removeData('.modal-content');
                        }
                        setTimeout(refreshpage, 200);
                        // location.reload();
                    }
                });

            }

        });

    });

    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });

    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm'
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);


    $("#fechaContratacion").datepicker({
        //defaultDate: "+1w",
        changeMonth: true,
        dateFormat: "yy-mm-dd",
        changeYear: true,
        numberOfMonths: 1,
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>'
    });


    $("#fechaNacimiento").datepicker({
        //defaultDate: "+1w",
        changeMonth: true,
        dateFormat: "yy-mm-dd",
        changeYear: true,
        yearRange: "-100:+0",
        numberOfMonths: 1,
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>'
    });




    function agregarNota() {

        $("#notas-previas").append(

            '\n' +
            '                <section>\n' +
            '                    <!-- NOTA NUEVA -->\n' +
            '                    <fieldset>\n' +
            '\n' +
            '                        <div class="row">\n' +
            '                            <section class="col col-10" style="width: 100%">\n' +
            '                                <label class="textarea textarea-resizable">\n' +
            '\n' +
            '                                    <i class="icon-prepend fa fa-sticky-note-o"></i>\n' +
            '\n' +
            '                                    <textarea   required\n' +
            '                                                style       =   "resize: none;"\n' +
            '                                                name        =   "not_Body"\n' +
            '                                                rows        =   "5"\n' +
            '                                                placeholder =   "Nueva Nota"\n' +
            '                                                value       = ""></textarea>\n' +
            '\n' +
            '                                </label>\n' +
            '                            </section>\n' +
            '                        </div>\n' +
            '                    </fieldset>\n' +
            '                </section>');

    }



</script>
