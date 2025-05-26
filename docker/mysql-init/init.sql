-- Crée la DB principale (si nécessaire)


-- Crée la DB de test
CREATE DATABASE IF NOT EXISTS rutix_db_test;

-- Crée un utilisateur spécifique pour la DB test avec tous les droits
CREATE USER IF NOT EXISTS 'test_user'@'%' IDENTIFIED BY 'test_password';
GRANT ALL PRIVILEGES ON rutix_db_test.* TO 'test_user'@'%';

FLUSH PRIVILEGES;
