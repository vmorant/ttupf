CREATE TABLE assignatura (id BIGINT AUTO_INCREMENT, nom VARCHAR(255) NOT NULL, carrera_curs_id BIGINT NOT NULL, INDEX carrera_curs_id_idx (carrera_curs_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE carrera_curs (id BIGINT AUTO_INCREMENT, nom VARCHAR(255) NOT NULL, curs BIGINT NOT NULL, grup_teoria BIGINT NOT NULL, url_horari VARCHAR(255) NOT NULL UNIQUE, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sessio (id BIGINT AUTO_INCREMENT, data_hora_inici DATETIME NOT NULL, data_hora_fi DATETIME NOT NULL, assignatura_id BIGINT NOT NULL, aula VARCHAR(255), tipus VARCHAR(255) NOT NULL, grup_seminari VARCHAR(255), grup_practiques VARCHAR(255), INDEX assignatura_id_idx (assignatura_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE usuari_te_assignatures (id BIGINT AUTO_INCREMENT, usuari_id BIGINT NOT NULL, assignatura_id BIGINT NOT NULL, grup_practiques BIGINT, grup_seminari BIGINT, INDEX usuari_id_idx (usuari_id), INDEX assignatura_id_idx (assignatura_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_forgot_password (id BIGINT AUTO_INCREMENT, user_id BIGINT NOT NULL, unique_key VARCHAR(255), expires_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_group (id BIGINT AUTO_INCREMENT, name VARCHAR(255) UNIQUE, description TEXT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_group_permission (group_id BIGINT, permission_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(group_id, permission_id)) ENGINE = INNODB;
CREATE TABLE sf_guard_permission (id BIGINT AUTO_INCREMENT, name VARCHAR(255) UNIQUE, description TEXT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_remember_key (id BIGINT AUTO_INCREMENT, user_id BIGINT, remember_key VARCHAR(32), ip_address VARCHAR(50), created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user (id BIGINT AUTO_INCREMENT, first_name VARCHAR(255), last_name VARCHAR(255), email_address VARCHAR(255) NOT NULL UNIQUE, username VARCHAR(128) NOT NULL UNIQUE, algorithm VARCHAR(128) DEFAULT 'sha1' NOT NULL, salt VARCHAR(128), password VARCHAR(128), is_active TINYINT(1) DEFAULT '1', is_super_admin TINYINT(1) DEFAULT '0', last_login DATETIME, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX is_active_idx_idx (is_active), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user_group (user_id BIGINT, group_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(user_id, group_id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user_permission (user_id BIGINT, permission_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(user_id, permission_id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user_profile (id BIGINT AUTO_INCREMENT, user_id BIGINT NOT NULL, email_new VARCHAR(255) UNIQUE, firstname VARCHAR(255), lastname VARCHAR(255), validate_at DATETIME, validate VARCHAR(33), created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX user_id_unique_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;
ALTER TABLE assignatura ADD CONSTRAINT assignatura_carrera_curs_id_carrera_curs_id FOREIGN KEY (carrera_curs_id) REFERENCES carrera_curs(id) ON DELETE CASCADE;
ALTER TABLE sessio ADD CONSTRAINT sessio_assignatura_id_assignatura_id FOREIGN KEY (assignatura_id) REFERENCES assignatura(id) ON DELETE CASCADE;
ALTER TABLE usuari_te_assignatures ADD CONSTRAINT usuari_te_assignatures_usuari_id_sf_guard_user_id FOREIGN KEY (usuari_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE usuari_te_assignatures ADD CONSTRAINT usuari_te_assignatures_assignatura_id_assignatura_id FOREIGN KEY (assignatura_id) REFERENCES assignatura(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_forgot_password ADD CONSTRAINT sf_guard_forgot_password_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_group_permission ADD CONSTRAINT sf_guard_group_permission_permission_id_sf_guard_permission_id FOREIGN KEY (permission_id) REFERENCES sf_guard_permission(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_group_permission ADD CONSTRAINT sf_guard_group_permission_group_id_sf_guard_group_id FOREIGN KEY (group_id) REFERENCES sf_guard_group(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_remember_key ADD CONSTRAINT sf_guard_remember_key_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_group ADD CONSTRAINT sf_guard_user_group_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_group ADD CONSTRAINT sf_guard_user_group_group_id_sf_guard_group_id FOREIGN KEY (group_id) REFERENCES sf_guard_group(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_permission ADD CONSTRAINT sf_guard_user_permission_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_permission ADD CONSTRAINT sf_guard_user_permission_permission_id_sf_guard_permission_id FOREIGN KEY (permission_id) REFERENCES sf_guard_permission(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_profile ADD CONSTRAINT sf_guard_user_profile_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
