<?php $this->load->view('mails/header'); ?>

<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">Paquete #<?php echo isset($id) ? $id : '' ?> enviado.</div>
<br>

<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">

El paquete con el número de venta #<?php echo isset($id) ? $id : '' ?> ha sido enviado:<br><br>

<table width="100%" style="border-spacing: 3px;border-collapse: separate;">
	<tr>
		<td style="width:50%;padding: 3px 5px;background-color: #F6F6F6;">Comprador:</td>
		<td style="width:50%;padding: 3px 5px;border:1px solid #F1F1F1"><?php echo isset($name) ? $name : '' ?></td>
	</tr>
	<tr>
		<td style="width:50%;padding: 3px 5px;background-color: #F6F6F6;">Código de Rastreo:</td>
		<td style="width:50%;padding: 3px 5px;border:1px solid #F1F1F1"><?php echo isset($track_code) ? $track_code : '' ?></td>
	</tr>
	<tr>
		<td style="width:50%;padding: 3px 5px;background-color: #F6F6F6;">Paquete:</td>
		<td style="width:50%;padding: 3px 5px;border:1px solid #F1F1F1"><?php echo isset($package) ? $package : '' ?></td>
	</tr>
</table>

</div>

<?php $this->load->view('mails/footer', array('why' => false)); ?>

