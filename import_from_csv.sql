INSERT INTO sales (
	date,
	name,
	user,
	email,
	package,
	addressee,
	address,
	phone,
	courier,
	track_code,
	shipping_cost,
	shipping_date,
	shipping_status,
	payment_date,
	payment_status,
	total,
	commission,
	raw_material,
	split_earnings,
	status,
	active
)
SELECT
	STR_TO_DATE(`Fecha Compra`, "%e-%b-%Y"),
	SUBSTRING_INDEX(Nombre, '\n', 1) AS Nombre,
	`Usuario ML`,
	Email,
	REPLACE(Producto, '\n', ', ') AS Producto,
	null,
    REPLACE(
		SUBSTR(
			Nombre,
	    	LOCATE('\n', Nombre) + 1,
	      	(CHAR_LENGTH(Nombre) - LOCATE(':',REVERSE(Nombre)) - LOCATE('\n',Nombre))
	    ),
    '"', '') AS Direccion,
	null,
	IF(SUBSTRING(`Código de Rastreo`, 1, 1) = 'M', 'Correos de México', 'Estafeta') AS Paqueteria,
	`Código de Rastreo`,
	REPLACE(REPLACE(`Costo Envio`, '$', ''), ',', ''),
	`Fecha Envio`,
	IF(`Código de Rastreo` <> '', "Enviado", "Pendiente"),
	null,
	IF(`Código de Rastreo` <> '', "Pagado", "Pendiente"),
	REPLACE(REPLACE(Total, '$', ''), ',', ''),
	REPLACE(REPLACE(`Comisión`, '$', ''), ',', ''),
	REPLACE(REPLACE(`Materia Prima`, '$', ''), ',', ''),
	IF(`Aplica Dividendo` = 'SI', 1, 0),
	Pago,
	IF(Pago = "Cancelado", 0, 1)
FROM `table 2`
WHERE `Usuario ML` <> ""
ORDER BY STR_TO_DATE(`Fecha Compra`, "%e-%b-%Y")

UPDATE sales
SET status = 'Finalizado'
WHERE payment_status = 'Pagado'
AND shipping_status = 'Enviado'