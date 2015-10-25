<?php $this->load->view('mails/header'); ?>

<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">Solicitud de Pago PayPal</div>

<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
<p>Estás a un paso de completar tu compra, revisa que tu pedido esté correcto y sigue las intrucciones para realizar tu pago:</p>

<table style="width:100%;border-spacing:3px;border-collapse:separate;">
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

<br>
<a href="<?php echo $paypal_url ?>" target="_blank" style="background: #71A866; color: #FFF; padding: 10px 15px; text-decoration: none; border-radius: 3px; display: block; width: 200px; margin:0 auto; text-align: center">
	Pagar Ahora
</a>

<p>El pago en línea con tarjeta de crédito o débito se procesará mediante PayPal. PayPal es un servicio que te permite pagar, enviar dinero y aceptar pagos sin revelar tus detalles financieros. PayPal utiliza la más reciente tecnología de cifrado de datos y sistema a prueba de fraudes para mantener tu información segura.<p>

<p>Para completar tu pago sigue estas instrucciones:</p>

<ol>
	<li>Da clic en el botón verde "Pagar Ahora".</li>
	<li>Se abrirá la pagina de Paypal mostrándole el total a pagar (debe coincidir con el total arriba mencionado).</li>
	<li>Si ya tienes una cuenta en PayPal, selecciona la opcion "Pague con su cuenta PayPal" e inicia sesión.</li>
	<li>Si no tienes una cuenta en PayPal, selecciona la opción "Pague con su tarjeta de crédito o débito" o "Abra una cuenta en PayPal", ingresa tus datos y los de tu tarjeta y da clic en "Aceptar y continuar"</li>
	<li>Autoriza el pago</li>
</ol>

<a href="<?php echo $paypal_url ?>" target="_blank" style="background: #71A866; color: #FFF; padding: 10px 15px; text-decoration: none; border-radius: 3px; display: block; width: 200px; margin:0 auto; text-align: center">
	Pagar Ahora
</a>

<p>
Una vez autorizado tu pago te confirmaremos por correo la recepción del mismo y comenzaremos a procesar tu pedido. Si tienes algún problema para pagar puedes mandarnos un correo a <a href="mailto:ventas.nd.fm@gmail.com">ventas.nd.fm@gmail.com</a>.
</p>

</div>

<?php $this->load->view('mails/footer', array('why' => true)); ?>

