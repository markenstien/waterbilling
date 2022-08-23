create table customer_wallet(
    id int(10) not null primary key auto_increment,
    customer_id int(10) not null,
    amount decimal(10,2),
    description text,
    created_at timestamp default now()
);