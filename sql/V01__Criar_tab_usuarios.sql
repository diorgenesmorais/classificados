create table usuarios (
	id int unsigned auto_increment,
	nome varchar(100) not null,
	email varchar(100) not null,
	senha varchar(32) not null,
	telefone varchar(11),
	primary key(id)
) engine=InnoDB default charset=utf8mb4;
