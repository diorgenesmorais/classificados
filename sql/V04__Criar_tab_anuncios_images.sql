create table anuncio_images (
	id int unsigned auto_increment,
	anuncio_id int unsigned not null,
	url varchar(100) not null,
	foreign key(anuncio_id) references anuncios(id),
	primary key(id)
) engine=InnoDB default charset=utf8;
