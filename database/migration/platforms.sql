create table platforms(
    id int(10) not null primary key auto_increment,
    reference varchar(100),
    platform_name varchar(100) unique,
    contact_number varchar(100),
    is_active boolean default true,
    created_at timestamp default now(),
    updated_at timestamp default now() ON UPDATE now()
)