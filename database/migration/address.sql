drop table if exists address;
create table address(
    id int(10) not null primary key auto_increment,
    parent_id int(10) not null,
    parent_key varchar(50),
    street varchar(100),
    street_id int(10),
    barangay varchar(100),
    area varchar(100),
    corner varchar(100),
    building varchar(100),
    house_number varchar(100),
    tower varchar(100),
    city varchar(100),

    created_at timestamp default now(),
    updated_at timestamp default now() ON UPDATE now()
); 


drop table if exists address_source;
create table address_source(
    id int(10) not null primary key auto_increment,
    abbr varchar(5) unique,
    type varchar(50),
    value varchar(250),
    is_active boolean default true,
    created_at timestamp default now()
);