# Projeto: Site de classificado

### Ferramentas usadas neste projeto

Ferramenta   | Usado para |
------------ | ------------------ |
Gedit | Nos scripts para criar as tabelas
Flyway | Para migração do banco de dados
Atom | Nos códigos PHP

### Criar o Banco de dados
```bash
create database classificado default character set utf8mb4 default collate utf8mb4_general_ci;
```

### Lista das tabelas

Nome |
----- |
usuarios |
categorias |
anuncios |
anuncio_images |

### Foram definidas as seguintes constantes no arquivo access.php

Constantes  |
------------------ |
define("WIDTH_MAX", 500); |
define("HEIGHT_MAX", 500); |
define("FILE_LOCATION", "assets/images/anuncios/"); |
