<?php $this->load->view('mails/header'); ?>

<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">Confirmación de Pago</div>

<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">

<p>Gracias por confiar en nosotros, tu pago ha sido confirmado. Empezaremos a procesar tu pedido, una vez que tu paquete sea enviado recibirás un segundo correo con tu código de rastreo y las instrucciones para que puedas rastrear tu paquete en línea.<p>

<p><strong>Tu compra</strong></p>
</p>A continuación te presentamos un resumen de los detalles de tu compra:</p>

<table style="width:100%;border-spacing:3px;border-collapse:separate;">
	<!-- <tr>
		<td style="width:30%;background-color:#f1f1f1;padding:2px 5px">Número de compra:</td>
		<td style="width:50%;border:1px solid #ddd;padding:2px 5px">#<?php echo $id ?></td>
	</tr> -->
	<tr>
		<td style="width:30%;background-color:#f1f1f1;padding:2px 5px">Nombre:</td>
		<td style="width:50%;border:1px solid #ddd;padding:2px 5px"><?php echo $name ?></td>
	</tr>
	<tr>
		<td style="width:30%;background-color:#f1f1f1;padding:2px 5px;vertical-align:top">
			Producto(s):
		</td>
		<td style="width:50%;border:1px solid #ddd;padding:2px 5px;">
			<?php foreach ($package as $item) : ?>
			<?php echo $item ?> <br>
			<?php endforeach; ?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<table style="width:100%;border-spacing:2px;border-collapse:separate;">
				<tr>
					<td style="width:70%;text-align:right;padding:2px 5px">Subtotal:</td>
					<td style="width:30%;border:1px solid #ddd;padding:2px 5px">
						$<?php echo $subtotal ?>
					</td>
				</tr>
				<tr>
					<td style="width:70%;text-align:right;padding:2px 5px">Envio:</td>
					<td style="width:30%;border:1px solid #ddd;padding:2px 5px">
						$<?php echo $shipment_cost ?>
					</td>
				</tr>
				<tr>
					<td style="width:70%;text-align:right;padding:2px 5px"><strong>Total:</strong></td>
					<td style="width:30%;border:1px solid #ddd;padding:2px 5px">
						<strong>$<?php echo $total ?></strong>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<!-- <p><em>*NOTA:</em> Guarde su número de compra para cualquier aclaración y futura referencia.</p> -->

<?php if(isset($address) && !empty($address)) : ?>
<p><strong>Tu envío</strong></p>

<p>Una vez procesado, tu pedido será enviado a:</p>

<table style="width:100%;border-spacing:3px;border-collapse:separate;">
	<tr>
		<td style="width:30%;background-color:#f1f1f1;padding:2px 5px">Paquetería:</td>
		<td style="width:50%;border:1px solid #ddd;padding:2px 5px"><?php echo $courier ?></td>
	</tr>
	<tr>
		<td style="width:30%;background-color:#f1f1f1;padding:2px 5px">Recibe:</td>
		<td style="width:50%;border:1px solid #ddd;padding:2px 5px"><?php echo $addressee ?></td>
	</tr>
	<tr>
		<td style="width:30%;background-color:#f1f1f1;padding:2px 5px;vertical-align:top">
			Dirección:
		</td>
		<td style="width:50%;border:1px solid #ddd;padding:2px 5px"><?php echo $address ?></td>
	</tr>
</table>
<?php endif; ?>

<p>El tiempo de procesamiento de tu pedido puede tardar de <strong>1 a 3 días hábiles</strong>, si pasado ese tiempo no recibes tu código de rastreo o si tienes alguna duda, ponte en contacto con nosotros.</p>

<p>
Seguimos en contacto. <br>
Saludos
</p>

</div>

<?php $this->load->view('mails/footer', array('why' => true)); ?>

