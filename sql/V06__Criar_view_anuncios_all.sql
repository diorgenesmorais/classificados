create view anuncios_all
  as select *,
  (select categorias.nome from categorias where categorias.id=categoria_id) as categoria,
  (select usuarios.telefone from usuarios where usuarios.id=usuario_id) as telefone
  from anuncios;
