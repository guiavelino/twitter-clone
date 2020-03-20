create database twitter_clone;

use twitter_clone;

create table usuarios(
	id int not null primary key AUTO_INCREMENT,
	nome varchar(100) not null,
	email varchar(150) not null,
	senha varchar(32) not null
);

CREATE table tweets(
	id int not null primary key AUTO_INCREMENT,
    id_usuario int not null,
    tweet varchar(140) not null,
    data datetime default CURRENT_TIMESTAMP
);