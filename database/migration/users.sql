drop table if exists users;
create table users(
    id int(10) not null primary key auto_increment,
    reference varchar(50),
    firstname varchar(50),
    lastname varchar(50),
    username varchar(50) unique,
    password varchar(250),
    parent_id int(10) not null,
    user_type enum('PLATFORM','VENDOR'),
    access_type varchar(50),
    created_at timestamp default now() ON UPDATE now(),
    updated_at timestamp default now() ON UPDATE now()
);


/**
*acces
*manager,administrator, cx_delivery, cx_field_agent, cx_administrator
*/

