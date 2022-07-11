<?php

class HtmlHelper {

	/**
	 * Función que permite armar mediante un array los HTML options de un SELECT.
	 * La función también permite seleccionar un item si se especifica el indice.
	 *
	 * @param array $oArrayOptions  El array conteniendo las opciones.
	 * @param mixed $indexSelected Indice del item a seleccionar.
	 * @param bool  $firstEmpty    Especifica si la primera opción del SELECT debe permanecer vacia.
	 * @param bool  $objeto         Se notifica a la funcion si es un objeto o un array lo que se le proporciona
	 * @param string $tipoMostrar   Comentar mas adelante.
	 * @return string Cadena conteniendo las opciones del SELECT.
	 */
	public static function array2htmloptions($oArrayOptions, $indexSelected = null, $firstEmpty = false, $objeto = true, $tipoMostar = '', $comentarioEmpty = '') {
		$emptyOption = '<option value="">' . $comentarioEmpty . '</option>' . PHP_EOL;
		$mostar_t = '';
		$mostar_a = '';
		$title_t = '';

		if (!is_array($oArrayOptions)) {
			return $emptyOption;
		}



		$htmlOptions = '';
		if ($firstEmpty) {
			if ($tipoMostar == 'PersonayRol'){ //  <li class="divider"></li>
				$htmlOptions .= '<option value="TodasLasPersonas">' . _('Todas las Personas') . '</option>' . PHP_EOL;
			}
			elseif ($tipoMostar == 'PersonayGrupoLicencia')
                $htmlOptions .= '<option value="TodasLasPersonas" selected="selected" >' . _('Todas las Personas') . '</option>' . PHP_EOL;


            elseif ($tipoMostar == 'PersonayGrupoFeriado')
				$htmlOptions .= '<option value="TodasLasPersonas">' . _('Todas las Personas') . '</option>' . PHP_EOL;

			elseif ($tipoMostar == 'EquipoNotificacion')
				$htmlOptions .= '<option value="TodosLosEquipos">' . _('Todos los Equipos') . '</option>' . PHP_EOL;

			elseif ($tipoMostar == 'Destinatario'){

				if($indexSelected == 'Usuario'){
                    $htmlOptions .= '<option value="Usuario" selected="selected" >' . _('Usuario') . '</option>' . PHP_EOL;
                }
				else{
                    $htmlOptions .= '<option value="Usuario">' . _('Usuario') . '</option>' . PHP_EOL;
                }


				if($indexSelected == 'Grupo'){
                    $htmlOptions .= '<option value="Grupo" selected="selected" >' . _('Grupo') . '</option>' . PHP_EOL;

                }
				else{
                    $htmlOptions .= '<option value="Grupo">' . _('Grupo') . '</option>' . PHP_EOL;
                }

			}
            elseif ($tipoMostar == 'Alerta'){

                if($indexSelected == 'Grupo'){
                    $htmlOptions .= '<option value="Grupo" selected="selected" >' . _('Grupo') . '</option>' . PHP_EOL;

                }
                else{
                    $htmlOptions .= '<option value="Grupo">' . _('Grupo') . '</option>' . PHP_EOL;
                }

            }
            elseif ($tipoMostar == 'Filtro_Horario'){
                $htmlOptions .= '<option value="">' . _('Todos') . '</option>' . PHP_EOL;
            }
            elseif ($tipoMostar == 'Filtro_Equipo'){
                $htmlOptions .= '<option value="">' . _('Todos') . '</option>' . PHP_EOL;
            }
			else
				$htmlOptions .= $emptyOption;
		}
		if ($tipoMostar == 'PersonayRol' || $tipoMostar == 'PersonayGrupoLicencia' || $tipoMostar == 'PersonayGrupoFeriado' || $tipoMostar == 'PersonayRegistro') {


		    if ($indexSelected == 'SelectRol'   || $indexSelected == 'GrupoPersonas' )
				$htmlOptions .= '<option value="SelectRol" selected="selected" >' . _('Grupo') . '</option>' . PHP_EOL;
			else
				$htmlOptions .= '<option value="SelectRol">' . _('Grupo') . '</option>' . PHP_EOL;
		}
		//die("indexSelected:{$indexSelected}");
		if ($objeto) {//recibe ojetos
				// echo "<pre>".print_r($oArrayOptions)."</pre>";die;
			foreach ($oArrayOptions as $value) {


				switch ($tipoMostar) {
					case 'Equipo':
					case 'EquipoNotificacion':
						//$mostar_t = $mostar_a . htmlentities($value->getHost(), ENT_COMPAT, 'utf-8') . ' - ' . htmlentities($value->getDetalle(), ENT_COMPAT, 'utf-8');
                        $_data= mb_convert_case($value->getDetalle(), MB_CASE_TITLE, "UTF-8");
						$mostar_t = $mostar_a . htmlentities($_data, ENT_COMPAT, 'utf-8');
						break;
					case 'Persona':
					case 'Personas':
					case 'Destinatario':
                    case 'Alerta':
					case 'PersonayRegistro':
					case 'PersonayRol':
					case 'PersonayGrupoLicencia':		
					case 'PersonayGrupoFeriado':

                        $_data= mb_convert_case($value->getLegajo()  . " - ". $value->getApellido() . ", " . $value->getNombre(), MB_CASE_TITLE, "UTF-8");
					    $mostar_t = htmlentities(". ". $_data, MB_CASE_TITLE, 'utf-8');
						break;
					
							
					case 'Persona-NOTIFICACIONES':
                        $_data= mb_convert_case($value->getApellido() . ", " . $value->getNombre() . " - (" . ")", MB_CASE_TITLE, "UTF-8");
						$mostar_t = htmlentities($_data, MB_CASE_TITLE, 'utf-8');
						$title_t = "title=\"" . $value->getEmail() . "\"";
						break;
					case 'Dispositivo':
						if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 99) {
                            $_data= mb_convert_case($value->getEquipoObject()->getDetalle() . ' - ' . $value->getNombre(), MB_CASE_TITLE, "UTF-8");
							$mostar_t = htmlentities($_data, MB_CASE_TITLE, 'utf-8');
						} else {
                            $_data= mb_convert_case($value->getNombre(), MB_CASE_TITLE, "UTF-8");
							$mostar_t = htmlentities($_data, MB_CASE_TITLE, 'utf-8');
						}
						break;
					case 'Not_Persona':
						if ($value->getPersona() == 0){
                            $_data= mb_convert_case($value->getEmail(), MB_CASE_TITLE, "UTF-8");
                            $mostar_t = htmlentities($_data, MB_CASE_TITLE, 'utf-8');
                        }
						else {
							$o_persona = Persona_L::obtenerPorId($value->getPersona(),1);
                            $_data= mb_convert_case($o_persona->getApellido() . ", " . $o_persona->getNombre(), MB_CASE_TITLE, "UTF-8");
                            $mostar_t = htmlentities($_data, MB_CASE_TITLE, 'utf-8');
						}
						break;
					case 'GrupoPersonas':
                        //mb_convert_case($oArrayOptions[$a_key], MB_CASE_TITLE, "UTF-8");
                        $_data= mb_convert_case($value->getDetalle(), MB_CASE_TITLE, "UTF-8");
                        $mostar_t = htmlentities($_data, MB_CASE_TITLE, 'utf-8');
						break;
					default:
                        $_data= mb_convert_case($value->getDetalle(), MB_CASE_TITLE, "UTF-8");
						$mostar_t = htmlentities($_data, MB_CASE_TITLE, 'utf-8');
						break;
				}
				if (!is_null($indexSelected) && $value->getId() == $indexSelected) {
					$htmlOptions .= "\t\t" . '<option ' . $title_t . ' value="' . htmlspecialchars($value->getId(), MB_CASE_TITLE, 'utf-8') . '" selected="selected">' . $mostar_t . '</option>' . PHP_EOL;
				} else {
					$htmlOptions .= "\t\t" . '<option ' . $title_t . ' value="' . htmlspecialchars($value->getId(), MB_CASE_TITLE, 'utf-8') . '">' . $mostar_t . '</option>' . PHP_EOL;
				}
			}
		} else {// recibe array
			foreach ($oArrayOptions as $indice => $valor) {

				switch ($tipoMostar) {

					case 'AccionesLogs':
						if ($indice == 0)
							continue;
						if (!is_null($indexSelected) && $indice == $indexSelected) {
							$htmlOptions .= "\t\t" . '<option value="' . htmlspecialchars($indice, MB_CASE_TITLE, 'utf-8') . '" selected="selected">' . $valor . '</option>' . PHP_EOL;
						} else {
							$htmlOptions .= "\t\t" . '<option value="' . htmlspecialchars($indice, MB_CASE_TITLE, 'utf-8') . '">' . $valor . '</option>' . PHP_EOL;
						}
						break;

					case 'Usuarios':
						if ($indice == 0)
							continue;
						if (!is_null($indexSelected) && $valor['usu_Id'] == $indexSelected) {
							$htmlOptions .= "\t\t" . '<option value="' . htmlspecialchars($valor['usu_Id'], MB_CASE_TITLE, 'utf-8') . '" selected="selected">' . $valor['usu_Nombre'] .' '.$valor['usu_Apellido']. '</option>' . PHP_EOL;
						} else {
							$htmlOptions .= "\t\t" . '<option value="' . htmlspecialchars($valor['usu_Id'], MB_CASE_TITLE, 'utf-8') . '">' . $valor['usu_Nombre'] .' '.$valor['usu_Apellido'] . '</option>' . PHP_EOL;
						}
						break;

					default:
						if (!is_null($indexSelected) && $indice == $indexSelected) {
							$htmlOptions .= "\t\t" . '<option value="' . htmlspecialchars($indice, MB_CASE_TITLE, 'utf-8') . '" selected="selected">' . $valor . '</option>' . PHP_EOL;
						} else {
							$htmlOptions .= "\t\t" . '<option value="' . htmlspecialchars($indice, MB_CASE_TITLE, 'utf-8') . '">' . $valor . '</option>' . PHP_EOL;
						}
						break;
				}
			}
		}

		return $htmlOptions;
	}

	public static function htmlCheckbox($p_Nomb_Var, $a_Options, $a_Indice_Check = null, $p_Objeto = false, $p_TipoMostar = '', $p_Zonas = '', $p_Pagina_Actual = '', $p_Script = '') {
		global $a_Salida;
		global $a_Lector;
		global $a_Pulsador;
		if (!is_array($a_Options)) {
			return false;
		}
		$bandera = 0;
		$htmlOptions = '';
		if ($p_Objeto) {//resibe objetos
			foreach ($a_Options as $key => $valor) {
				switch ($p_TipoMostar) {
					case 'Zona_Equipo':
						$pinta = (($key % 2) != 0) ? ' class="bg" ' : '';

						if ($bandera != $valor->getEqId()) {
							$htmlOptions .= '
						<thead>
							<tr>
								<th colspan="50">' . htmlentities($valor->getEquipoObject()->getDetalle(), ENT_COMPAT, 'utf-8') . '</th>
							</tr>
							<tr class="title_2">
								<td class="tb_5">Salida</td>
								<td class="tb_5">Lector</td>
								<td class="tb_5">Pulsa.</td>
								<td class="tb_20">Detalle Salida</td>';
							foreach ($p_Zonas as $tmp_zona) {
								$htmlOptions .= '<td> Z-' . $tmp_zona->getId() . '</td>';
							}
							$htmlOptions .= '
							</tr>
						</thead>
							 ' . PHP_EOL;
						}
						$bandera = $valor->getEqId();
						$htmlOptions .= "\t\t\t\t\t\t<tr" . $pinta . '><td>';
						$htmlOptions .= ($valor->getSalida() != 0) ? htmlentities($a_Salida[$valor->getSalida()], ENT_COMPAT, 'utf-8') : '----';
						$htmlOptions .= '</td><td>';
						$htmlOptions .= ($valor->getLector() != 0) ? htmlentities($a_Lector[$valor->getLector()], ENT_COMPAT, 'utf-8') : '----';
						$htmlOptions .= '</td><td>';
						$htmlOptions .= ($valor->getPulsador() != 0) ? htmlentities($a_Pulsador[$valor->getPulsador()], ENT_COMPAT, 'utf-8') : '----';
						$htmlOptions .= '</td><td>' . htmlentities($valor->getNombre(), ENT_COMPAT, 'utf-8') . "<br />" . '</td>' . PHP_EOL;
						foreach ($p_Zonas as $tmp_zona) {
							if (!empty($a_Indice_Check) && in_array($valor->getId() . '_' . $tmp_zona->getId(), $a_Indice_Check)) {
								$htmlOptions .= "\t\t\t\t\t\t\t" . '<td><a href="' . $p_Script . '.php?Tipo=Mod&amp;id=' . htmlspecialchars($valor->getId(), ENT_COMPAT, 'utf-8') . '&amp;z=' . htmlspecialchars($tmp_zona->getId(), ENT_COMPAT, 'utf-8') . '" title="Quitar"><img src="' . WEB_ROOT . '/imagen/connect16.ico" alt="Quitar" /></a> </td>' . PHP_EOL;
							} else {
								$htmlOptions .= "\t\t\t\t\t\t\t" . '<td><a href="' . $p_Script . '.php?Tipo=Cre&amp;id=' . htmlspecialchars($valor->getId(), ENT_COMPAT, 'utf-8') . '&amp;z=' . htmlspecialchars($tmp_zona->getId(), ENT_COMPAT, 'utf-8') . '" title="Agregar"><img src="' . WEB_ROOT . '/imagen/connect16_d.ico" alt="Agregar" /></a> </td>' . PHP_EOL;
							}
						}
						$htmlOptions .= "\t\t\t\t\t\t</tr>" . PHP_EOL;
						break;
					case 'Zona_Persona':
						$pinta = (($key % 2) != 0) ? ' class="bg" ' : '';
						$htmlOptions .= '<tr' . $pinta . '><td>' . htmlentities($valor->getLegajo(), ENT_COMPAT, 'utf-8') . '</td><td>' . htmlentities($valor->getDni(), ENT_COMPAT, 'utf-8') . '</td><td>' . htmlentities($valor->getApellido() . ', ' . $valor->getNombre(), ENT_COMPAT, 'utf-8') . '</td>' . PHP_EOL;
						foreach ($p_Zonas as $tmp_zona) {
							if (!empty($a_Indice_Check) && in_array($valor->getId() . '_' . $tmp_zona->getId(), $a_Indice_Check)) {
								$htmlOptions .= "\t\t" . '<td><a href="' . $p_Script . '.php?Tipo=Mod&amp;id=' . htmlspecialchars($valor->getId(), ENT_COMPAT, 'utf-8') . '&amp;z=' . htmlspecialchars($tmp_zona->getId(), ENT_COMPAT, 'utf-8') . '&amp;page=' . $p_Pagina_Actual . '" title="Quitar"><img src="' . WEB_ROOT . '/imagen/unlock.gif" alt="Quitar" /></a> </td>' . PHP_EOL;
							} else {
								$htmlOptions .= "\t\t" . '<td><a href="' . $p_Script . '.php?Tipo=Cre&amp;id=' . htmlspecialchars($valor->getId(), ENT_COMPAT, 'utf-8') . '&amp;z=' . htmlspecialchars($tmp_zona->getId(), ENT_COMPAT, 'utf-8') . '&amp;page=' . $p_Pagina_Actual . '" title="Agregar"><img src="' . WEB_ROOT . '/imagen/unlock_g.gif" alt="Agregar" /></a> </td>' . PHP_EOL;
							}
						}
						$htmlOptions .= '</tr>' . PHP_EOL;

						break;
					case 'Persona_Curso':
						$Total_Page = ceil(PaginadoHelper::getCount('curso_materia') / 10) ;
						$pinta = (($key % 2) != 0) ? ' class="bg" ' : '';
						$htmlOptions .= '<tr' . $pinta . '><td>' . htmlentities($valor->getLegajo(), ENT_COMPAT, 'utf-8') . '</td><td>' . htmlentities($valor->getDni(), ENT_COMPAT, 'utf-8') . '</td><td>' . htmlentities($valor->getApellido() . ', ' . $valor->getNombre(), ENT_COMPAT, 'utf-8') . '</td>' . PHP_EOL;
						
						if ($p_Pagina_Actual>1){
							$htmlOptions .= "\t\t" . '<td> &nbsp; </td>' . PHP_EOL;
						}
						foreach ($p_Zonas as $tmp_zona) {//en este caso la zona seria el curso
							if (!empty($a_Indice_Check) && in_array($valor->getId() . '_' . $tmp_zona->getId(), $a_Indice_Check)) {
								$htmlOptions .= "\t\t" . '<td><a href="' . $p_Script . '.php?Tipo=Mod&amp;id=' . htmlspecialchars($valor->getId(), ENT_COMPAT, 'utf-8') . '&amp;c=' . htmlspecialchars($tmp_zona->getId(), ENT_COMPAT, 'utf-8') . '&amp;page=' . $p_Pagina_Actual . '" title="Quitar"><img src="' . WEB_ROOT . '/imagen/unlock.gif" alt="Quitar" /></a> </td>' . PHP_EOL;
							} else {
								$htmlOptions .= "\t\t" . '<td><a href="' . $p_Script . '.php?Tipo=Cre&amp;id=' . htmlspecialchars($valor->getId(), ENT_COMPAT, 'utf-8') . '&amp;c=' . htmlspecialchars($tmp_zona->getId(), ENT_COMPAT, 'utf-8') . '&amp;page=' . $p_Pagina_Actual . '" title="Agregar"><img src="' . WEB_ROOT . '/imagen/unlock_g.gif" alt="Agregar" /></a> </td>' . PHP_EOL;
							}
						}
						if ($Total_Page >= ($p_Pagina_Actual+1)){
							$htmlOptions .= "\t\t" . '<td> &nbsp; </td>' . PHP_EOL;
						}
						$htmlOptions .= '</tr>' . PHP_EOL;

						break;
					case 'Curso_Aula':
						$Total_Page = ceil(PaginadoHelper::getCount('aula') / 10) ;
						$pinta = (($key % 2) != 0) ? ' class="bg" ' : '';
						$htmlOptions .= '<tr' . $pinta . '><td>' . htmlentities($valor->getDetalle(), ENT_COMPAT, 'utf-8'). ' ('. PersonaCurso_L::obtenerPorCursoCUONT($valor->getId()) .') '. '</td>' . PHP_EOL;
						
						if ($p_Pagina_Actual>1){
							$htmlOptions .= "\t\t" . '<td> &nbsp; </td>' . PHP_EOL;
						}
						foreach ($p_Zonas as $tmp_zona) {//en este caso la zona seria el curso
							if (!empty($a_Indice_Check) && in_array($valor->getId() . '_' . $tmp_zona->getId(), $a_Indice_Check)) {
								$htmlOptions .= "\t\t" . '<td><a href="' . $p_Script . '.php?Tipo=Mod&amp;c=' . htmlspecialchars($valor->getId(), ENT_COMPAT, 'utf-8') . '&amp;a=' . htmlspecialchars($tmp_zona->getId(), ENT_COMPAT, 'utf-8') . '&amp;page=' . $p_Pagina_Actual . '" title="Quitar"><img src="' . WEB_ROOT . '/imagen/unlock.gif" alt="Quitar" /></a> </td>' . PHP_EOL;
							} else {
								$htmlOptions .= "\t\t" . '<td><a href="' . $p_Script . '.php?Tipo=Cre&amp;c=' . htmlspecialchars($valor->getId(), ENT_COMPAT, 'utf-8') . '&amp;a=' . htmlspecialchars($tmp_zona->getId(), ENT_COMPAT, 'utf-8') . '&amp;page=' . $p_Pagina_Actual . '" title="Agregar"><img src="' . WEB_ROOT . '/imagen/unlock_g.gif" alt="Agregar" /></a> </td>' . PHP_EOL;
							}
						}
						if ($Total_Page >= ($p_Pagina_Actual+1)){
							$htmlOptions .= "\t\t" . '<td> &nbsp; </td>' . PHP_EOL;
						}
						$htmlOptions .= '</tr>' . PHP_EOL;

						break;
					default:
						if (!empty($a_Indice_Check) && in_array((integer) $valor->getId(), $a_Indice_Check)) {
							$htmlOptions .= "\t\t" . '<input type="checkbox" name="' . $p_Nomb_Var . '[]" value="' . htmlspecialchars($valor->getId(), ENT_COMPAT, 'utf-8')
									. '" checked="checked" />' . htmlentities($valor->getNumero() . ' - ' . $valor->getNombre(), ENT_COMPAT, 'utf-8') . "<br />" . PHP_EOL;
						} else {
							$htmlOptions .= "\t\t" . '<input type="checkbox" name="' . $p_Nomb_Var . '[]" value="' . htmlspecialchars($valor->getId(), ENT_COMPAT, 'utf-8')
									. '" />' . htmlentities($valor->getNumero() . ' - ' . $valor->getNombre(), ENT_COMPAT, 'utf-8') . "<br />" . PHP_EOL;
						}
						break;
				}
			}
		} else {// resibe array
			foreach ($a_Options as $indice => $valor) {

				if (!empty($a_Indice_Check) && in_array($indice, $a_Indice_Check)) {
					$htmlOptions .= "\t\t" . '<input type="checkbox" name="' . $p_Nomb_Var . '[]" value="' . htmlspecialchars($indice, ENT_COMPAT, 'utf-8') . '" checked="checked" />' . htmlentities($valor, ENT_COMPAT, 'utf-8') . "<br />" . PHP_EOL;
				} else {
					$htmlOptions .= "\t\t" . '<input type="checkbox" name="' . $p_Nomb_Var . '[]" value="' . htmlspecialchars($indice, ENT_COMPAT, 'utf-8') . '" />' . htmlentities($valor, ENT_COMPAT, 'utf-8') . "<br />" . PHP_EOL;
				}
			}
		}
		return $htmlOptions;
	}

	public static function htmlRadio($p_Nomb_Var, $a_Options, $p_Indice_Check = null) {
		if (!is_array($a_Options)) {
			return false;
		}
		$htmlOptions = '';
		if ($a_Options) {//resibe array
			foreach ($a_Options as $indice => $valor) {
				if ($indice == $p_Indice_Check) {
					$htmlOptions .= "\t\t" . '<input type="radio" name="' . $p_Nomb_Var . '" value="' . htmlspecialchars($indice, ENT_COMPAT, 'utf-8') . '" checked="checked" />' . htmlentities($valor, ENT_COMPAT, 'utf-8');
				} else {
					$htmlOptions .= "\t\t" . '<input type="radio" name="' . $p_Nomb_Var . '" value="' . htmlspecialchars($indice, ENT_COMPAT, 'utf-8') . '" />' . htmlentities($valor, ENT_COMPAT, 'utf-8');
				}
			}
			$htmlOptions .= "<br />" . PHP_EOL;
		}
		return $htmlOptions;
	}

	/**
	 * Función que no permite buscar un array de palabras prohibidas dentro de un string
	 *
	 * @param string $pTexto  Texto a ser analizado
	 * @param array $arrayPalabra  El array conteniendo las palabras a restringir.
	 * @return bool Devuelve TRUE cuando encuentra alguna coincidencia y FALSE cunado no.
	 */
	public static function ControlarPalabras($pTexto, $arrayPalabra) {
		foreach ($arrayPalabra as $palabra) {
			if (preg_match("/$palabra/i", $pTexto)) {
				/* el modificador i para que no diferencia entre mayuscula y minuscula */
				return true;
			}
		}
		return false;
	}

}
