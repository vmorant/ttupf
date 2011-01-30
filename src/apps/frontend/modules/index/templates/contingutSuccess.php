<?php 

	slot('opcions');
		echo "Opcions: ";
		foreach($opcions as $opcio):
			echo link_to($opcio->getOpcio()->getNom(), url_for(array('module' => $opcio->getOpcio()->getModule(), 'action' => $opcio->getOpcio()->getAction())))." ";
		endforeach;
	end_slot();
	
	slot('sidebar');
		echo "<br>Sidebar:<br>";
		foreach($continguts as $contingut):
			if($contingut_actual->getId() == $contingut->getId()) {
				echo link_to($contingut->getNom(), 'index_contingut', $contingut, array('class' => 'active'))."<br>";
			}
			else {
				echo link_to($contingut->getNom(), 'index_contingut', $contingut)."<br>";
			}
		endforeach;
	end_slot();
	
	slot('contacte');
		echo "<table>"
				.$form.
		    	"<tr>
		    		<td colspan='2'>
		    			<input type='submit' />
		    		</td>
		    	</tr>
			</table>
		</form>";
	end_slot();
	
	echo "<h1>".$contingut_actual->getNom()."</h1><br>";
	echo "<p>".$contingut_actual->getText()."</p>";
	
	if($contingut_actual->getId() == 1) {
		include_slot('contacte');
	}