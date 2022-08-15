drop table if exists customers;
create table customers(
    id int(10) not null primary key auto_increment,
    parent_id int(10),
    full_name varchar(100),
    address_id int(10),
    is_active boolean default true,
    created_at timestamp default now(),
    updated_at timestamp default now() ON UPDATE now()
);