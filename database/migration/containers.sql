create table containers(
    id int(10) not null primary key auto_increment,
    container_label varchar(50),
    container_type enum('JAG','CONTAINER_DISPENSER'),
    platform_id int(10) not null comment 'from what platform',
    customer_id int(10),
    created_at timestamp default now()
);