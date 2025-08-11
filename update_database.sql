-- Cập nhật bảng transactions để thêm các cột mới cho thông tin thẻ cào
ALTER TABLE transactions
ADD COLUMN card_code VARCHAR(50) DEFAULT NULL,
ADD COLUMN serial_number VARCHAR(50) DEFAULT NULL,
ADD COLUMN game_type VARCHAR(20) DEFAULT NULL;