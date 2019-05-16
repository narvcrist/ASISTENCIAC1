<div id="accordion">  
        <form id="fjornada">
            <div id="cabecera">
            <table width="99%" id="tjornada" class="formDialog">    
			
					<tr>
					    <th>
							Jornada
						</th>
                        <td>
                            <?php echo $combo_jornada; ?>
                        </td>
                    	<th>
							Hora Inicio
						</th>
						<td>
						<input type="number" min="0" step="1" max="24" style="width:50px;" name="HORA_INICIO" id="HORA_INICIO" value="<?php echo !empty($sol->HORA_INICIO) ? prepCampoMostrar($sol->HORA_INICIO) : 00 ;?>" /> :
						<input type="number" min="0" step="1" max="59" style="width:50px;" name="MINUTO_INICIO" id="MINUTO_INICIO" value="<?php echo !empty($sol->MINUTO_INICIO) ? prepCampoMostrar($sol->MINUTO_INICIO) : 00 ;?>" /> 
						</td>
						<th>
							Hora fin
                        </th>
                        <td>
						<input type="number" min="0" step="1" max="24" style="width:50px;" name="HORA_FIN" id="HORA_FIN" value="<?php echo !empty($sol->HORA_FIN) ? prepCampoMostrar($sol->HORA_FIN) : 00 ;?>" /> :
						<input type="number" min="0" step="1" max="59" style="width:50px;" name="MINUTO_FIN" id="MINUTO_FIN" value="<?php echo !empty($sol->MINUTO_FIN) ? prepCampoMostrar($sol->MINUTO_FIN) : 00 ;?>" />  
                        </td>
						
                    </tr>
						<?php if($accion=='n'|$accion=='e') : ?>                    
                             <td align="center" colspan="6" class="noclass">
                                <button title="Verifique la información antes de guardar." id="co_grabar" type="submit" ><img src="./imagenes/guardar.png" width="17" height="17"/>Grabar Jornada</button>
                             </td>
                    
						<?php endif; ?>
						
                </table>
            </div>
            <input type="hidden"  name="JOR_SECUENCIAL" id="JOR_SECUENCIAL" value="<?php echo !empty($sol->JOR_SECUENCIAL) ? prepCampoMostrar($sol->JOR_SECUENCIAL) : 0 ; ?>"  />
        </form>
</div>
