<?php $this->load->view('mails/header'); ?>

<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">¡Fue un placer atenderte!</div>

<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">

<p>Esperamos que el producto que recibiste sea lo que esperabas, cada día nos esforzamos por ofrecerte un servicio y productos de calidad.</p>

<p>Recuerda que si en un futuro deseas adquirir alguno de nuestros productos puedes contatarnos vía correo eléctronico, mandandonos un mail a la dirección: <a href="mailto:ventas.nd.fm@gmail.com">ventas.nd.fm@gmail.com</a>.</p>

<p>Si nos compraste mediante MercadoLibre ayúdanos a seguir brindado un buen servicio y crecer, tu calificación es muy importante para nosotros, <strong>¡califícanos!</strong>.</p>

<?php //if(isset($is_mp) && $is_mp) : ?>

<p><strong>¿Pagaste con MercadoPago? Por favor, Libera el dinero.</strong></p>

<p>Debido a que pagaste con MercadoPago, por tu seguridad, nosotros no podemos hacer uso del efectivo hasta que tú recibas tu producto y liberes el pago. Te pedimos de favor que liberes el dinero para que podamos hacer uso de él, de lo contrario no podrémos utilizarlo hasta pasados <strong>21 días</strong>.</p>

<p>Si no sabes como liberar el pago sigue las siguientes instrucciones:</p>
<ol>
	<li>
		Dirígete a <a href="https://myaccount.mercadolibre.com.mx">myaccount.mercadolibre.com.mx</a> para ingresar al resumen de tu cuenta en MercadoLibre.
	</li>
	<li>
		Una vez en el ahí, haz click en el enlace <em>"Compras"</em> de la sección <em>"Compras"</em> en el menú principal.
	</li>
	<li>
		Te aparecerá una lista con tus últimas compras, haz click en el enlace <em>"Ver Detalles"</em> en la compra de moringa.
	</li>
	<li>
		Ya en los detalles de la compra, en la parte inferior de la página en la sección <em>"Estado de la Compra"</em> has click en el botón <em>"Calificar"</em>.
	</li>
	<li>
		Sigue los pasos especificando que ya te llegó tu pedido y déjanos en los comentarios como te pareció el servicio y el producto.
	</li>
</ol>
<?php //endif; ?>

<p>Si tienes alguna duda o sugerencia, no dudes en contactarnos.</p>

<p>Estamos para servirte. <br>
Saludos</p>

</div>

<?php $this->load->view('mails/footer', array('why' => true)); ?>

