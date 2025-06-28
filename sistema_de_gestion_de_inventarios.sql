USE sistema_de_gestion_de_inventarios;

-- CATEGORÍAS
CREATE TABLE IF NOT EXISTS `categorias` (
  `categoria_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` TEXT NULL,
  `cuenta_inventario` TINYINT(1) NOT NULL DEFAULT 1,
  `creado_por` INT UNSIGNED NOT NULL,
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`categoria_id`)
) ENGINE=InnoDB;

-- PROVEEDORES
CREATE TABLE IF NOT EXISTS `proveedores` (
  `proveedor_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_legal` VARCHAR(200) NOT NULL,
  `nombre_comercial` VARCHAR(200) NOT NULL,
  `telefono` VARCHAR(20) NOT NULL,
  `email_contacto` VARCHAR(255) NOT NULL,
  `direccion` TEXT NOT NULL,
  `dias_credito` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `limite_credito` DECIMAL(15,2),
  `esta_activo` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`proveedor_id`)
) ENGINE=InnoDB;

-- USUARIOS
CREATE TABLE IF NOT EXISTS `usuarios` (
  `usuario_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `nombre_completo` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimo_login` DATETIME NULL,
  `esta_activo` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`usuario_id`)
) ENGINE=InnoDB;

-- PRODUCTOS
CREATE TABLE IF NOT EXISTS `productos` (
  `producto_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sku` VARCHAR(50) NOT NULL,
  `nombre` VARCHAR(200) NOT NULL,
  `descripcion` TEXT,
  `categoria_id` INT UNSIGNED NOT NULL,
  `proveedor_id` INT UNSIGNED,
  `unidad_medida` ENUM('pieza', 'metro', 'litro', 'kg') DEFAULT 'pieza',
  `costo_promedio` DECIMAL(15,4) DEFAULT 0.0000,
  `precio_venta` DECIMAL(15,2) DEFAULT 0.00,
  `stock_minimo` INT NOT NULL DEFAULT 0,
  `stock_maximo` INT,
  `controla_inventario` TINYINT(1) DEFAULT 1,
  `es_perecedero` TINYINT(1) DEFAULT 0,
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `creado_por` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`producto_id`),
  FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`categoria_id`),
  FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`proveedor_id`),
  FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`)
) ENGINE=InnoDB;

-- ORDENES
CREATE TABLE IF NOT EXISTS `ordenes` (
  `id_orden` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_producto` VARCHAR(200) NOT NULL,
  `producto_id` VARCHAR(50) NOT NULL,
  `categoria` VARCHAR(100),
  `unidad` VARCHAR(50),
  `precio_compra` DECIMAL(10,2),
  `cantidad` INT,
  `fecha_entrega` DATE,
  `estado` ENUM('confirmado', 'retrasado', 'en-entrega', 'regresado') DEFAULT 'confirmado',
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `producto_ref_id` INT UNSIGNED NOT NULL,
  `categoria_ref_id` INT UNSIGNED NOT NULL,
  `proveedor_ref_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id_orden`),
  FOREIGN KEY (`producto_ref_id`) REFERENCES `productos` (`producto_id`),
  FOREIGN KEY (`categoria_ref_id`) REFERENCES `categorias` (`categoria_id`),
  FOREIGN KEY (`proveedor_ref_id`) REFERENCES `proveedores` (`proveedor_id`)
) ENGINE=InnoDB;

-- DETALLE VENTAS
CREATE TABLE IF NOT EXISTS `detalle_ventas` (
  `id_detalle_ventas` INT NOT NULL AUTO_INCREMENT,
  `cantidad` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `precio_unitario` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id_detalle_ventas`)
) ENGINE=InnoDB;

-- REPORTE VENTAS
CREATE TABLE IF NOT EXISTS `reporte_ventas` (
  `id_reporte` INT NOT NULL AUTO_INCREMENT,
  `tipo_reporte` ENUM('mensual', 'anual', 'trimestral', 'personalizado') NOT NULL,
  `ventas_totales` DECIMAL(10,2) NOT NULL,
  `cantidad_ventas` INT UNSIGNED NOT NULL,
  `mejor_vendido` VARCHAR(150) NOT NULL,
  `menos_vendido` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id_reporte`)
) ENGINE=InnoDB;

-- VENTAS
CREATE TABLE IF NOT EXISTS `ventas` (
  `id_ventas` INT NOT NULL AUTO_INCREMENT,
  `fecha_venta` TIMESTAMP NOT NULL,
  `total_venta` DECIMAL(10,2) NOT NULL,
  `margen_ganancia` DECIMAL(10,2) NOT NULL,
  `medio_pago` ENUM('efectivo', 'tarjeta', 'transferencia') NOT NULL,
  `canal_venta` ENUM('tienda fisica', 'online', 'evento/feria') NOT NULL,
  `estado` ENUM('completada', 'pendiente', 'cancelada') NOT NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  `detalle_id` INT NOT NULL,
  `reporte_id` INT NOT NULL,
  PRIMARY KEY (`id_ventas`),
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`),
  FOREIGN KEY (`detalle_id`) REFERENCES `detalle_ventas` (`id_detalle_ventas`),
  FOREIGN KEY (`reporte_id`) REFERENCES `reporte_ventas` (`id_reporte`)
) ENGINE=InnoDB;

-- CLIENTES
CREATE TABLE IF NOT EXISTS `clientes` (
  `id_clientes` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `apellido` VARCHAR(100) NOT NULL,
  `correo_electronico` VARCHAR(255),
  `telefono` VARCHAR(15),
  `direccion` TEXT,
  `tipo_cliente` ENUM('Frecuente', 'Nuevo', 'VIP'),
  `venta_id` INT NOT NULL,
  PRIMARY KEY (`id_clientes`),
  FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id_ventas`)
) ENGINE=InnoDB;

-- INVENTARIO GENERAL
CREATE TABLE IF NOT EXISTS `inventario` (
  `id_inventario` INT NOT NULL AUTO_INCREMENT,
  `movimiento` ENUM('entrada', 'salida', 'ajuste manual') NOT NULL,
  `cantidad` SMALLINT UNSIGNED NOT NULL,
  `fecha_movimiento` DATETIME NOT NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  `observaciones` TEXT,
  PRIMARY KEY (`id_inventario`),
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`)
) ENGINE=InnoDB;

-- Las tablas de ubicaciones, movimientos y transacciones ya las tienes correctamente creadas (según la imagen).



-- -----------------------------------------------------
-- Table `mydb`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`usuarios` (
  `id_usuarios` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `apellido` VARCHAR(100) NOT NULL,
  `correo_electronico` VARCHAR(255) NOT NULL,
  `contraseña` VARCHAR(255) NOT NULL,
  `fecha_registro` TIMESTAMP(6) NULL DEFAULT NULL,
  `ordenes_id_orden` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id_usuarios`, `ordenes_id_orden`),
  UNIQUE INDEX `correo_electronico_UNIQUE` (`correo_electronico` ASC) VISIBLE,
  INDEX `fk_usuarios_ordenes1_idx` (`ordenes_id_orden` ASC) VISIBLE,
  CONSTRAINT `fk_usuarios_ordenes1`
    FOREIGN KEY (`ordenes_id_orden`)
    REFERENCES `mydb`.`ordenes` (`id_orden`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `mydb`.`ventas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`ventas` (
  `id_ventas` INT NOT NULL AUTO_INCREMENT,
  `fecha_venta` TIMESTAMP NOT NULL,
  `total_venta` DECIMAL(10,2) NOT NULL,
  `margen_ganancia` DECIMAL(10,2) NOT NULL,
  `medio_pago` ENUM('efectivo', 'tarjeta', 'transferencia') NOT NULL,
  `canal_venta` ENUM('tienda fisica', 'online', 'evento/feria') NOT NULL,
  `estado` ENUM('completada', 'pendiente', 'cancelada') NOT NULL,
  `usuarios_id_usuarios` INT NOT NULL,
  `detalle_ventas_id_detalle_ventas` INT NOT NULL,
  `reporte_ventas_id_reporte` INT NOT NULL,
  PRIMARY KEY (`id_ventas`, `usuarios_id_usuarios`, `detalle_ventas_id_detalle_ventas`, `reporte_ventas_id_reporte`),
  INDEX `fk_ventas_usuarios1_idx` (`usuarios_id_usuarios` ASC) VISIBLE,
  INDEX `fk_ventas_detalle_ventas1_idx` (`detalle_ventas_id_detalle_ventas` ASC) VISIBLE,
  INDEX `fk_ventas_reporte_ventas1_idx` (`reporte_ventas_id_reporte` ASC) VISIBLE,
  CONSTRAINT `fk_ventas_detalle_ventas1`
    FOREIGN KEY (`detalle_ventas_id_detalle_ventas`)
    REFERENCES `mydb`.`detalle_ventas` (`id_detalle_ventas`),
  CONSTRAINT `fk_ventas_reporte_ventas1`
    FOREIGN KEY (`reporte_ventas_id_reporte`)
    REFERENCES `mydb`.`reporte_ventas` (`id_reporte`),
  CONSTRAINT `fk_ventas_usuarios1`
    FOREIGN KEY (`usuarios_id_usuarios`)
    REFERENCES `mydb`.`usuarios` (`id_usuarios`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `mydb`.`clientes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`clientes` (
  `id_clientes` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `apellido` VARCHAR(100) NOT NULL,
  `correo_electronico` VARCHAR(255) NULL DEFAULT NULL,
  `telefono` VARCHAR(15) NULL DEFAULT NULL,
  `direccion` TEXT NULL DEFAULT NULL,
  `tipo_cliente` ENUM('Frecuente', 'Nuevo', 'VIP') NULL DEFAULT NULL,
  `ventas_id_ventas` INT NOT NULL,
  `ventas_usuarios_id_usuarios` INT NOT NULL,
  `ventas_detalle_ventas_id_detalle_ventas` INT NOT NULL,
  `ventas_reporte_ventas_id_reporte` INT NOT NULL,
  PRIMARY KEY (`id_clientes`, `ventas_id_ventas`, `ventas_usuarios_id_usuarios`, `ventas_detalle_ventas_id_detalle_ventas`, `ventas_reporte_ventas_id_reporte`),
  UNIQUE INDEX `correo_electronico_UNIQUE` (`correo_electronico` ASC) VISIBLE,
  INDEX `fk_clientes_ventas1_idx` (`ventas_id_ventas` ASC, `ventas_usuarios_id_usuarios` ASC, `ventas_detalle_ventas_id_detalle_ventas` ASC, `ventas_reporte_ventas_id_reporte` ASC) VISIBLE,
  CONSTRAINT `fk_clientes_ventas1`
    FOREIGN KEY (`ventas_id_ventas` , `ventas_usuarios_id_usuarios` , `ventas_detalle_ventas_id_detalle_ventas` , `ventas_reporte_ventas_id_reporte`)
    REFERENCES `mydb`.`ventas` (`id_ventas` , `usuarios_id_usuarios` , `detalle_ventas_id_detalle_ventas` , `reporte_ventas_id_reporte`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `mydb`.`inventario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`inventario` (
  `id_inventario` INT NOT NULL AUTO_INCREMENT,
  `movimiento` ENUM('entrada', 'salida', 'ajuste manual') NOT NULL,
  `cantidad` SMALLINT UNSIGNED NOT NULL,
  `fecha_movimiento` DATETIME NOT NULL,
  `usuarios_id_usuarios` INT NOT NULL,
  `observaciones` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_inventario`, `usuarios_id_usuarios`),
  INDEX `fk_inventario_usuarios1_idx` (`usuarios_id_usuarios` ASC) VISIBLE,
  CONSTRAINT `fk_inventario_usuarios1`
    FOREIGN KEY (`usuarios_id_usuarios`)
    REFERENCES `mydb`.`usuarios` (`id_usuarios`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;

USE `sistema_de_gestion_de_inventarios` ;

-- -----------------------------------------------------
-- Table `sistema_de_gestion_de_inventarios`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_de_gestion_de_inventarios`.`usuarios` (
  `usuario_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL COMMENT 'Almacenamiento seguro con bcrypt',
  `nombre_completo` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimo_login` DATETIME NULL DEFAULT NULL,
  `esta_activo` TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`usuario_id`),
  UNIQUE INDEX `idx_username_unique` (`username` ASC) VISIBLE,
  UNIQUE INDEX `idx_email_unique` (`email` ASC) VISIBLE,
  INDEX `idx_usuario_activo` (`esta_activo` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci
ROW_FORMAT = COMPRESSED
KEY_BLOCK_SIZE = 8;


-- -----------------------------------------------------
-- Table `sistema_de_gestion_de_inventarios`.`categorias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_de_gestion_de_inventarios`.`categorias` (
  `categoria_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` TEXT NULL DEFAULT NULL,
  `cuenta_inventario` TINYINT(1) NOT NULL DEFAULT '1',
  `creado_por` INT UNSIGNED NOT NULL,
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`categoria_id`),
  UNIQUE INDEX `idx_nombre_categoria` (`nombre` ASC) VISIBLE,
  INDEX `fk_categoria_creador` (`creado_por` ASC) VISIBLE,
  CONSTRAINT `fk_categoria_creador`
    FOREIGN KEY (`creado_por`)
    REFERENCES `sistema_de_gestion_de_inventarios`.`usuarios` (`usuario_id`)
    ON DELETE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `sistema_de_gestion_de_inventarios`.`proveedores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_de_gestion_de_inventarios`.`proveedores` (
  `proveedor_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_legal` VARCHAR(200) NOT NULL,
  `nombre_comercial` VARCHAR(200) NOT NULL,
  `rfc` VARCHAR(20) NULL DEFAULT NULL,
  `direccion` TEXT NOT NULL,
  `telefono` VARCHAR(20) NOT NULL,
  `email_contacto` VARCHAR(255) NOT NULL,
  `dias_credito` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  `limite_credito` DECIMAL(15,2) NULL DEFAULT NULL,
  `esta_activo` TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`proveedor_id`),
  INDEX `idx_proveedor_activo` (`esta_activo` ASC) VISIBLE,
  INDEX `idx_nombre_comercial` (`nombre_comercial` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `sistema_de_gestion_de_inventarios`.`productos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_de_gestion_de_inventarios`.`productos` (
  `producto_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sku` VARCHAR(50) NOT NULL COMMENT 'Stock Keeping Unit',
  `upc` VARCHAR(20) NULL DEFAULT NULL COMMENT 'Código de barras',
  `nombre` VARCHAR(200) NOT NULL,
  `descripcion` TEXT NULL DEFAULT NULL,
  `categoria_id` INT UNSIGNED NOT NULL,
  `proveedor_id` INT UNSIGNED NULL DEFAULT NULL,
  `unidad_medida` ENUM('pieza', 'metro', 'litro', 'kg') NOT NULL DEFAULT 'pieza',
  `costo_promedio` DECIMAL(15,4) NOT NULL DEFAULT '0.0000',
  `precio_venta` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
  `stock_minimo` INT NOT NULL DEFAULT '0',
  `stock_maximo` INT NULL DEFAULT NULL,
  `controla_inventario` TINYINT(1) NOT NULL DEFAULT '1',
  `es_perecedero` TINYINT(1) NOT NULL DEFAULT '0',
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creado_por` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`producto_id`),
  UNIQUE INDEX `idx_sku_unique` (`sku` ASC) VISIBLE,
  UNIQUE INDEX `idx_upc_unique` (`upc` ASC) VISIBLE,
  INDEX `idx_producto_categoria` (`categoria_id` ASC) VISIBLE,
  INDEX `idx_producto_proveedor` (`proveedor_id` ASC) VISIBLE,
  INDEX `fk_producto_creador` (`creado_por` ASC) VISIBLE,
  FULLTEXT INDEX `idx_busqueda_producto` (`nombre`, `descripcion`) VISIBLE,
  CONSTRAINT `fk_producto_categoria`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `sistema_de_gestion_de_inventarios`.`categorias` (`categoria_id`)
    ON DELETE RESTRICT,
  CONSTRAINT `fk_producto_creador`
    FOREIGN KEY (`creado_por`)
    REFERENCES `sistema_de_gestion_de_inventarios`.`usuarios` (`usuario_id`)
    ON DELETE RESTRICT,
  CONSTRAINT `fk_producto_proveedor`
    FOREIGN KEY (`proveedor_id`)
    REFERENCES `sistema_de_gestion_de_inventarios`.`proveedores` (`proveedor_id`)
    ON DELETE SET NULL)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `sistema_de_gestion_de_inventarios`.`ubicaciones`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_de_gestion_de_inventarios`.`ubicaciones` (
  `ubicacion_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(20) NOT NULL COMMENT 'Código de estantería/rack',
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` TEXT NULL DEFAULT NULL,
  `es_almacen_principal` TINYINT(1) NOT NULL DEFAULT '0',
  `capacidad` INT UNSIGNED NULL DEFAULT NULL COMMENT 'En unidades genéricas',
  PRIMARY KEY (`ubicacion_id`),
  UNIQUE INDEX `idx_codigo_ubicacion` (`codigo` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `sistema_de_gestion_de_inventarios`.`inventario_ubicaciones`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_de_gestion_de_inventarios`.`inventario_ubicaciones` (
  `registro_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `producto_id` INT UNSIGNED NOT NULL,
  `ubicacion_id` INT UNSIGNED NOT NULL,
  `cantidad` INT NOT NULL DEFAULT '0',
  `cantidad_reservada` INT NOT NULL DEFAULT '0',
  `ultima_actualizacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ordenes_id_orden` INT UNSIGNED NOT NULL,
  `ordenes_productos_id_productos` INT NOT NULL,
  `ordenes_productos_categorias_id_categorias` INT NOT NULL,
  `ordenes_productos_proveedores_id_proveedores` INT NOT NULL,
  PRIMARY KEY (`registro_id`, `ordenes_id_orden`, `ordenes_productos_id_productos`, `ordenes_productos_categorias_id_categorias`, `ordenes_productos_proveedores_id_proveedores`),
  UNIQUE INDEX `idx_producto_ubicacion` (`producto_id` ASC, `ubicacion_id` ASC) VISIBLE,
  INDEX `idx_inventario_ubicacion` (`ubicacion_id` ASC) VISIBLE,
  INDEX `fk_inventario_ubicaciones_ordenes1_idx` (`ordenes_id_orden` ASC, `ordenes_productos_id_productos` ASC, `ordenes_productos_categorias_id_categorias` ASC, `ordenes_productos_proveedores_id_proveedores` ASC) VISIBLE,
  CONSTRAINT `fk_inventario_producto`
    FOREIGN KEY (`producto_id`)
    REFERENCES `sistema_de_gestion_de_inventarios`.`productos` (`producto_id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_inventario_ubicacion`
    FOREIGN KEY (`ubicacion_id`)
    REFERENCES `sistema_de_gestion_de_inventarios`.`ubicaciones` (`ubicacion_id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_inventario_ubicaciones_ordenes1`
    FOREIGN KEY (`ordenes_id_orden` , `ordenes_productos_id_productos` , `ordenes_productos_categorias_id_categorias` , `ordenes_productos_proveedores_id_proveedores`)
    REFERENCES `mydb`.`ordenes` (`id_orden` , `productos_id_productos` , `productos_categorias_id_categorias` , `productos_proveedores_id_proveedores`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `sistema_de_gestion_de_inventarios`.`movimientos_inventario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_de_gestion_de_inventarios`.`movimientos_inventario` (
  `movimiento_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `producto_id` INT UNSIGNED NOT NULL COMMENT 'Referencia al producto',
  `ubicacion_id` INT UNSIGNED NOT NULL COMMENT 'Ubicación física del inventario',
  `tipo_movimiento` ENUM('entrada', 'salida', 'ajuste', 'transferencia', 'venta', 'compra', 'produccion', 'consumo') NOT NULL COMMENT 'Tipo de movimiento de inventario',
  `cantidad` INT NOT NULL COMMENT 'Cantidad movida (positiva para entradas, negativa para salidas)',
  `referencia_id` VARCHAR(50) NULL DEFAULT NULL COMMENT 'ID de documento relacionado (factura, orden, etc.)',
  `usuario_id` INT UNSIGNED NOT NULL COMMENT 'Usuario que registró el movimiento',
  `fecha_movimiento` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora del movimiento',
  `comentarios` TEXT NULL DEFAULT NULL COMMENT 'Observaciones adicionales',
  `costo_unitario` DECIMAL(15,4) NULL DEFAULT NULL COMMENT 'Costo unitario al momento del movimiento',
  PRIMARY KEY (`movimiento_id`),
  INDEX `idx_movimiento_producto` (`producto_id` ASC) VISIBLE,
  INDEX `idx_movimiento_fecha` (`fecha_movimiento` ASC) VISIBLE,
  INDEX `idx_movimiento_referencia` (`referencia_id` ASC) VISIBLE,
  INDEX `idx_movimiento_tipo` (`tipo_movimiento` ASC) VISIBLE,
  INDEX `fk_movimiento_ubicacion` (`ubicacion_id` ASC) VISIBLE,
  INDEX `fk_movimiento_usuario` (`usuario_id` ASC) VISIBLE,
  CONSTRAINT `fk_movimiento_producto`
    FOREIGN KEY (`producto_id`)
    REFERENCES `sistema_de_gestion_de_inventarios`.`productos` (`producto_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_movimiento_ubicacion`
    FOREIGN KEY (`ubicacion_id`)
    REFERENCES `sistema_de_gestion_de_inventarios`.`ubicaciones` (`ubicacion_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_movimiento_usuario`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `sistema_de_gestion_de_inventarios`.`usuarios` (`usuario_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci
COMMENT = 'Registro de movimientos de inventario';


-- -----------------------------------------------------
-- Table `sistema_de_gestion_de_inventarios`.`transacciones_inventario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_de_gestion_de_inventarios`.`transacciones_inventario` (
  `transaccion_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `movimiento_id` BIGINT UNSIGNED NOT NULL,
  `producto_id` INT UNSIGNED NOT NULL,
  `fecha_transaccion` DATETIME NOT NULL,
  `tipo` ENUM('entrada', 'salida') NOT NULL,
  `cantidad` INT NOT NULL,
  `costo_unitario` DECIMAL(15,4) NOT NULL,
  `costo_total` DECIMAL(15,2) NOT NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`transaccion_id`),
  UNIQUE INDEX `idx_transaccion_movimiento` (`movimiento_id` ASC) VISIBLE,
  INDEX `idx_transaccion_producto` (`producto_id` ASC) VISIBLE,
  INDEX `idx_transaccion_fecha` (`fecha_transaccion` ASC) VISIBLE,
  INDEX `fk_transaccion_usuario` (`usuario_id` ASC) VISIBLE,
  CONSTRAINT `fk_transaccion_movimiento`
    FOREIGN KEY (`movimiento_id`)
    REFERENCES `sistema_de_gestion_de_inventarios`.`movimientos_inventario` (`movimiento_id`)
    ON DELETE RESTRICT,
  CONSTRAINT `fk_transaccion_producto`
    FOREIGN KEY (`producto_id`)
    REFERENCES `sistema_de_gestion_de_inventarios`.`productos` (`producto_id`)
    ON DELETE RESTRICT,
  CONSTRAINT `fk_transaccion_usuario`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `sistema_de_gestion_de_inventarios`.`usuarios` (`usuario_id`)
    ON DELETE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
