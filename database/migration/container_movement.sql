drop table if exists container_movements;
create table container_movements(
    id int(10) not null primary key auto_increment,
    user_id int(10),
    platform_id int(10),
    container_id int(10),
    customer_id int(10),
    entry_type enum('pick-up','delivery'),
    created_at timestamp default now()
);