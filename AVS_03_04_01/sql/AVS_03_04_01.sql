-- ============================================================
-- AVS.03.04.01 Página de gestión de productos
-- Script de creación de la base de datos AVS_03_04_01
-- Uso: phpMyAdmin > Importar, o  mysql -u root < AVS_03_04_01.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS AVS_03_04_01
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE AVS_03_04_01;

-- Tabla de categorías de producto
CREATE TABLE IF NOT EXISTS categorias (
  id_categoria INT(8)      NOT NULL AUTO_INCREMENT,
  descripcion  VARCHAR(30) NOT NULL,
  PRIMARY KEY (id_categoria)
) ENGINE=InnoDB;

-- Tabla de productos (cada producto pertenece a una categoría)
CREATE TABLE IF NOT EXISTS productos (
  id_producto  INT(11)      NOT NULL AUTO_INCREMENT,
  nombre       VARCHAR(30)  NOT NULL,
  precio       DOUBLE       NOT NULL,
  stock        INT          NOT NULL DEFAULT 0,
  foto_p       VARCHAR(100) NOT NULL,
  categoria_id INT(8)       NOT NULL,
  PRIMARY KEY (id_producto),
  KEY idx_categoria (categoria_id),
  CONSTRAINT fk_producto_categoria FOREIGN KEY (categoria_id)
    REFERENCES categorias (id_categoria)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Datos iniciales de categorías (necesarios para el formulario)
INSERT INTO categorias (descripcion) VALUES
  ('Electrónica'),
  ('Ropa'),
  ('Hogar'),
  ('Alimentos'),
  ('Papelería');
