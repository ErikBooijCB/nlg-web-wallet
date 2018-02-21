CREATE DATABASE IF NOT EXISTS gulden_wallet;
GRANT USAGE ON *.* TO  migration_user@'%';
DROP USER migration_user@'%';
CREATE USER migration_user@'%' IDENTIFIED BY 'migration_password';
GRANT CREATE, ALTER, DROP, ALTER ROUTINE, CREATE VIEW, SELECT, INSERT, UPDATE, DELETE ON gulden_wallet.* TO migration_user@'%';

