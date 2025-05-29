SELECT s.id id, CONCAT_WS('', u.name, u.lastname) 'Usuario', u.mail 'Email', u.phone 'Teléfono', p.title 'Experiencia', c.name 'Cliente', s.collection_status 'Estatus', s.collection_id 'Nro de Operación', s.quantity 'Cantidad', s.price 'Precio Unit', s.price*s.quantity 'Subtotal', s.mercadopago_fee 'Comisión MercadoPago', s.application_fee 'Comisión EstiloSPA', s.added 'Fecha'
FROM spa_sales s
LEFT JOIN spa_clients c ON c.id=s.idclient
LEFT JOIN spa_promos p ON p.id=s.idpromo
LEFT JOIN spa_users u ON u.id=s.iduser
WHERE DATE(s.added)>='2025-01-01' AND DATE(s.added)<='2025-05-07'
ORDER BY s.added DESC