USE zdtechlab;
-- Categorias
INSERT INTO categorias(nombre,descripcion) VALUES
('Perifericos','Teclados, mouse, audifonos'),('Pantallas','Monitores y pantallas'),
('Almacenamiento','SSD, HDD, USB'),('Redes','Routers, switches, cables'),('Energia','UPS y reguladores');
-- Usuarios (clave: Clave.2026* para los 3)
-- Hash generado con password_hash('Clave.2026*', PASSWORD_DEFAULT)
INSERT INTO usuarios(nombre,correo,clave_hash,rol) VALUES
('Admin ZD','admin@zdtechlab.co','$2y$10$99oIHRtOX7GWbfm.sHW2FOgDhzwlwqUXE40Qy98C/uQO/abYxvMfi','administrador'),
('Vendedor ZD','vendedor@zdtechlab.co','$2y$10$99oIHRtOX7GWbfm.sHW2FOgDhzwlwqUXE40Qy98C/uQO/abYxvMfi','vendedor'),
('Consultor ZD','consultor@zdtechlab.co','$2y$10$99oIHRtOX7GWbfm.sHW2FOgDhzwlwqUXE40Qy98C/uQO/abYxvMfi','consultor');
-- Clientes (10)
INSERT INTO clientes(documento,nombre,correo,telefono,direccion) VALUES
('1001','Ana Ruiz','ana@mail.co','3001112222','Calle 10 #5-20'),('1002','Luis Perez','luis@mail.co','3002223333','Cra 15 #20-30'),
('1003','Marta Gomez','marta@mail.co','3003334444','Av 68 #40-50'),('1004','Carlos Diaz','carlos@mail.co','3004445555','Calle 80 #10-10'),
('1005','Sofia Lopez','sofia@mail.co','3005556666','Cra 7 #100-20'),('1006','Diego Torres','diego@mail.co','3006667777','Calle 26 #30-40'),
('1007','Laura Mora','laura@mail.co','3007778888','Av Suba #90-10'),('1008','Pedro Sanz','pedro@mail.co','3008889999','Cra 50 #15-25'),
('1009','Elena Castro','elena@mail.co','3009990000','Calle 45 #12-34'),('1010','Jorge Rios','jorge@mail.co','3010001111','Av Cali #60-70');
-- Productos (20)
INSERT INTO productos(nombre,categoria_id,precio,stock,stock_minimo) VALUES
('Teclado mecanico RGB',1,120000,14,5),('Mouse inalambrico',1,65000,32,5),('Audifonos gamer',1,180000,9,5),
('Monitor 24 pulg',2,890000,6,3),('Monitor 27 pulg 4K',2,1450000,4,2),
('SSD 1TB',3,320000,7,5),('SSD 512GB',3,210000,12,5),('USB 64GB',3,45000,40,10),('Disco HDD 2TB',3,280000,3,5),
('Router WiFi 6',4,350000,10,4),('Switch 8 puertos',4,220000,8,4),('Cable UTP 305m',4,400000,5,2),
('UPS 1000VA',5,520000,6,3),('Regulador 2000VA',5,180000,15,5),
('Webcam HD',1,150000,11,5),('Parlante Bluetooth',1,130000,20,5),('Memoria RAM 16GB',3,260000,9,5),
('Patch cord 3m',4,25000,50,10),('Base refrigerante',1,90000,2,5),('Estabilizador',5,140000,7,5);
-- Pedidos (15) + detalle basico
INSERT INTO pedidos(cliente_id,fecha,total,estado,creado_por) VALUES
(1,'2026-03-10 10:00',185000,'confirmado',1),(2,'2026-04-12 11:00',890000,'confirmado',1),
(3,'2026-05-05 09:30',385000,'confirmado',1),(4,'2026-06-08 15:00',670000,'confirmado',1),
(5,'2026-07-11 12:00',450000,'confirmado',1),(6,'2026-07-20 16:00',1200000,'confirmado',1),
(7,'2026-08-02 10:20',320000,'confirmado',1),(8,'2026-08-15 14:00',740000,'confirmado',1),
(9,'2026-09-01 09:00',980000,'confirmado',1),(10,'2026-09-05 11:30',410000,'confirmado',1),
(1,'2026-09-06 13:00',65000,'confirmado',1),(2,'2026-09-07 10:00',240000,'confirmado',2),
(3,'2026-09-08 17:00',890000,'confirmado',2),(4,'2026-09-09 12:00',180000,'anulado',2),
(5,'2026-09-10 09:45',520000,'confirmado',2);
INSERT INTO detalle_pedido(pedido_id,producto_id,cantidad,precio_unitario) VALUES
(1,2,1,65000),(1,8,2,45000),(1,19,1,30000),
(2,4,1,890000),(3,2,1,65000),(3,6,1,320000),
(4,10,1,350000),(4,6,1,320000),(5,7,1,210000),(5,2,1,65000),(5,8,2,45000),(5,19,2,45000),
(6,5,1,1450000),(7,6,1,320000),(8,10,1,350000),(8,7,1,210000),(8,15,1,150000),
(9,4,1,890000),(9,8,2,45000),(10,7,1,210000),(10,16,1,130000),(10,14,1,90000),
(11,2,1,65000),(12,3,1,180000),(12,20,1,60000),(13,4,1,890000),(14,3,1,180000),(15,13,1,520000);
