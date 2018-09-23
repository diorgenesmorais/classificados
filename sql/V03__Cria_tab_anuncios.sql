create table anuncios (
	id int unsigned auto_increment,
	usuario_id int unsigned not null,
	categoria_id int unsigned not null,
	titulo varchar(100),
	descricao text,
	valor decimal(10,2) default 0,
	estado tinyint unsigned,
	foreign key(usuario_id) references usuarios(id),
	foreign key(categoria_id) references categorias(id),
	primary key(id)
) engine=InnoDB default charset=utf8mb4;
