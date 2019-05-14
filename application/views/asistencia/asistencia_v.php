<div id="accordion">
        <form id="fasistencia">
            <div id="cabecera">
            <table width="99%" id="tasistencia" class="formDialog">    
					<tr>
						<th>
							Persona
						</th>
                        <td>
							<?php echo $combo_persona; ?>
						</td>
					    <th>
							Horario
						</th>
                        <td>
							<?php echo $combo_horario; ?>
						</td>
						<th>
							Hora Inicio
						</th>
                        <td>
							<input type="number" min="0" step="1" max="24" style="width:50px;" name="HORA_INICIO" id="HORA_INICIO" value="<?php echo !empty($sol->HORA_INICIO) ? prepCampoMostrar($sol->HORA_INICIO) : 0 ;?>" /> :
							<input type="number" min="0" step="1" max="59" style="width:50px;" name="MINUTO_INICIO" id="MINUTO_INICIO" value="<?php echo !empty($sol->MINUTO_INICIO) ? prepCampoMostrar($sol->MINUTO_INICIO) : 0 ;?>" /> 		
						</td>
						<th>
							Hora Fin
						</th>
						<td> 
						<input type="number" min="0" step="1" max="24" style="width:50px;" name="HORA_FIN" id="HORA_FIN" value="<?php echo !empty($sol->HORA_FIN) ? prepCampoMostrar($sol->HORA_FIN) : 0 ;?>" /> :
						<input type="number" min="0" step="1" max="59" style="width:50px;" name="MINUTO_FIN" id="MINUTO_FIN" value="<?php echo !empty($sol->MINUTO_FIN) ? prepCampoMostrar($sol->MINUTO_FIN) : 0 ;?>" /> 
						</td>
					</tr>
					<?php if($accion=='n'|$accion=='e') : ?>                    
                                <td align="center" colspan="10" class="noclass">
                                <button title="Verifique la información antes de guardar." id="co_grabar" type="submit" ><img src="./imagenes/guardar.png" width="17" height="17"/>Grabar Asistencia</button>
                             </td>
                    <?php endif; ?>
				</table>
            </div>
            <input type="hidden"  name="ASIS_SECUENCIAL" id="ASIS_SECUENCIAL" value="<?php echo !empty($sol->ASIS_SECUENCIAL) ? prepCampoMostrar($sol->ASIS_SECUENCIAL) : 0 ; ?>"  />
        </form>
</div>
