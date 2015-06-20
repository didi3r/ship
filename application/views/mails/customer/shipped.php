<?php $this->load->view('mails/header'); ?>

<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">¡Tu paquete ha sido enviado!</div>
<br>

<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">

<p>Buen día <strong><?php echo isset($name) ? $name : '' ?></strong>, <br>
Tu paquete ya ha sido enviado a la dirección que nos proporcionaste. </p>

<?php if(isset($courier) && $courier == 'Estafeta') : ?>
  <p>ESTAFETA<br>
  Código de rastreo: <strong><?php echo isset($track_code) ? $track_code : '' ?></strong></p>

  <p>Para rastrear tu paquete copia el código y dirígete a:<br>
  <a href="http://www.estafeta.com/herramientas/rastreo.aspx">http://www.estafeta.com/herramientas/rastreo.aspx</a><br><br>
  En el cuadro azul, selecciona la opción "Código de rastreo (10 dígitos)" y pega tu código debajo en el cuadro de texto. A continuación haz clic en "RASTREAR".</p>

  <p>Es posible que al rastrear tu envío por primera vez te aparezca la leyenda "No hay información disponible.", esto no quiere decir que el código de rastreo no funcione o que tu envío no haya sido realizado, ESTAFETA tarda unas horas en actualizar su base de datos una vez recibido el paquete en sucursal, así que consúltalo más tarde. En caso de que pasadas 24hr te siga apareciendo el mismo mensaje o tengas algún problema no dudes en contactarnos.</p>

<?php else: ?>

<p>Correos de México <br>
Código de rastreo: <strong><?php echo isset($track_code) ? $track_code : '' ?></strong></p>

<p>Para rastrear tu paquete copia el código y dirígete a:<br>
<a href="http://www.sepomex.gob.mx/ServiciosLinea/Paginas/cemsmexpost.aspx">http://www.sepomex.gob.mx/ServiciosLinea/Paginas/cemsmexpost.aspx</a> <br><br>
Pega tu código en el cuadro de texto "Número de Guía", selecciona "2015" en la lista desplegable y a continuación haz clic en el botón "Buscar".</p>

<p>Es posible que al rastrear tu envío por primera vez te aparezca la leyenda <em>"No se encontró información referente a la guía ***."</em>, esto no quiere decir que el código de rastreo no funcione o que tu envío no haya sido realizado, Correos de México tarda unas horas en actualizar su base de datos una vez recibido el paquete en sucursal, así que consúltalo más tarde. En caso de que pasadas 72hr te siga apareciendo el mismo mensaje o tengas algún problema no dudes en contactarnos.</p>

<?php endif; ?>

<p><em><strong>NOTA:</strong> Los retrasos en la paquetería no son responsabilidad nuestra.</em></p>

<p>Seguimos en contacto <br>
Saludos</p>

</div>

<?php $this->load->view('mails/footer', array('why' => true)); ?>

