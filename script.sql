DROP DATABASE IF EXISTS social_network;
CREATE DATABASE social_network CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
use social_network;

create table post(
id int not null primary key auto_increment,
content text,
time timestamp not null default current_timestamp ,
image text
)engine=InnoDB;

create table comments(
id int not null primary key auto_increment,
content text,
postId int,
foreign key (postId) references post(id) on delete cascade
)engine=InnoDB;

insert into post (content) values ('Evo danas pada kiša opet :('), ('Jedem jagode.');
insert into comments (content, postId) values ('Vani je bas lose vrijeme' , 1);