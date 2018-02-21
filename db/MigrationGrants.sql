GRANT CREATE, ALTER, DROP, ALTER ROUTINE, CREATE VIEW ON gulden_wallet.* TO migration_user@'%';
GRANT SELECT, INSERT, UPDATE, DELETE ON gulden_wallet.phinxlog TO migration_user@'%';
