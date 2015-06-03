CREATE TABLE IF NOT EXISTS `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `user` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `phone` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `package` text CHARACTER SET utf8,
  `addressee` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `address` text,
  `addressee_phone` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `courier` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT 'Estafeta',
  `track_code` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `shipping_cost` float NOT NULL DEFAULT '100',
  `shipping_date` date DEFAULT NULL,
  `shipping_status` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT 'Pendiente',
  `shipping_comments` text CHARACTER SET utf8,
  `payment_date` date DEFAULT NULL,
  `payment_status` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT 'Pendiente',
  `total` float NOT NULL DEFAULT '0',
  `commission` float NOT NULL DEFAULT '0',
  `raw_material` float NOT NULL DEFAULT '0',
  `split_earnings` tinyint(1) NOT NULL DEFAULT '1',
  `status` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT 'Pendiente',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `description` varchar(300) CHARACTER SET utf8 NOT NULL,
  `total` float NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

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
SET addressee = name

UPDATE sales
SET status = 'Finalizado'
WHERE payment_status = 'Pagado'
AND shipping_status = 'Enviado'

UPDATE sales
SET payment_status = 'Pagado'
WHERE status = 'Pagado'
OR shipping_status = 'Enviado'


SELECT * FROM sales
WHERE (status = 'Enviando' AND shipping_status = 'Pendiente')
OR (status = 'En Camino' AND shipping_status = 'Enviado')