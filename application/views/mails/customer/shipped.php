<?php $this->load->view('mails/header'); ?>

<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">¡Tu paquete ha sido enviado!</div>
<br>

<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">

<p>Buen día <strong><?php echo isset($name) ? $name : '' ?></strong>, <br>
Tu paquete ya ha sido enviado a la dirección que nos proporcionaste. A continuación encontrarás tu código de rastreo y un enlace para acceder a las <em>Instrucciones de Rastreo</em>.</p>

<p style="text-align: center; font-size: 14px">
	Codigo de Rastreo
	<span style="display: block; margin: 0 auto; border: 1px solid #CCC; border-radius: 3px; padding: 5px 10px; max-width: 165px">
		<strong><?php echo isset($track_code) ? $track_code : '' ?></strong>
	</span>
</p>

<p style="text-align: center">
	<a href="http://moringa-michoacana.com.mx/como-comprar/instrucciones-de-rastreo/" target="_blank" style="background: #71A866; color: #FFF; padding: 10px 15px; text-decoration: none; border-radius: 3px;">
		Instrucciones de Rastreo
	</a>
</p>
<br>
<p><em><strong>NOTA:</strong> Los retrasos en la paquetería no son responsabilidad nuestra.</em></p>

<p>Seguimos en contacto <br>
Saludos</p>

</div>

<?php $this->load->view('mails/footer', array('why' => true)); ?>

