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
					</tr>
					<tr>
						<th>
							Hora Inicio
						</th>
                        <td>
							<input type="text" style="width:100px;" name="ASIS_HORAINICIO" id="ASIS_HORAINICIO" value="<?php echo !empty($sol->ASIS_HORAINICIO) ? prepCampoMostrar($sol->ASIS_HORAINICIO) : null ;?>" /> 		
						</td>
						<th>
							Hora Fin
						</th>
						<td> 
							<input type="text" style="width:100px;" name="ASIS_HORAFIN" id="ASIS_HORAFIN" value="<?php echo !empty($sol->ASIS_HORAFIN) ? prepCampoMostrar($sol->ASIS_HORAFIN) : null ;?>" /> 
						</td>
					</tr>
					<?php if($accion=='n'|$accion=='e') : ?>                    
                                <td align="center" colspan="6" class="noclass">
                                <button title="Verifique la información antes de guardar." id="co_grabar" type="submit" ><img src="./imagenes/guardar.png" width="17" height="17"/>Grabar Asistencia</button>
                             </td>
                    <?php endif; ?>
				</table>
            </div>
            <input type="hidden"  name="ASIS_SECUENCIAL" id="ASIS_SECUENCIAL" value="<?php echo !empty($sol->ASIS_SECUENCIAL) ? prepCampoMostrar($sol->ASIS_SECUENCIAL) : 0 ; ?>"  />
        </form>
</div>
