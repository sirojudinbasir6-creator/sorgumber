CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  role VARCHAR(50) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(120) NULL,
  phone VARCHAR(30) NOT NULL,
  city VARCHAR(120) NOT NULL,
  address TEXT NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  sku VARCHAR(60) NOT NULL UNIQUE,
  price DECIMAL(12, 2) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  status VARCHAR(30) NOT NULL DEFAULT 'Aktif',
  description TEXT NULL,
  image_path VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_code VARCHAR(30) NOT NULL UNIQUE,
  customer_id INT NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Menunggu',
  courier VARCHAR(30) NOT NULL,
  subtotal DECIMAL(12, 2) NOT NULL,
  shipping_fee DECIMAL(12, 2) NOT NULL,
  discount DECIMAL(12, 2) NOT NULL,
  total DECIMAL(12, 2) NOT NULL,
  notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_customer FOREIGN KEY (customer_id) REFERENCES customers(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(12, 2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  sender_name VARCHAR(120) NOT NULL,
  bank_name VARCHAR(80) NOT NULL,
  amount DECIMAL(12, 2) NOT NULL,
  proof_path VARCHAR(255) NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_payments_order FOREIGN KEY (order_id) REFERENCES orders(id)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE faqs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  question VARCHAR(255) NOT NULL,
  answer TEXT NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Aktif',
  sort_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, role, password_hash, status) VALUES
  ('Alya Pratama', 'alya@sorgumhub.id', 'Super Admin', '$2y$10$uiZ1QCFmx11PtrqD4SkCEuX8oQLkJ1nxIhR9afvmUsA8IuABohyHy', 'Aktif'),
  ('Rafi Setiawan', 'rafi@sorgumhub.id', 'Admin Operasional', '$2y$10$uiZ1QCFmx11PtrqD4SkCEuX8oQLkJ1nxIhR9afvmUsA8IuABohyHy', 'Aktif'),
  ('Nina Hapsari', 'nina@sorgumhub.id', 'Admin CS', '$2y$10$uiZ1QCFmx11PtrqD4SkCEuX8oQLkJ1nxIhR9afvmUsA8IuABohyHy', 'Pending');

INSERT INTO products (name, sku, price, stock, status, description) VALUES
  ('Sorgum Starter Pack 250g', 'SG-250', 49000, 120, 'Aktif', 'Paket mencoba untuk pemula.'),
  ('Sorgum Family Pack 1kg', 'SG-1K', 159000, 68, 'Aktif', 'Paket keluarga favorit.'),
  ('Sorgum Healthy Bulk 3kg', 'SG-3K', 399000, 34, 'Restock', 'Paket hemat untuk stok bulanan.');

INSERT INTO customers (name, email, phone, city, address, status) VALUES
  ('Fina Anjani', 'fina@gmail.com', '081234567890', 'Bandung', 'Jl. Melati No. 12', 'Aktif'),
  ('Rio Pratama', 'rio@gmail.com', '081245678901', 'Jakarta', 'Jl. Anggrek No. 9', 'Aktif'),
  ('Maya Kusuma', 'maya@gmail.com', '081256789012', 'Surabaya', 'Jl. Kenanga No. 4', 'Pasif');

INSERT INTO orders (order_code, customer_id, status, courier, subtotal, shipping_fee, discount, total, notes) VALUES
  ('INV-2407', 1, 'Menunggu', 'Reguler', 159000, 18000, 10000, 167000, 'Titip di resepsionis'),
  ('INV-2408', 2, 'Dikirim', 'Express', 399000, 35000, 0, 434000, NULL),
  ('INV-2409', 3, 'Diproses', 'Reguler', 49000, 18000, 0, 67000, NULL);

INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
  (1, 2, 1, 159000),
  (2, 3, 1, 399000),
  (3, 1, 1, 49000);

INSERT INTO payments (order_id, sender_name, bank_name, amount, proof_path, status) VALUES
  (2, 'Rio Pratama', 'BCA', 434000, NULL, 'Diterima');

INSERT INTO faqs (question, answer, status, sort_order) VALUES
  ('Apakah sorgum cocok untuk diet rendah gula?', 'Ya, sorgum memiliki indeks glikemik rendah dan kaya serat sehingga aman untuk diet seimbang.', 'Aktif', 1),
  ('Berapa lama proses pengiriman?', 'Pengiriman diproses maksimal 1x24 jam setelah pembayaran terkonfirmasi.', 'Aktif', 2),
  ('Apakah ada panduan memasak?', 'Setiap pembelian mendapatkan kartu resep dan akses ke tips meal prep mingguan.', 'Aktif', 3);
