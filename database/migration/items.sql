create table items(
    id int(10) not null primary key auto_increment,
    item_code varchar(50),
    type varchar(50),
    owner_id int(10) comment 'owner_id',
    created_at timestamp default now()
);